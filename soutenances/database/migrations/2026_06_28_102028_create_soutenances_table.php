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
        Schema::create('soutenances', function (Blueprint $table) {
            $table->id(); // Identifiant unique de la soutenance
            $table->string('matricule'); // Lié à l'étudiant
            $table->unsignedBigInteger('idorg');
            $table->date('date_soutenance'); // Lié à l'organisme
            $table->string('annee_univ'); // Format 2022-2023
            $table->integer('note');
            
            // Les membres du jury (Ce sont tous des professeurs, donc reliés à idprof)
            $table->string('president');
            $table->string('examinateur');
            $table->string('rapporteur_int');
            $table->string('rapporteur_ext');

            // --- Déclaration des clés étrangères (Foreign Keys) ---
            
            // Liaison avec les étudiants (Si on supprime un étudiant, ses soutenances sautent)
            $table->foreign('matricule')->references('matricule')->on('etudiants')->onDelete('cascade');
            
            // Liaison avec les organismes
            $table->foreign('idorg')->references('idorg')->on('organismes')->onDelete('cascade');
            
            // Liaison des membres du jury avec la table professeurs
            $table->foreign('president')->references('idprof')->on('professeurs');
            $table->foreign('examinateur')->references('idprof')->on('professeurs');
            $table->foreign('rapporteur_int')->references('idprof')->on('professeurs');
            $table->foreign('rapporteur_ext')->references('idprof')->on('professeurs');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soutenances');
    }
};
