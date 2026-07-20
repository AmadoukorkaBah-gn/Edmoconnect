<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('forfaits', function (Blueprint $table) {

        $table->id();

        // Nom du forfait
        $table->string('nom');

        // Prix en GNF
        $table->decimal('prix', 12, 2);

        // Durée en heures
        $table->integer('duree');

        // Débit descendant (Mbps)
        $table->integer('download_speed')->nullable();

        // Débit montant (Mbps)
        $table->integer('upload_speed')->nullable();

        // Nom du profil Hotspot MikroTik
        $table->string('mikrotik_profile');

        // Description
        $table->text('description')->nullable();

        // Disponible ou non
        $table->boolean('is_active')->default(true);

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('forfaits');
    }
};