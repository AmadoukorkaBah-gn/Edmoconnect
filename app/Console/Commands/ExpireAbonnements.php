<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Services\MikrotikService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireAbonnements extends Command
{
    protected $signature = 'abonnements:expire';

    protected $description = 'Desactive les abonnements arrives a echeance sur les routeurs MikroTik';

    public function handle(): void
    {
        $abonnementsExpires = Abonnement::where('statut', 'active')
            ->where('date_fin', '<', now())
            ->with(['hotspot.mikrotikServer'])
            ->get();

        if ($abonnementsExpires->isEmpty()) {
            $this->info('Aucun abonnement a expirer.');
            return;
        }

        foreach ($abonnementsExpires as $abonnement) {
            $mikrotikServer = $abonnement->hotspot->mikrotikServer ?? null;

            if ($mikrotikServer && $mikrotikServer->is_active && $abonnement->hotspot_username) {
                $mikrotik = new MikrotikService($mikrotikServer);
                $result = $mikrotik->disableHotspotUser($abonnement->hotspot_username);

                if (! $result['success']) {
                    Log::warning('[EXPIRATION] Echec desactivation MikroTik', [
                        'abonnement_id' => $abonnement->id,
                        'error' => $result['error'],
                    ]);
                }
            }

            $abonnement->update(['statut' => 'expired']);

            $this->info("Abonnement #{$abonnement->id} expire et desactive.");
        }
    }
}