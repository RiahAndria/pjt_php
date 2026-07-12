<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    public function soutenance()
    {
        return $this->hasOne(Soutenance::class, 'matricule', 'matricule');
    }
    // On indique la clé primaire personnalisée
    protected $primaryKey = 'matricule';
    
    // On précise qu'elle n'est pas auto-incrémentée et que c'est un string
    public $incrementing = false;
    protected $keyType = 'string';

    // Les champs modifiables
    protected $fillable = ['matricule', 'nom', 'prenoms', 'niveau', 'parcours', 'adr_email'];
}