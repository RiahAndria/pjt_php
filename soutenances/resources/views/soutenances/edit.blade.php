<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Soutenance</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier la Soutenance ID : {{ $soutenance->id }}</h1>
    @if ($errors->any())
        <div style="background:#ffe6e6;border:1px solid #ffcccc;padding:10px;margin-bottom:15px;border-radius:4px;">
            <strong>Des erreurs sont survenues :</strong>
            <ul style="margin:8px 0 0 18px;padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('soutenances.update', $soutenance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Étudiant</label>
            <select name="matricule" required>
                @foreach($etudiants as $etudiant)
                    <option value="{{ $etudiant->matricule }}" {{ $soutenance->matricule == $etudiant->matricule ? 'selected' : '' }}>
                        {{ $etudiant->matricule }} - {{ $etudiant->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Organisme</label>
            <select name="idorg" required>
                @foreach($organismes as $organisme)
                    <option value="{{ $organisme->idorg }}" {{ $soutenance->idorg == $organisme->idorg ? 'selected' : '' }}>
                        {{ $organisme->design }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Année Universitaire</label>
            <input type="text" name="annee_univ" value="{{ $soutenance->annee_univ }}" required>
        </div>

        <div class="form-group">
            <label>Date de soutenance</label>
            <input type="date" name="date_soutenance" value="{{ old('date_soutenance', $soutenance->date_soutenance ? \Carbon\Carbon::parse($soutenance->date_soutenance)->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
            <label>Note</label>
            <input type="number" name="note" value="{{ $soutenance->note }}" min="0" max="20" required>
        </div>

        <div class="form-group">
            <label>Président du Jury</label>
            <select name="president" required>
                @foreach($professeurs as $prof)
                    <option value="{{ $prof->idprof }}" {{ $soutenance->president == $prof->idprof ? 'selected' : '' }}>
                        {{ $prof->civilite }} {{ $prof->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Examinateur</label>
            <select name="examinateur" required>
                @foreach($professeurs as $prof)
                    <option value="{{ $prof->idprof }}" {{ $soutenance->examinateur == $prof->idprof ? 'selected' : '' }}>
                        {{ $prof->civilite }} {{ $prof->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Rapporteur Int.</label>
            <select name="rapporteur_int" required>
                @foreach($professeurs as $prof)
                    <option value="{{ $prof->idprof }}" {{ $soutenance->rapporteur_int == $prof->idprof ? 'selected' : '' }}>
                        {{ $prof->civilite }} {{ $prof->nom }} {{ isset($prof->prenoms) ? $prof->prenoms : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Rapporteur Ext.</label>
            <select name="rapporteur_ext" required>
                @foreach($professeurs as $prof)
                    <option value="{{ $prof->idprof }}" {{ $soutenance->rapporteur_ext == $prof->idprof ? 'selected' : '' }}>
                        {{ $prof->civilite }} {{ $prof->nom }} {{ isset($prof->prenoms) ? $prof->prenoms : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('soutenances.index') }}" class="btn-back">Retour</a>
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>

</body>
</html>