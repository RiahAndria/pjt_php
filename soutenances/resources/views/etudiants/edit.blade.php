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
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input.is-invalid, select.is-invalid { border-color: #dc3545; background-color: #fff5f5; }
        button { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #2779bd; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; display: inline-block; }
        .btn-back:hover { background: #5a6268; }
        .error-message { color: #dc3545; font-size: 0.875rem; margin-top: 5px; }
        .field-error-message {
            display: none;
            position: absolute;
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
        .form-group { position: relative; }
        .success-message { background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
    </style>
    <script src="{{ asset('js/validation-etudiant.js') }}" defer></script>
</head>
<body>

<div class="container">
    <h1>Modifier l'Étudiant : {{ $etudiant->matricule }}</h1>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
            <strong>Erreurs trouvées :</strong>
            <ul style="margin: 5px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('etudiants.update', $etudiant->matricule) }}" method="POST" class="student-validation">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nom">Nom <span style="color: #dc3545;">*</span></label>
            <input 
                type="text" 
                id="nom"
                name="nom" 
                value="{{ old('nom', $etudiant->nom) }}"
                maxlength="100"
                required
                class="@error('nom') is-invalid @enderror"
            >
            <span class="field-error-message" data-error-for="nom"></span>
            @error('nom')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="prenoms">Prénoms <span style="color: #dc3545;">*</span></label>
            <input 
                type="text" 
                id="prenoms"
                name="prenoms" 
                value="{{ old('prenoms', $etudiant->prenoms) }}"
                maxlength="100"
                required
                class="@error('prenoms') is-invalid @enderror"
            >
            <span class="field-error-message" data-error-for="prenoms"></span>
            @error('prenoms')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="niveau">Niveau <span style="color: #dc3545;">*</span></label>
            <select 
                id="niveau"
                name="niveau" 
                required
                class="@error('niveau') is-invalid @enderror"
            >
                <option value="">-- Sélectionner un niveau --</option>
                <option value="L1" {{ old('niveau', $etudiant->niveau) == 'L1' ? 'selected' : '' }}>L1</option>
                <option value="L2" {{ old('niveau', $etudiant->niveau) == 'L2' ? 'selected' : '' }}>L2</option>
                <option value="L3" {{ old('niveau', $etudiant->niveau) == 'L3' ? 'selected' : '' }}>L3</option>
                <option value="M1" {{ old('niveau', $etudiant->niveau) == 'M1' ? 'selected' : '' }}>M1</option>
                <option value="M2" {{ old('niveau', $etudiant->niveau) == 'M2' ? 'selected' : '' }}>M2</option>
            </select>
            <span class="field-error-message" data-error-for="niveau"></span>
            @error('niveau')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="parcours">Parcours <span style="color: #dc3545;">*</span></label>
            <select 
                id="parcours"
                name="parcours" 
                required
                class="@error('parcours') is-invalid @enderror"
            >
                <option value="">-- Sélectionner un parcours --</option>
                <option value="GB" {{ old('parcours', $etudiant->parcours) == 'GB' ? 'selected' : '' }}>GB</option>
                <option value="SR" {{ old('parcours', $etudiant->parcours) == 'SR' ? 'selected' : '' }}>SR</option>
                <option value="IG" {{ old('parcours', $etudiant->parcours) == 'IG' ? 'selected' : '' }}>IG</option>
            </select>
            <span class="field-error-message" data-error-for="parcours"></span>
            @error('parcours')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="adr_email">Adresse Email <span style="color: #dc3545;">*</span></label>
            <input 
                type="email" 
                id="adr_email"
                name="adr_email" 
                value="{{ old('adr_email', $etudiant->adr_email) }}"
                maxlength="150"
                required
                class="@error('adr_email') is-invalid @enderror"
            >
            <span class="field-error-message" data-error-for="adr_email"></span>
            @error('adr_email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('etudiants.index') }}" class="btn-back">Retour</a>
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>

</body>
</html>