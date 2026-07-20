<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->string('hotspot_username')->nullable()->after('reference_paiement');
            $table->string('hotspot_password')->nullable()->after('hotspot_username');
            $table->enum('sync_mikrotik', ['pending', 'synced', 'failed'])
                ->default('pending')
                ->after('hotspot_password');
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropColumn(['hotspot_username', 'hotspot_password', 'sync_mikrotik']);
        });
    }
};