<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('abonnements', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('hotspot_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('forfait_id')
              ->constrained()
              ->cascadeOnDelete();

        // Date de début
        $table->dateTime('date_debut');

$table->dateTime('date_fin');

        // Statut
        $table->enum('statut', [
            'pending',
            'active',
            'expired',
            'suspended',
            'cancelled'
        ])->default('pending');

        // Référence du paiement Djomy
        $table->string('reference_paiement')->nullable();

        // Commentaires
        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};