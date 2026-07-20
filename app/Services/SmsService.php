<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $serviceId;
    protected string $secretToken;
    protected string $senderName;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serviceId = config('nimbasms.service_id');
        $this->secretToken = config('nimbasms.secret_token');
        $this->senderName = config('nimbasms.sender_name');
        $this->baseUrl = config('nimbasms.base_url');
    }

    /**
     * Envoie un SMS a un numero de telephone.
     */
    public function send(string $telephone, string $message): array
    {
        $numero = $this->normaliserNumero($telephone);

        try {
            $response = Http::withBasicAuth($this->serviceId, $this->secretToken)
            
                ->post($this->baseUrl . '/messages', [
                    'sender_name' => $this->senderName,
                    'to' => [$numero],
                    'message' => $message,
                    'channel' => 'sms',
                ]);
                dd(
    $response->status(),
    $response->body(),
    $response->json()
);

            if (! $response->successful()) {
                Log::error('[NIMBA SMS ERROR]', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'numero' => $numero,
                ]);

                return ['success' => false, 'error' => $response->body()];
            }

            return [
                'success' => true,
                'messageid' => $response->json('messageid'),
                'cost' => $response->json('message_cost'),
            ];
        } catch (\Throwable $e) {
            Log::error('[NIMBA SMS EXCEPTION]', [
                'message' => $e->getMessage(),
                'numero' => $numero,
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Met le numero au format attendu par Nimba SMS (224XXXXXXXXX).
     */
    private function normaliserNumero(string $telephone): string
    {
        $numero = preg_replace('/[^0-9]/', '', $telephone);

        // Si le numero ne commence pas deja par l'indicatif Guinee (224), on l'ajoute
        if (! str_starts_with($numero, '224')) {
            $numero = '224' . ltrim($numero, '0');
        }

        return $numero;
    }
}