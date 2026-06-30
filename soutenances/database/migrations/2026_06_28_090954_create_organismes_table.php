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
        Schema::create('organismes', function (Blueprint $table) {
            $table->id('idorg'); // Clé primaire auto-incrémentée 
            $table->string('design'); // Désignation de l'organisme 
            $table->string('lieu'); // Lieu 
            $table->timestamps(); // Crée les colonnes created_at et updated_at automatiquement
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organismes');
    }
};
