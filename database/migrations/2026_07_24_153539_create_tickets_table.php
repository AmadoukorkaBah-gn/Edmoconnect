<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hotspot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('forfait_id')->constrained()->cascadeOnDelete();

            $table->string('code')->unique();

            // Identifiants Hotspot MikroTik
            $table->string('username')->unique();
            $table->string('password');

            $table->boolean('used')->default(false);
            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};