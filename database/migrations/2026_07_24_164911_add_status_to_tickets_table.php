<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->enum('status', [
                'available',
                'activated',
                'cancelled'
            ])
            ->default('available')
            ->after('password');


            $table->timestamp('activated_at')
                ->nullable()
                ->after('status');

        });
    }


    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->dropColumn([
                'status',
                'activated_at'
            ]);

        });
    }
};