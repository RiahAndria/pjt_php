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
        // Validation et normalisation
        $designRegex = '/^[\p{L}0-9][\p{L}0-9\s\'\-\.,&()]*$/u';
        $lieuRegex = '/^[\p{L}][\p{L}\s\-]*$/u';

        $request->validate([
            'design' => ['required','max:150', "regex:$designRegex"],
            'lieu' => ['required','max:100', "regex:$lieuRegex"],
        ]);

        $data = [
            'design' => mb_convert_case(trim($request->design), MB_CASE_TITLE, 'UTF-8'),
            'lieu' => mb_convert_case(trim($request->lieu), MB_CASE_TITLE, 'UTF-8'),
        ];

        Organisme::create($data);

        return redirect()->back()->with('success', 'Organisme ajouté avec succès !');
    }

    // 3. Afficher le formulaire d'édition pour un organisme
    public function edit($idorg)
    {
        $organisme = Organisme::findOrFail($idorg);
        return view('organismes.edit', compact('organisme'));
    }

    // 4. Mettre à jour un organisme
    public function update(Request $request, $idorg)
    {
        $organisme = Organisme::findOrFail($idorg);
        $designRegex = '/^[\p{L}0-9][\p{L}0-9\s\'\-\.,&()]*$/u';
        $lieuRegex = '/^[\p{L}][\p{L}\s\-]*$/u';

        $request->validate([
            'design' => ['required','max:150', "regex:$designRegex"],
            'lieu' => ['required','max:100', "regex:$lieuRegex"],
        ]);

        $data = [
            'design' => mb_convert_case(trim($request->design), MB_CASE_TITLE, 'UTF-8'),
            'lieu' => mb_convert_case(trim($request->lieu), MB_CASE_TITLE, 'UTF-8'),
        ];

        $organisme->update($data);

        return redirect()->route('organismes.index')->with('success', 'Organisme mis à jour avec succès !');
    }

    // 5. Supprimer un organisme
    public function destroy($idorg)
    {
        $organisme = Organisme::findOrFail($idorg);
        $organisme->delete();

        return redirect()->back()->with('success', 'Organisme supprimé avec succès !');
    }
}