<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Http\Requests\StoreEtudiantRequest;
use App\Http\Requests\UpdateEtudiantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{
    // 1. Afficher la liste et gérer la recherche
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $etudiants = Etudiant::where('matricule', 'LIKE', "%{$search}%")
                ->orWhere('nom', 'LIKE', "%{$search}%")
                ->orWhere('prenoms', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $etudiants = Etudiant::all();
        }

        $effectifsParNiveau = Etudiant::select('niveau', DB::raw('count(*) as effectif'))
            ->groupBy('niveau')
            ->orderBy('niveau')
            ->get();

        $totalEtudiants = Etudiant::count();

        return view('etudiants.index', compact('etudiants', 'search', 'effectifsParNiveau', 'totalEtudiants'));
    }

    // 2. Enregistrer un étudiant
    public function store(StoreEtudiantRequest $request)
    {
        Etudiant::create($request->validated());

        return redirect()->back()->with('success', 'Étudiant ajouté avec succès !');
    }

    // 3. Afficher le formulaire de modification
    public function edit(string $matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        return view('etudiants.edit', compact('etudiant'));
    }

    // 4. Enregistrer les modifications
    public function update(UpdateEtudiantRequest $request, string $matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        $etudiant->update($request->validated());

        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès !');
    }

    // 5. Supprimer l'étudiant
    public function destroy(string $matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        $etudiant->delete();

        return redirect()->back()->with('success', 'Étudiant supprimé avec succès !');
    }
}