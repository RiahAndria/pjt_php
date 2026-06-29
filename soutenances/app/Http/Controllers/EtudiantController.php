<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use Illuminate\Http\Request;

class EtudiantController extends Controller
{
    // 1. Afficher la liste et gérer la recherche
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $etudiants = Etudiant::where('matricule', 'LIKE', "%{$search}%")
                ->orWhere('nom', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $etudiants = Etudiant::all();
        }

        // If AJAX request, return JSON data to update the table dynamically
        if ($request->ajax()) {
            $data = $etudiants->map(function ($e) {
                return [
                    'matricule' => $e->matricule,
                    'nom' => $e->nom,
                    'prenoms' => $e->prenoms,
                    'niveau' => $e->niveau,
                    'parcours' => $e->parcours,
                    'adr_email' => $e->adr_email,
                ];
            })->values();

            return response()->json(['data' => $data]);
        }

        return view('etudiants.index', compact('etudiants', 'search'));
    }

    // 2. Enregistrer un étudiant
    public function store(Request $request)
    {
        $nameRegex = '/^[\p{L}][\p{L}\s\'\-\.]*$/u';

        $request->validate([
            'matricule' => 'required|unique:etudiants,matricule',
            'nom' => ['required','max:100', "regex:$nameRegex"],
            'prenoms' => ['required','max:150', "regex:$nameRegex"],
            'niveau' => 'required',
            'parcours' => 'required',
            'adr_email' => 'required|email|unique:etudiants,adr_email',
        ]);

        $data = $request->all();
        // Normalisation : nom en MAJUSCULES, prénoms en Title Case, email en minuscule
        $data['nom'] = mb_strtoupper(trim($data['nom']), 'UTF-8');
        $data['prenoms'] = mb_convert_case(trim($data['prenoms']), MB_CASE_TITLE, 'UTF-8');
        $data['adr_email'] = mb_strtolower(trim($data['adr_email']), 'UTF-8');

        Etudiant::create($data);

        return redirect()->back()->with('success', 'Étudiant ajouté avec succès !');
    }

    // 3. Afficher le formulaire de modification (L'élément manquant !)
    public function edit($matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        return view('etudiants.edit', compact('etudiant'));
    }

    // 4. Enregistrer les modifications
    public function update(Request $request, $matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        $nameRegex = '/^[\p{L}][\p{L}\s\'\-\.]*$/u';

        $request->validate([
            'nom' => ['required','max:100', "regex:$nameRegex"],
            'prenoms' => ['required','max:150', "regex:$nameRegex"],
            'niveau' => 'required',
            'parcours' => 'required',
            'adr_email' => ['required','email', 'unique:etudiants,adr_email,' . $matricule . ',matricule'],
        ]);

        $data = $request->all();
        $data['nom'] = mb_strtoupper(trim($data['nom']), 'UTF-8');
        $data['prenoms'] = mb_convert_case(trim($data['prenoms']), MB_CASE_TITLE, 'UTF-8');
        $data['adr_email'] = mb_strtolower(trim($data['adr_email']), 'UTF-8');

        $etudiant->update($data);

        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès !');
    }

    // 5. Supprimer l'étudiant (L'autre élément manquant !)
    public function destroy($matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        $etudiant->delete();

        return redirect()->back()->with('success', 'Étudiant supprimé avec succès !');
    }
}