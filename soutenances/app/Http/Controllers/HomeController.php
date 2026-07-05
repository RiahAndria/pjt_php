<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Les statistiques globales (déjà faites)
        $effectifsParNiveau = Etudiant::select('niveau', DB::raw('count(*) as effectif'))
            ->groupBy('niveau')
            ->orderBy('niveau')
            ->get();

        $totalEtudiants = Etudiant::count();

        // 2. On récupère TOUS les étudiants pour notre nouveau tableau filtrable
        $tousEtudiants = Etudiant::orderBy('nom')->orderBy('prenoms')->get();

        // 3. On envoie tout à la vue welcome
        return view('welcome', compact('effectifsParNiveau', 'totalEtudiants', 'tousEtudiants'));
    }
}