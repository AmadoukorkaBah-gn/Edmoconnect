<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Paiement;
use App\Services\DjomyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DjomyWebhookController extends Controller
{
    public function __construct(protected DjomyService $djomy) {}

    public function handle(Request $request)
    {
        $rawPayload = $request->getContent();
        $signature = $request->header('X-Webhook-Signature', '');

        Log::info('📩 DJOMY WEBHOOK REÇU (WiFi Zone)', [
            'ip' => $request->ip(),
            'payload' => $rawPayload,
        ]);

        if (! $this->djomy->verifyWebhook($rawPayload, $signature)) {
            Log::warning('❌ SIGNATURE WEBHOOK INVALIDE (WiFi Zone)', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $body = json_decode($rawPayload, true);

        if (! is_array($body)) {
            Log::error('❌ PAYLOAD JSON INVALIDE (WiFi Zone)', ['payload' => $rawPayload]);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $eventType = $body['eventType'] ?? null;
        $data = $body['data'] ?? [];
        $status = strtoupper($data['status'] ?? '');
        $transactionId = $data['transactionId'] ?? null;

        if (! $transactionId) {
            Log::warning('⚠️ transactionId manquant (WiFi Zone)', ['body' => $body]);
            return response()->json(['message' => 'ignored'], 200);
        }

        $paiement = Paiement::where('response_api->transaction_id', $transactionId)
            ->with(['forfait', 'hotspot', 'user'])
            ->first();

        if (! $paiement) {
            Log::warning('❌ Paiement introuvable (WiFi Zone)', ['transactionId' => $transactionId]);
            return response()->json(['message' => 'payment not found'], 200);
        }

        // Évite le double traitement (webhook renvoyé plusieurs fois par Djomy, ou déjà traité via retourSucces)
        if (in_array($paiement->statut, ['success', 'failed'])) {
            Log::info('⛔ Paiement déjà traité (WiFi Zone)', [
                'transactionId' => $transactionId,
                'statut' => $paiement->statut,
            ]);
            return response()->json(['message' => 'already processed'], 200);
        }

        match (true) {
            ($eventType === 'payment.success' || $status === 'SUCCESS') => $this->onSuccess($paiement, $data),
            ($eventType === 'payment.failed' || $status === 'FAILED') => $this->onFailed($paiement, $data),
            ($eventType === 'payment.cancelled') => $this->onCancelled($paiement, $data),
            default => Log::info('ℹ️ EVENT NON GÉRÉ (WiFi Zone)', ['eventType' => $eventType, 'status' => $status]),
        };

        return response()->json(['message' => 'OK'], 200);
    }

    private function onSuccess(Paiement $paiement, array $data): void
{
    $methodMap = [
        'OM' => 'Orange Money',
        'MOMO' => 'MTN MoMo',
        'SOUTRA_MONEY' => 'Soutra Money',
        'CARD' => 'Carte bancaire',
    ];

    $methode = $methodMap[strtoupper($data['paymentMethod'] ?? '')] ?? 'Djomy';

    // Idempotence : si l'utilisateur est déjà revenu via retourSucces avant le webhook, ne pas dupliquer
    $dejaCree = Abonnement::where('reference_paiement', $paiement->reference)->exists();

    $abonnement = DB::transaction(function () use ($paiement, $methode, $data, $dejaCree) {
        $paiement->update([
            'statut' => 'success',
            'methode' => $methode,
            'response_api' => array_merge($paiement->response_api ?? [], $data),
        ]);

        if ($dejaCree) {
            return Abonnement::where('reference_paiement', $paiement->reference)->first();
        }

        return Abonnement::create([
            'user_id' => $paiement->user_id,
            'hotspot_id' => $paiement->hotspot_id,
            'forfait_id' => $paiement->forfait_id,
            'date_debut' => now(),
            'date_fin' => now()->addHours($paiement->forfait->duree),
            'statut' => 'active',
            'reference_paiement' => $paiement->reference,
        ]);
    });

    (new \App\Services\HotspotActivationService())->activer($abonnement);

    Log::info('✅ PAIEMENT CONFIRMÉ + ABONNEMENT ACTIVÉ (WiFi Zone)', [
        'paiement_id' => $paiement->id,
        'deja_cree' => $dejaCree,
    ]);
}

    private function onFailed(Paiement $paiement, array $data): void
    {
        $paiement->update([
            'statut' => 'failed',
            'response_api' => array_merge($paiement->response_api ?? [], $data),
        ]);

        Log::warning('❌ PAIEMENT ÉCHOUÉ (WiFi Zone)', ['paiement_id' => $paiement->id]);
    }

    private function onCancelled(Paiement $paiement, array $data): void
    {
        $paiement->update([
            'statut' => 'cancelled',
            'response_api' => array_merge($paiement->response_api ?? [], $data),
        ]);

        Log::warning('🚫 PAIEMENT ANNULÉ (WiFi Zone)', ['paiement_id' => $paiement->id]);
    }
}