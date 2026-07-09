<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Soutenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Les statistiques globales 
        $effectifsParNiveau = Etudiant::select('niveau', DB::raw('count(*) as effectif'))
            ->groupBy('niveau')
            ->orderBy('niveau')
            ->get();

        $totalEtudiants = Etudiant::count();

        // 2. On récupère TOUS les étudiants pour notre tableau filtrable
        $tousEtudiants = Etudiant::orderBy('matricule')->orderBy('nom')->orderBy('prenoms')->get();

        // 3. Liste des étudiants qui n'ont pas encore effectué de soutenance
        //    (déplacé depuis la page Soutenances)
        $etudiantsSansSoutenance = Etudiant::whereNotIn('matricule', Soutenance::select('matricule'))->orderBy('nom')->orderBy('matricule')->get();

        // 4. Liste des notes des étudiants entre deux dates, filtre optionnel via GET
        //    (déplacé depuis la page Soutenances)
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $notesEntreDates = collect();

        if ($dateDebut && $dateFin) {
            try {
                $debut = Carbon::createFromFormat('Y-m-d', $dateDebut)->startOfDay();
                $fin = Carbon::createFromFormat('Y-m-d', $dateFin)->endOfDay();

                // Filtrer par date_soutenance (colonne dédiée) et trier chronologiquement
                $notesEntreDates = Soutenance::whereBetween('date_soutenance', [$debut->toDateString(), $fin->toDateString()])
                    ->orderBy('date_soutenance', 'asc')
                    ->get();
            } catch (\Exception $e) {
                // Si les dates sont invalides, on ignore le filtre
                $notesEntreDates = collect();
            }
        }

        // 5. On envoie tout à la vue welcome
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