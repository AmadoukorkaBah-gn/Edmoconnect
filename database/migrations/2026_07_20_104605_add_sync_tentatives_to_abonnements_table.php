<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->unsignedTinyInteger('sync_tentatives')->default(0)->after('sync_mikrotik');
            $table->timestamp('dernier_essai_sync')->nullable()->after('sync_tentatives');
        });
    }

    public function down(): void
    {
        Schema::table('abonnements', function (Blueprint $table) {
            $table->dropColumn(['sync_tentatives', 'dernier_essai_sync']);
        });
    }
};