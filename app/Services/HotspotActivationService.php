<?php

namespace App\Services;

use App\Models\Abonnement;
use Illuminate\Support\Facades\Log;

class HotspotActivationService
{
    private const MAX_TENTATIVES = 5;

    public function activer(Abonnement $abonnement): void
    {
        $abonnement->loadMissing(['forfait', 'hotspot.mikrotikServer', 'user']);

        $premiereActivation = ! $abonnement->hotspot_username;

        if ($premiereActivation) {
            $abonnement->hotspot_username = $this->normaliserTelephone($abonnement->user->telephone);
            $abonnement->hotspot_password = (string) random_int(1000, 9999);
            $abonnement->save();

            $this->envoyerSmsIdentifiants($abonnement);
        }

        $this->synchroniserMikrotik($abonnement);
    }

    /**
     * Tente de pousser le compte hotspot sur MikroTik.
     * Appelable a la creation initiale ET lors des reessais automatiques.
     */
    public function synchroniserMikrotik(Abonnement $abonnement): bool
    {
        $abonnement->loadMissing(['forfait', 'hotspot.mikrotikServer']);

        $mikrotikServer = $abonnement->hotspot->mikrotikServer;

        if (! $mikrotikServer || ! $mikrotikServer->is_active) {
            $this->marquerEchec($abonnement, 'Aucun serveur MikroTik actif pour ce hotspot');
            return false;
        }

        $mikrotik = new MikrotikService($mikrotikServer);

        $result = $mikrotik->createHotspotUser(
            $abonnement->hotspot_username,
            $abonnement->hotspot_password,
            $abonnement->forfait->mikrotik_profile,
            $abonnement->forfait->duree
        );

        if ($result['success']) {
            $abonnement->update([
                'sync_mikrotik' => 'synced',
                'sync_tentatives' => 0,
                'dernier_essai_sync' => now(),
            ]);

            Log::info('[COMPTE HOTSPOT CREE SUR MIKROTIK]', [
                'abonnement_id' => $abonnement->id,
                'username' => $abonnement->hotspot_username,
            ]);

            return true;
        }

        $this->marquerEchec($abonnement, $result['error']);
        return false;
    }

    private function marquerEchec(Abonnement $abonnement, string $erreur): void
    {
        $tentatives = $abonnement->sync_tentatives + 1;

        $abonnement->update([
            'sync_mikrotik' => 'failed',
            'sync_tentatives' => $tentatives,
            'dernier_essai_sync' => now(),
        ]);

        Log::error('[ECHEC SYNC MIKROTIK]', [
            'abonnement_id' => $abonnement->id,
            'tentative' => $tentatives,
            'error' => $erreur,
        ]);

        if ($tentatives >= self::MAX_TENTATIVES) {
            Log::critical('[ECHEC DEFINITIF SYNC MIKROTIK]', [
                'abonnement_id' => $abonnement->id,
                'message' => "L'abonnement #{$abonnement->id} n'a pas pu etre synchronise apres " . self::MAX_TENTATIVES . " tentatives. Intervention manuelle requise.",
            ]);
        }
    }

    private function envoyerSmsIdentifiants(Abonnement $abonnement): void
    {
        $message = "WiFi Zone : votre acces est actif !\n"
            . "Identifiant: {$abonnement->hotspot_username}\n"
            . "Mot de passe: {$abonnement->hotspot_password}\n"
            . "Valide jusqu'au " . $abonnement->date_fin->format('d/m/Y H:i') . ".";

        $sms = new SmsService();
        $result = $sms->send($abonnement->user->telephone, $message);

        if (! $result['success']) {
            Log::warning('[ECHEC SMS IDENTIFIANTS]', [
                'abonnement_id' => $abonnement->id,
                'error' => $result['error'],
            ]);
        }
    }

    private function normaliserTelephone(string $telephone): string
    {
        return preg_replace('/[^0-9]/', '', $telephone);
    }
}