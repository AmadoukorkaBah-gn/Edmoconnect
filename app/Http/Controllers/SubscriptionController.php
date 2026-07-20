<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\Forfait;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    // Étape "Choix du forfait" de la maquette
    public function choisirForfait()
    {
        $forfaits = Forfait::where('actif', true)->get();
        return view('client.choix-forfait', compact('forfaits'));
    }

    // Étape "Paiement" : crée l'abonnement (en_attente) + le paiement (en_attente)
    // puis initie la requête vers l'API Orange Money / MTN
    public function initierPaiement(Request $request)
    {
        $request->validate([
            'forfait_id' => 'required|exists:forfaits,id',
            'telephone'  => 'required|string',
            'operateur'  => 'required|in:orange_money,mtn_money',
        ]);

        $forfait = Forfait::findOrFail($request->forfait_id);

        $abonnement = Abonnement::create([
            'user_id' => auth()->id(),
            'forfait_id' => $forfait->id,
            'mikrotik_username' => $request->telephone,
            'mikrotik_password' => Str::random(6),
            'statut' => 'en_attente',
        ]);

        $paiement = Paiement::create([
            'abonnement_id' => $abonnement->id,
            'reference' => 'WH-' . now()->format('Y-m-d') . '-' . Str::padLeft($abonnement->id, 3, '0'),
            'operateur' => $request->operateur,
            'montant' => $forfait->prix,
            'statut' => 'en_attente',
        ]);

        // TODO: appeler ici l'API Orange Money / MTN pour déclencher la demande
        // de paiement (push USSD) vers le téléphone du client, avec $paiement->reference
        // en identifiant externe pour faire le lien au retour du webhook.

        return view('client.paiement-en-cours', compact('paiement'));
    }

    // Étape "Abonnement actif" : le client consulte l'état de son forfait
    public function monAbonnement()
    {
        $abonnement = Abonnement::where('user_id', auth()->id())
            ->latest()
            ->firstOrFail();

        return view('client.abonnement-actif', compact('abonnement'));
    }
}