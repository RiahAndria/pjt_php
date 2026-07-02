<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PdfController extends Controller
{
    public function index()
    {
        return view('generation-pdf');
    }

    public function modele()
    {
        $path = base_path('../Projet 2.pdf');

        if (!file_exists($path)) {
            abort(404, 'Fichier PDF introuvable.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function export()
    {
        $path = base_path('../Projet 2.pdf');

        if (!file_exists($path)) {
            abort(404, 'Fichier PDF introuvable.');
        }

        return response()->download($path, 'modele-projet-2.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
