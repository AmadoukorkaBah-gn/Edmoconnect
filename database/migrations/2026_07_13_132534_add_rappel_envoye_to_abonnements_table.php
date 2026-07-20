<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->boolean('rappel_envoye')->default(false)->after('sync_mikrotik');
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropColumn('rappel_envoye');
        });
    }
};