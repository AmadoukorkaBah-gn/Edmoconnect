<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('nom_entreprise')->default('WiFi Zone');
            $table->string('telephone_support')->nullable();
            $table->string('email_support')->nullable();
            $table->string('adresse')->nullable();
            $table->unsignedInteger('rappel_expiration_minutes')->default(30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};