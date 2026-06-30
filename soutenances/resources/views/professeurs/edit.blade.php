<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Professeur</title>
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
    <h1>Modifier le Professeur : {{ $professeur->idprof }}</h1>

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <form action="{{ route('professeurs.update', $professeur->idprof) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>ID Professeur</label>
            <input type="text" value="{{ $professeur->idprof }}" disabled>
        </div>

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" value="{{ $professeur->nom }}" required>
        </div>

        <div class="form-group">
            <label>Prénoms</label>
            <input type="text" name="prenoms" value="{{ $professeur->prenoms }}" required>
        </div>

        <div class="form-group">
            <label>Civilité</label>
            <select name="civilite" required>
                <option value="Mr" {{ $professeur->civilite == 'Mr' ? 'selected' : '' }}>Mr</option>
                <option value="Mme" {{ $professeur->civilite == 'Mme' ? 'selected' : '' }}>Mme</option>
                <option value="Mlle" {{ $professeur->civilite == 'Mlle' ? 'selected' : '' }}>Mlle</option>
            </select>
        </div>

        <div class="form-group">
            <label>Grade</label>
            <select name="grade" required>
                <option value="Professeur titulaire" {{ $professeur->grade == 'Professeur titulaire' ? 'selected' : '' }}>Professeur titulaire</option>
                <option value="Maître de Conférences" {{ $professeur->grade == 'Maître de Conférences' ? 'selected' : '' }}>Maître de Conférences</option>
                <option value="Assistant d’Enseignement Supérieur et de Recherche" {{ $professeur->grade == 'Assistant d’Enseignement Supérieur et de Recherche' ? 'selected' : '' }}>Assistant d’Enseignement Supérieur et de Recherche</option>
                <option value="Docteur HDR" {{ $professeur->grade == 'Docteur HDR' ? 'selected' : '' }}>Docteur HDR</option>
                <option value="Docteur en Informatique" {{ $professeur->grade == 'Docteur en Informatique' ? 'selected' : '' }}>Docteur en Informatique</option>
                <option value="Doctorant en informatique" {{ $professeur->grade == 'Doctorant en informatique' ? 'selected' : '' }}>Doctorant en informatique</option>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('professeurs.index') }}" class="btn-back">Retour</a>
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>

</body>
</html>
