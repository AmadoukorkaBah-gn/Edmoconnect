<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up(): void
{
    Schema::create('paiements', function (Blueprint $table) {

        $table->id();

        // L'utilisateur qui paie
        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        // Forfait acheté
        $table->foreignId('forfait_id')
              ->constrained()
              ->cascadeOnDelete();

        // Hotspot concerné
        $table->foreignId('hotspot_id')
              ->constrained()
              ->cascadeOnDelete();

        // Référence unique Djomy
        $table->string('reference')->unique();

        // Montant payé
        $table->decimal('montant', 12, 2);

        // Statut du paiement
        $table->enum('statut', [
            'pending',
            'success',
            'failed',
            'cancelled'
        ])->default('pending');

        // Méthode (Djomy mobile money, etc.)
        $table->string('methode')->nullable();

        // Données brutes retour API Djomy
        $table->json('response_api')->nullable();

        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};