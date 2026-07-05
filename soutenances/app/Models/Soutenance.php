<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    protected $fillable = [
        'matricule', 
        'idorg', 
        'date_soutenance', // <--- Ajouté ici
        'annee_univ', 
        'note', 
        'president', 
        'examinateur', 
        'rapporteur_int', 
        'rapporteur_ext'
    ];

    // Relation pour récupérer l'étudiant
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'matricule', 'matricule');
    }

    // Relation pour récupérer l'organisme[cite: 4]
    public function organisme()
    {
        return $this->belongsTo(Organisme::class, 'idorg', 'idorg');
    }

    // Relations pour le jury (liés à la table professeurs)[cite: 4]
    public function profPresident()
    {
        return $this->belongsTo(Professeur::class, 'president', 'idprof');
    }

    public function profExaminateur()
    {
        return $this->belongsTo(Professeur::class, 'examinateur', 'idprof');
    }
}