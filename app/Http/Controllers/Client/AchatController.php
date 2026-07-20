<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Forfait;
use App\Models\Hotspot;
use App\Models\Paiement;
use App\Models\User;
use App\Services\DjomyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AchatController extends Controller
{
    public function index(Hotspot $hotspot)
    {
        abort_unless($hotspot->is_active, 404);

        $forfaits = $hotspot->forfaits()->where('forfaits.is_active', true)->orderBy('prix')->get();

        $maxSpeed = $forfaits->max('download_speed') ?: 1;
        $forfaits = $forfaits->map(function ($f) use ($maxSpeed) {
            $ratio = $f->download_speed ? $f->download_speed / $maxSpeed : 0.5;
            $f->signal_bars = max(1, min(4, (int) ceil($ratio * 4)));
            $f->duree_label = $this->formatDuree($f->duree);
            return $f;
        });

        return view('client.accueil', compact('hotspot', 'forfaits'));
    }

    public function paiement(Hotspot $hotspot, Forfait $forfait)
    {
        abort_unless($hotspot->is_active && $forfait->is_active, 404);
        abort_unless($hotspot->forfaits()->where('forfaits.id', $forfait->id)->exists(), 404);

        $forfait->duree_label = $this->formatDuree($forfait->duree);

        return view('client.paiement', compact('hotspot', 'forfait'));
    }

    public function initierPaiement(Request $request, Hotspot $hotspot, Forfait $forfait, DjomyService $djomy)
    {
        $validated = $request->validate([
            'telephone' => 'required|string|min:8|max:20',
        ]);

        abort_unless($hotspot->forfaits()->where('forfaits.id', $forfait->id)->exists(), 404);

        // Compte client minimal basé uniquement sur le téléphone (pas de mot de passe à saisir)
        $user = User::firstOrCreate(
            ['telephone' => $validated['telephone']],
            [
                'name' => 'Client ' . $validated['telephone'],
                'password' => bcrypt(str()->random(24)),
                'status' => 'active',
            ]
        );

        $paiement = Paiement::create([
            'user_id' => $user->id,
            'forfait_id' => $forfait->id,
            'hotspot_id' => $hotspot->id,
            'reference' => 'PENDING-' . strtoupper(str()->random(10)),
            'montant' => $forfait->prix,
            'statut' => 'pending',
        ]);

        try {
            $result = $djomy->createPayment([
                'montant' => $forfait->prix,
                'telephone' => $validated['telephone'],
                'user_id' => $user->id,
                'forfait_id' => $forfait->id,
                'hotspot_id' => $hotspot->id,
                'forfait_nom' => $forfait->nom,
            ]);

           $paiement->update([
    'reference' => $result['reference'],
    'transaction_id' => $result['transaction_id'],
    'response_api' => [
        'transaction_id' => $result['transaction_id'],
    ],
]);

            return redirect($result['redirect_url']);

        } catch (\Throwable $e) {
            Log::error('❌ DJOMY INIT ERROR (WiFi Zone)', [
                'paiement_id' => $paiement->id,
                'message' => $e->getMessage(),
            ]);

            $paiement->update(['statut' => 'failed']);

            return redirect()
                ->route('client.paiement', [$hotspot, $forfait])
                ->with('error', "Erreur lors de l'initialisation du paiement. Réessaie.");
        }
    }

    public function retourSucces(Request $request, DjomyService $djomy)
    {
        $reference = $request->query('ref');
        $paiement = Paiement::where('reference', $reference)->with(['forfait', 'hotspot', 'user'])->first();

        if (! $paiement) {
            return view('client.paiement-introuvable');
        }

        // Vérification immédiate (le webhook fera aussi cette mise à jour de façon fiable, indépendamment)
        try {
            $transactionId = $paiement->response_api['transaction_id'] ?? null;

            if ($transactionId && $paiement->statut === 'pending') {
                $verification = $djomy->checkPayment($transactionId);
                $status = strtoupper($verification['data']['status'] ?? '');

                if ($status === 'SUCCESS') {
                    $this->activerAbonnement($paiement);
                } elseif ($status === 'FAILED') {
                    $paiement->update(['statut' => 'failed']);
                }
            }
        } catch (\Throwable $e) {
            Log::error('❌ Erreur vérification retour Djomy (WiFi Zone)', ['message' => $e->getMessage()]);
        }

        $paiement->refresh();

        return view('client.retour', compact('paiement'));
    }

    public function retourAnnule(Request $request)
    {
        $reference = $request->query('ref');
        $paiement = Paiement::where('reference', $reference)->first();

        if ($paiement) {
            $paiement->update(['statut' => 'cancelled']);
        }

        return view('client.paiement-annule');
    }

    public function monAbonnement(Request $request)
    {
        $telephone = $request->query('telephone');
        $abonnement = null;

        if ($telephone) {
            $user = User::where('telephone', $telephone)->first();

            $abonnement = $user
                ? Abonnement::where('user_id', $user->id)
                    ->where('statut', 'active')
                    ->where('date_fin', '>=', now())
                    ->with(['hotspot', 'forfait'])
                    ->latest()
                    ->first()
                : null;
        }

        return view('client.abonnement', compact('abonnement', 'telephone'));
    }

    private function activerAbonnement(Paiement $paiement): void
    {
        // Idempotence : si un webhook est déjà passé avant nous, on ne recrée pas l'abonnement
        $dejaCree = Abonnement::where('reference_paiement', $paiement->reference)->exists();

        if ($dejaCree) {
            $paiement->update(['statut' => 'success']);
            return;
        }

        DB::transaction(function () use ($paiement) {
            $paiement->update(['statut' => 'success']);

            Abonnement::create([
                'user_id' => $paiement->user_id,
                'hotspot_id' => $paiement->hotspot_id,
                'forfait_id' => $paiement->forfait_id,
                'date_debut' => now(),
                'date_fin' => now()->addHours($paiement->forfait->duree),
                'statut' => 'active',
                'reference_paiement' => $paiement->reference,
            ]);
        });
    }

    private function formatDuree(int $heures): string
    {
        if ($heures < 24) {
            return $heures . ' h';
        }

        $jours = intdiv($heures, 24);
        $reste = $heures % 24;

        return $reste === 0
            ? $jours . ' jour' . ($jours > 1 ? 's' : '')
            : $jours . 'j ' . $reste . 'h';
    }
}