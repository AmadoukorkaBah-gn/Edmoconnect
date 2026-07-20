<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Services\HotspotActivationService;
use Illuminate\Console\Command;

class RetryMikrotikSync extends Command
{
    protected $signature = 'wifizone:retry-sync';
    protected $description = 'Reessaie de synchroniser les abonnements dont la creation MikroTik a echoue';

    public function handle(HotspotActivationService $service): int
    {
        $abonnements = Abonnement::where('sync_mikrotik', 'failed')
            ->where('statut', 'active')
            ->where('date_fin', '>', now())
            ->where('sync_tentatives', '<', 5)
            ->get();

        if ($abonnements->isEmpty()) {
            $this->info('Aucune synchronisation a reessayer.');
            return self::SUCCESS;
        }

        foreach ($abonnements as $abonnement) {
            $success = $service->synchroniserMikrotik($abonnement);

            $this->info($success
                ? "Abonnement #{$abonnement->id} synchronise avec succes."
                : "Echec persistant pour l'abonnement #{$abonnement->id} (tentative {$abonnement->fresh()->sync_tentatives}/5)."
            );
        }

        return self::SUCCESS;
    }
}