<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    protected $fillable = [
        'matricule', 'idorg', 'annee_univ', 'note', 
        'president', 'examinateur', 'rapporteur_int', 'rapporteur_ext'
    ];
}