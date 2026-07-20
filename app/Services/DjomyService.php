<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DjomyService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $baseUrl;
    protected string $partnerDomain;

    public function __construct()
    {
        $this->clientId     = config('djomy.client_id');
        $this->clientSecret = config('djomy.client_secret');
        $this->baseUrl      = config('djomy.base_url', 'https://api.djomy.africa');
        $this->partnerDomain = config('app.url');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 AUTH DJOMY
    |--------------------------------------------------------------------------
    */
    public function getAccessToken(): string
    {
        return Cache::remember('djomy_token', now()->addMinutes(50), function () {

            $signature = hash_hmac('sha256', $this->clientId, $this->clientSecret);

            $response = Http::withHeaders([
                'X-API-KEY'        => $this->clientId . ':' . $signature,
                'X-PARTNER-DOMAIN' => $this->partnerDomain,
                'Accept'           => 'application/json',
            ])->post($this->baseUrl . '/v1/auth', [
                'clientId'     => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]);

            if (!$response->successful()) {
                Log::error('DJOMY AUTH ERROR', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                throw new \Exception('Erreur auth Djomy');
            }

            return $response->json('data.accessToken');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 💳 INITIER PAIEMENT WIFI
    |--------------------------------------------------------------------------
    */
    public function createPayment(array $data): array
    {
        $token = $this->getAccessToken();
        $signature = hash_hmac('sha256', $this->clientId, $this->clientSecret);

        $reference = 'WIFI-' . time() . '-' . rand(1000, 9999);

        $payload = [
    'amount' => (int) $data['montant'],
    'countryCode' => 'GN',
    'payerNumber' => $data['telephone'],
    // Le numéro sera saisi sur la page Djomy
    // donc on ne met PAS payerNumber

    'description' => 'Paiement forfait : ' . ($data['forfait_nom'] ?? 'WiFi'),

    'merchantPaymentReference' => $reference,

    'returnUrl' => route('client.paiement.succes', [
    'ref' => $reference,
]),

'cancelUrl' => route('client.paiement.annule', [
    'ref' => $reference,
]),

    'allowedPaymentMethods' => [
        'OM',
        'MOMO',
    ],

    'metadata' => [
        'user_id'    => $data['user_id'],
        'forfait_id' => $data['forfait_id'],
        'hotspot_id' => $data['hotspot_id'],
    ],
];
        $response = Http::withHeaders([
            'Authorization'    => 'Bearer ' . $token,
            'X-API-KEY'        => $this->clientId . ':' . $signature,
            'X-PARTNER-DOMAIN' => $this->partnerDomain,
            'Accept'           => 'application/json',
        ])->post($this->baseUrl . '/v1/payments/gateway', $payload);

        if (!$response->successful()) {
            Log::error('DJOMY PAYMENT ERROR', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \Exception('Erreur création paiement Djomy');
        }

        $result = $response->json('data');

       return [
    'redirect_url'   => $result['redirectUrl'],
    'transaction_id' => $result['transactionId'],
    'reference'      => $reference,
];
    }

    /*
    |--------------------------------------------------------------------------
    | 🔍 VERIFIER PAIEMENT
    |--------------------------------------------------------------------------
    */
    public function checkPayment(string $transactionId): array
    {
        $token = $this->getAccessToken();
        $signature = hash_hmac('sha256', $this->clientId, $this->clientSecret);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'X-API-KEY'     => $this->clientId . ':' . $signature,
        ])->get($this->baseUrl . "/v1/payments/{$transactionId}/status");

        if (!$response->successful()) {
            Log::error('DJOMY CHECK ERROR', [
                'body' => $response->body(),
            ]);

            throw new \Exception('Erreur verification paiement');
        }

        return $response->json();
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 WEBHOOK SIGNATURE
    |--------------------------------------------------------------------------
    */
    public function verifyWebhook(string $payload, string $signature): bool
    {
        if (config('app.env') === 'local') {
            return true;
        }

        if (!str_starts_with($signature, 'v1:')) {
            return false;
        }

        $signature = substr($signature, 3);
        $calc = hash_hmac('sha256', $payload, $this->clientSecret);

        return hash_equals($calc, $signature);
    }
}