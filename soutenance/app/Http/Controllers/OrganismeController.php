<?php

namespace App\Http\Controllers;

use App\Models\Organisme;
use Illuminate\Http\Request;

class OrganismeController extends Controller
{
    // 1. Afficher la liste des organismes
    public function index()
    {
        $organismes = Organisme::all(); // Récupère tous les organismes de Postgres
        return view('organismes.index', compact('organismes'));
    }

    // 2. Enregistrer un organisme dans la base de données
    public function store(Request $request)
    {
        // Validation simple des champs
        $request->validate([
            'design' => 'required',
            'lieu' => 'required',
        ]);

        // Insertion dans PostgreSQL
        Organisme::create([
            'design' => $request->design,
            'lieu' => $request->lieu,
        ]);

        return redirect()->back()->with('success', 'Organisme ajouté avec succès !');
    }
}