<?php

namespace App\Http\Controllers;

use App\Models\Soutenance;
use App\Models\Etudiant;
use App\Models\Organisme;
use App\Models\Professeur;
use Illuminate\Http\Request;

class SoutenanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $soutenances = Soutenance::when($search, function ($query, $search) {
            return $query->where('matricule', 'like', "%{$search}%")
                         ->orWhere('annee_univ', 'like', "%{$search}%");
        })->get();

        $etudiants = Etudiant::all();
        $organismes = Organisme::all();
        $professeurs = Professeur::all();

        return view('soutenances.index', compact(
            'soutenances',
            'search',
            'etudiants',
            'organismes',
            'professeurs'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|exists:etudiants,matricule',
            'idorg' => 'required|exists:organismes,idorg',
            'annee_univ' => 'required|string',
            'note' => 'required|integer|min:0|max:20',
            'president' => 'required|exists:professeurs,idprof',
            'examinateur' => 'required|exists:professeurs,idprof',
            'rapporteur_int' => 'required|exists:professeurs,idprof',
            'rapporteur_ext' => 'required|exists:professeurs,idprof',
        ]);

        Soutenance::create($validated);

        return redirect()->route('soutenances.index')->with('success', 'Soutenance ajoutée avec succès !');
    }

    public function edit($id)
    {
        $soutenance = Soutenance::findOrFail($id);
        $etudiants = Etudiant::all();
        $organismes = Organisme::all();
        $professeurs = Professeur::all();

        return view('soutenances.edit', compact('soutenance', 'etudiants', 'organismes', 'professeurs'));
    }

    public function update(Request $request, $id)
    {
        $soutenance = Soutenance::findOrFail($id);

        $validated = $request->validate([
            'matricule' => 'required|exists:etudiants,matricule',
            'idorg' => 'required|exists:organismes,idorg',
            'annee_univ' => 'required|string',
            'note' => 'required|integer|min:0|max:20',
            'president' => 'required|exists:professeurs,idprof',
            'examinateur' => 'required|exists:professeurs,idprof',
            'rapporteur_int' => 'required|exists:professeurs,idprof',
            'rapporteur_ext' => 'required|exists:professeurs,idprof',
        ]);

        $soutenance->update($validated);

        return redirect()->route('soutenances.index')->with('success', 'Soutenance mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $soutenance = Soutenance::findOrFail($id);
        $soutenance->delete();

        return redirect()->route('soutenances.index')->with('success', 'Soutenance supprimée !');
    }
}