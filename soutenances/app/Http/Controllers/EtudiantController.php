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
                ->orWhere('prenoms', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $etudiants = Etudiant::all();
        }

        return view('etudiants.index', compact('etudiants', 'search'));
    }

    // 2. Enregistrer un étudiant
    // regex à implémenter mais là j'ai la flemme...
    public function store(Request $request)
    {
        //jsp si ça marchera si on inverse les commentaires mais je pose là sujte comme ça
        //Ah, bah ça marche pas gros...
        
        //$nameRegex = '/^[\p{L}][\p{L}\s\'\-\.]*$/u';
        
        $request->validate([
            'matricule' => 'required|unique:etudiants,matricule',
            'nom' => 'required',
            //'nom' => ['required', 'max:100', "regex = $nameRegex"], 
            'prenoms' => 'required',
            //'prenoms' => ['required', 'max:250', "regex = $nameRegex"],
            'niveau' => 'required',
            //'niveau' => ['required', 'max:10', "regex"],
            'parcours' => 'required',
            'adr_email' => 'required|email|unique:etudiants,adr_email',
        ]);

        Etudiant::create($request->all());

        return redirect()->back()->with('success', 'Étudiant ajouté avec succès !');
    }

    // 3. Afficher le formulaire de modification
    public function edit($matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        return view('etudiants.edit', compact('etudiant'));
    }

    // 4. Enregistrer les modifications
    public function update(Request $request, $matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);

        $request->validate([
            'nom' => 'required',
            'prenoms' => 'required',
            'niveau' => 'required',
            'parcours' => 'required',
            'adr_email' => 'required|email|unique:etudiants,adr_email,' . $matricule . ',matricule',
        ]);

        $etudiant->update($request->all());

        return redirect()->route('etudiants.index')->with('success', 'Étudiant mis à jour avec succès !');
    }

    // 5. Supprimer l'étudiant
    public function destroy($matricule)
    {
        $etudiant = Etudiant::findOrFail($matricule);
        $etudiant->delete();

        return redirect()->back()->with('success', 'Étudiant supprimé avec succès !');
    }
}