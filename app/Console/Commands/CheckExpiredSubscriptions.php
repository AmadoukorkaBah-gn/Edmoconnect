<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Services\MikrotikService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'wifizone:check-expirations';
    protected $description = 'Desactive automatiquement les abonnements WiFi expires sur MikroTik';

    public function handle(): int
    {
        $expires = Abonnement::where('statut', 'active')
            ->where('date_fin', '<=', now())
            ->with('hotspot.mikrotikServer')
            ->get();

        if ($expires->isEmpty()) {
            $this->info('Aucun abonnement expire a traiter.');
            return self::SUCCESS;
        }

        foreach ($expires as $abonnement) {
            $mikrotikServer = $abonnement->hotspot->mikrotikServer ?? null;

            if ($mikrotikServer && $mikrotikServer->is_active && $abonnement->hotspot_username) {
                $mikrotik = new MikrotikService($mikrotikServer);
                $result = $mikrotik->disableHotspotUser($abonnement->hotspot_username);

                if (! $result['success']) {
                    Log::warning('[EXPIRATION] Echec desactivation MikroTik', [
                        'abonnement_id' => $abonnement->id,
                        'error' => $result['error'],
                    ]);
                    $this->error("Echec MikroTik pour l'abonnement #{$abonnement->id}: {$result['error']}");
                }
            }

            $abonnement->update(['statut' => 'expired']);
            $this->info("Abonnement #{$abonnement->id} desactive ({$abonnement->hotspot_username})");
        }

        return self::SUCCESS;
    }
}