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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->string('matricule', 50)->primary(); // Clé primaire en string
            $table->string('nom', 100);
            $table->string('prenoms', 100);
            $table->char('niveau', 2); // (L1, L2, L3, M1, M2)
            $table->char('parcours', 2); // (GB, SR, IG)
            $table->string('adr_email', 150)->unique();
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('nom');
            $table->index('matricule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
