<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Soutenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Les statistiques globales (déjà faites)
        $effectifsParNiveau = Etudiant::select('niveau', DB::raw('count(*) as effectif'))
            ->groupBy('niveau')
            ->orderBy('niveau')
            ->get();

        $totalEtudiants = Etudiant::count();

        // 2. On récupère TOUS les étudiants pour notre nouveau tableau filtrable
        $tousEtudiants = Etudiant::orderBy('nom')->orderBy('prenoms')->get();

        $etudiantsSansSoutenance = Etudiant::whereNotIn('matricule', function($query) {
            $query->select('matricule')->from('soutenances');
        })->orderBy('nom')->get();

        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $notesEntreDates = collect(); // Collection vide par défaut

        if ($dateDebut && $dateFin) {
            // Adaptez le nom de la colonne de date si nécessaire (ex: date_soutenance, créé_a, etc.)
            $notesEntreDates = Soutenance::whereBetween('date_soutenance', [$dateDebut, $dateFin])
                ->orderBy('date_soutenance', 'desc')
                ->get();
        }


        // 3. On envoie tout à la vue welcome
        return view('welcome', compact(
            'effectifsParNiveau', 
            'totalEtudiants', 
            'tousEtudiants', 
            'etudiantsSansSoutenance', 
            'notesEntreDates', 
            'dateDebut', 
            'dateFin'
        ));
    }
}