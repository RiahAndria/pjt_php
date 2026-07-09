<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    protected $fillable = [
        'matricule', 'idorg', 'annee_univ', 'note', 
        'president', 'examinateur', 'rapporteur_int', 'rapporteur_ext',
        'date_soutenance'
    ];

    protected $casts = [
        'date_soutenance' => 'date',
    ];

    // --- Relations utilisées pour la génération du procès-verbal ---

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'matricule', 'matricule');
    }

    public function organisme()
    {
        return $this->belongsTo(Organisme::class, 'idorg', 'idorg');
    }

    public function presidentProf()
    {
        return $this->belongsTo(Professeur::class, 'president', 'idprof');
    }

    public function examinateurProf()
    {
        return $this->belongsTo(Professeur::class, 'examinateur', 'idprof');
    }

    public function rapporteurInt()
    {
        return $this->belongsTo(Professeur::class, 'rapporteur_int', 'idprof');
    }

    public function rapporteurExt()
    {
        return $this->belongsTo(Professeur::class, 'rapporteur_ext', 'idprof');
    }
}