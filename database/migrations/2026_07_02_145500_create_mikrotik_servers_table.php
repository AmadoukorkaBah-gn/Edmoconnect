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
    Schema::create('mikrotik_servers', function (Blueprint $table) {
        $table->id();

        $table->string('name'); // Nom du serveur (Conakry Centre, Sonfonia...)

        $table->string('host'); // Adresse IP ou nom de domaine

        $table->integer('port')->default(8728);

        $table->string('username');

        $table->string('password');

        $table->boolean('ssl')->default(false);

        $table->string('location')->nullable();

        $table->boolean('is_active')->default(true);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mikrotik_servers');
    }
};
