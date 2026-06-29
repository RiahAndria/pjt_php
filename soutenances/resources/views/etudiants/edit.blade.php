<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'Étudiant</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-radius; }
        button { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier l'Étudiant : {{ $etudiant->matricule }}</h1>

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <form action="{{ route('etudiants.update', $etudiant->matricule) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" value="{{ $etudiant->nom }}" required>
        </div>

        <div class="form-group">
            <label>Prénoms</label>
            <input type="text" name="prenoms" value="{{ $etudiant->prenoms }}" required>
        </div>

        <div class="form-group">
            <label>Niveau</label>
            <select name="niveau" required>
                <option value="L1" {{ $etudiant->niveau == 'L1' ? 'selected' : '' }}>L1</option>
                <option value="L2" {{ $etudiant->niveau == 'L2' ? 'selected' : '' }}>L2</option>
                <option value="L3" {{ $etudiant->niveau == 'L3' ? 'selected' : '' }}>L3</option>
                <option value="M1" {{ $etudiant->niveau == 'M1' ? 'selected' : '' }}>M1</option>
                <option value="M2" {{ $etudiant->niveau == 'M2' ? 'selected' : '' }}>M2</option>
            </select>
        </div>

        <div class="form-group">
            <label>Parcours</label>
            <select name="parcours" required>
                <option value="GB" {{ $etudiant->parcours == 'GB' ? 'selected' : '' }}>GB</option>
                <option value="SR" {{ $etudiant->parcours == 'SR' ? 'selected' : '' }}>SR</option>
                <option value="IG" {{ $etudiant->parcours == 'IG' ? 'selected' : '' }}>IG</option>
            </select>
        </div>

        <div class="form-group">
            <label>Adresse Email</label>
            <input type="email" name="adr_email" value="{{ $etudiant->adr_email }}" required>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('etudiants.index') }}" class="btn-back">Retour</a>
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>

</body>
</html>