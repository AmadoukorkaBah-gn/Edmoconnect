<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Services\SmsService;
use Illuminate\Console\Command;

class SendExpirationReminders extends Command
{
    protected $signature = 'wifizone:send-reminders';
    protected $description = 'Envoie un SMS de rappel 30 minutes avant expiration des abonnements';

    public function handle(): int
    {
         $delaiMinutes = \App\Models\Parametre::courant()->rappel_expiration_minutes;

    $abonnements = Abonnement::where('statut', 'active')
        ->where('rappel_envoye', false)
        ->whereBetween('date_fin', [now(), now()->addMinutes($delaiMinutes)])
        ->with(['user', 'forfait'])
        ->get();

        if ($abonnements->isEmpty()) {
            $this->info('Aucun rappel a envoyer.');
            return self::SUCCESS;
        }

        $sms = new SmsService();

        foreach ($abonnements as $abonnement) {
            $minutesRestantes = now()->diffInMinutes($abonnement->date_fin);

            $message = "Cher client : votre forfait \"{$abonnement->forfait->nom}\" expire dans environ {$minutesRestantes} min. "
                . "Renouvelez des maintenant pour ne pas perdre votre connexion.";

            $result = $sms->send($abonnement->user->telephone, $message);

            if ($result['success']) {
                $abonnement->update(['rappel_envoye' => true]);
                $this->info("Rappel envoye pour l'abonnement #{$abonnement->id}");
            } else {
                $this->error("Echec SMS pour l'abonnement #{$abonnement->id}: {$result['error']}");
            }
        }

        return self::SUCCESS;
    }
}