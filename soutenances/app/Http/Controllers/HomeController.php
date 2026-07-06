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
        // Les statistiques globales
        $effectifsParNiveau = Etudiant::select('niveau', DB::raw('count(*) as effectif'))
            ->groupBy('niveau')
            ->orderBy('niveau')
            ->get();

        $totalEtudiants = Etudiant::count();

        // On récupère les étudiants pour le tableau où on les filtre et les affiche
        $tousEtudiants = Etudiant::orderBy('nom')->orderBy('prenoms')->get();

        $etudiantsSansSoutenance = Etudiant::whereNotIn('matricule', function($query) {
            $query->select('matricule')->from('soutenances');
        })->orderBy('nom')->get();

        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $notesEntreDates = collect(); // vide par défaut

        if ($dateDebut && $dateFin) {
            $notesEntreDates = Soutenance::whereBetween('date_soutenance', [$dateDebut, $dateFin])
                ->orderBy('date_soutenance', 'desc')
                ->get();
        }


        // On envoie tout à la page Welcome.blade.php pour affichage
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