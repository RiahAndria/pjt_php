<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Professeur</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; position: relative; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input.is-invalid, select.is-invalid { border-color: #dc3545; background-color: #fff5f5; }
        button { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; }
        .field-error-message {
            display: none;
            position: relative;
            left: 0;
            top: calc(100% + 0.35rem);
            z-index: 20;
            padding: 0.45rem 0.65rem;
            background: rgba(220, 53, 69, 0.96);
            color: white;
            border-radius: 0.35rem;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
            pointer-events: none;
        }
        .field-error-message::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 12px;
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent rgba(220, 53, 69, 0.96) transparent;
        }
    </style>
    <script src="{{ asset('js/validation-professeur.js') }}" defer></script>
</head>
<body>

<div class="container">
    <h1>Modifier le Professeur : {{ $professeur->idprof }}</h1>

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <form action="{{ route('professeurs.update', $professeur->idprof) }}" method="POST" class="prof-validation">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>ID Professeur</label>
            <input type="text" value="{{ $professeur->idprof }}" disabled>
        </div>

        <div class="form-group">
            <label>Nom</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $professeur->nom) }}" maxlength="100" class="@error('nom') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="nom"></span>
        </div>

        <div class="form-group">
            <label>Prénoms</label>
            <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms', $professeur->prenoms) }}" maxlength="150" class="@error('prenoms') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="prenoms"></span>
        </div>

        <div class="form-group">
            <label>Civilité</label>
            <select id="civilite" name="civilite" class="@error('civilite') is-invalid @enderror" required>
                <option value="Mr" {{ old('civilite', $professeur->civilite) == 'Mr' ? 'selected' : '' }}>Mr</option>
                <option value="Mme" {{ old('civilite', $professeur->civilite) == 'Mme' ? 'selected' : '' }}>Mme</option>
                <option value="Mlle" {{ old('civilite', $professeur->civilite) == 'Mlle' ? 'selected' : '' }}>Mlle</option>
            </select>
            <span class="field-error-message" data-error-for="civilite"></span>
        </div>

        <div class="form-group">
            <label>Grade</label>
            <select id="grade" name="grade" class="@error('grade') is-invalid @enderror" required>
                <option value="Professeur titulaire" {{ old('grade', $professeur->grade) == 'Professeur titulaire' ? 'selected' : '' }}>Professeur titulaire</option>
                <option value="Maître de Conférences" {{ old('grade', $professeur->grade) == 'Maître de Conférences' ? 'selected' : '' }}>Maître de Conférences</option>
                <option value="Assistant d'Enseignement Supérieur et de Recherche" {{ old('grade', $professeur->grade) == "Assistant d'Enseignement Supérieur et de Recherche" ? 'selected' : '' }}>Assistant d'Enseignement Supérieur et de Recherche</option>
                <option value="Docteur HDR" {{ old('grade', $professeur->grade) == 'Docteur HDR' ? 'selected' : '' }}>Docteur HDR</option>
                <option value="Docteur en Informatique" {{ old('grade', $professeur->grade) == 'Docteur en Informatique' ? 'selected' : '' }}>Docteur en Informatique</option>
                <option value="Doctorant en informatique" {{ old('grade', $professeur->grade) == 'Doctorant en informatique' ? 'selected' : '' }}>Doctorant en informatique</option>
            </select>
            <span class="field-error-message" data-error-for="grade"></span>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('professeurs.index') }}" class="btn-back">Retour</a>
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>

</body>
</html>