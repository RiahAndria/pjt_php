<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    protected $primaryKey = 'idprof';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['idprof', 'nom', 'prenoms', 'civilite', 'grade'];
}