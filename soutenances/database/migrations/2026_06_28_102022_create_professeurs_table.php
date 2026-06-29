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
        Schema::create('professeurs', function (Blueprint $table) {
            $table->string('idprof')->primary(); // Clé primaire en texte (ex: P001)
            $table->string('nom');
            $table->string('prenoms');
            $table->string('civilite'); // Liste de choix gérée dans le formulaire (Mr, Mlle, Mme)
            $table->string('grade');    // Liste de choix gérée dans le formulaire (Professeur titulaire, Maître de Conférences, Assistant)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};
