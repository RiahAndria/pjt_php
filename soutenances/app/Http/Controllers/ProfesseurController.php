<?php

namespace App\Http\Controllers;

use App\Models\Professeur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfesseurController extends Controller
{
    private static array $grades = [
        'Professeur titulaire',
        'Maître de Conférences',
        "Assistant d'Enseignement Supérieur et de Recherche",
        'Docteur HDR',
        'Docteur en Informatique',
        'Doctorant en informatique',
    ];

    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $professeurs = Professeur::where('idprof', 'LIKE', "%{$search}%")
                ->orWhere('nom', 'LIKE', "%{$search}%")
                ->orWhere('prenoms', 'LIKE', "%{$search}%")
                ->orderBy('idprof')
                ->get();
        } else {
            $professeurs = Professeur::orderBy('idprof')->get();
        }

        if ($request->ajax()) {
            $data = $professeurs->map(function ($p) {
                return [
                    'idprof' => $p->idprof,
                    'nom' => $p->nom,
                    'prenoms' => $p->prenoms,
                    'civilite' => $p->civilite,
                    'grade' => $p->grade,
                ];
            })->values();

            return response()->json(['data' => $data]);
        }

        return view('professeurs.index', compact('professeurs', 'search'));
    }

    public function store(Request $request)
    {
        $nameRegex = '/^[\p{L}][\p{L}\s\'\-\.]*$/u';

        $request->validate([
            'idprof' => 'required|unique:professeurs,idprof',
            'nom' => ['required','max:100', "regex:$nameRegex"],
            'prenoms' => ['required','max:150', "regex:$nameRegex"],
            'civilite' => 'required',
            'grade' => ['required', Rule::in(self::$grades)],
        ]);

        $data = $request->all();
        $data['nom'] = mb_strtoupper(trim($data['nom']), 'UTF-8');
        $data['prenoms'] = mb_convert_case(trim($data['prenoms']), MB_CASE_TITLE, 'UTF-8');

        Professeur::create($data);

        return redirect()->back()->with('success', 'Professeur ajouté avec succès !');
    }

    public function edit(string $idprof)
    {
        $professeur = Professeur::findOrFail($idprof);
        return view('professeurs.edit', compact('professeur'));
    }

    public function update(Request $request, string $idprof)
    {
        $professeur = Professeur::findOrFail($idprof);
        $nameRegex = '/^[\p{L}][\p{L}\s\'\-\.]*$/u';

        $request->validate([
            'nom' => ['required','max:100', "regex:$nameRegex"],
            'prenoms' => ['required','max:150', "regex:$nameRegex"],
            'civilite' => 'required',
            'grade' => ['required', Rule::in(self::$grades)],
        ]);

        $data = $request->all();
        $data['nom'] = mb_strtoupper(trim($data['nom']), 'UTF-8');
        $data['prenoms'] = mb_convert_case(trim($data['prenoms']), MB_CASE_TITLE, 'UTF-8');

        $professeur->update($data);

        return redirect()->route('professeurs.index')->with('success', 'Professeur mis à jour avec succès !');
    }

    public function destroy(string $idprof)
    {
        $professeur = Professeur::findOrFail($idprof);
        $professeur->delete();

        return redirect()->back()->with('success', 'Professeur supprimé avec succès !');
    }
}