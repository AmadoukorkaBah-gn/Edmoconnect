<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Relation avec les rôles
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            // Téléphone (utilisé pour la connexion)
            $table->string('telephone', 20)
                ->unique()
                ->after('name');

            // Adresse MAC du client
            $table->string('mac_address')
                ->nullable()
                ->after('telephone');

            // Statut du compte
            $table->enum('status', [
                'active',
                'inactive',
                'blocked'
            ])->default('active');

            // Photo de profil
            $table->string('photo')
                ->nullable();

            // Email facultatif
            $table->string('email')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['role_id']);

            $table->dropColumn([
                'role_id',
                'telephone',
                'mac_address',
                'status',
                'photo'
            ]);
        });
    }
};