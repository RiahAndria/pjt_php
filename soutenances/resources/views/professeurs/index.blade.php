<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Professeurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style>
        /* On garde la structure globale identique pour l'alignement */
        body { margin: 0; padding: 0; background: var(--color-bg); display: flex; flex-direction: column; min-height: 100vh; }
        .app-header { display: flex; align-items: center; padding: 0 var(--space-lg); height: 70px; background: #1e293b; border-bottom: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); position: fixed; top: 0; left: 0; right: 0; z-index: 110; }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; color: var(--color-white); letter-spacing: 0.5px; }
        .app-body { display: flex; margin-top: 70px; flex: 1; }
        .sidebar { width: 260px; background: #0f172a; border-right: 1px solid rgba(255, 255, 255, 0.05); padding: var(--space-md) var(--space-sm); display: flex; flex-direction: column; position: fixed; top: 70px; bottom: 0; left: 0; z-index: 100; }
        .sidebar__menu { display: flex; flex-direction: column; gap: var(--space-sm); list-style: none; padding: 0; margin: 0; }
        .sidebar__link { display: flex; align-items: center; gap: var(--space-md); padding: 0.8rem var(--space-md); color: rgba(255, 255, 255, 0.7); text-decoration: none; border-radius: var(--border-radius-sm); transition: all var(--transition); font-size: 0.95rem; font-weight: 500; }
        .sidebar__link:hover { background: rgba(255, 255, 255, 0.05); color: var(--color-white); }
        .sidebar__link--active { background: var(--color-primary); color: var(--color-white); font-weight: 600; }
        .sidebar__link-icon { width: 20px; text-align: center; font-size: 1.1rem; }

        /* C'est cette marge à gauche qui évite que le tableau passe sous la sidebar */
        .main-content { flex: 1; margin-left: 260px; padding: var(--space-lg); }

        /* --- Ajouts pour la validation en direct --- */
        .form-group { position: relative; }
        input.is-invalid, select.is-invalid { border-color: #dc3545; }
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
    </style>
    <script src="{{ asset('js/validation-professeur.js') }}" defer></script>
</head>
<body>

<x-header />
<div class="app-body">
<x-sidebar />

<main class="main-content">

<div class="container">
    <h1>Gestion des Professeurs</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher un professeur</h3>
        <form action="{{ route('professeurs.index') }}" method="GET" class="flex-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Par identifiant, nom ou prénom...">
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if($search)
                <a href="{{ route('professeurs.index') }}" class="reset-link">Réinitialiser</a>
            @endif
        </form>
    </div>

    <h3>Ajouter un nouveau professeur</h3>
    <form action="{{ route('professeurs.store') }}" method="POST" class="flex-form prof-validation">
        @csrf

        <div class="form-group">
            <input type="text" id="idprof" name="idprof" placeholder="ID Prof (ex: P001)" value="{{ old('idprof') }}" maxlength="50" class="@error('idprof') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="idprof"></span>
        </div>

        <div class="form-group">
            <input type="text" id="nom" name="nom" placeholder="Nom" value="{{ old('nom') }}" maxlength="100" class="@error('nom') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="nom"></span>
        </div>

        <div class="form-group">
            <input type="text" id="prenoms" name="prenoms" placeholder="Prénoms" value="{{ old('prenoms') }}" maxlength="150" class="@error('prenoms') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="prenoms"></span>
        </div>

        <div class="form-group">
            <select id="civilite" name="civilite" class="@error('civilite') is-invalid @enderror" required>
                <option value="">-- Choisir Civilité --</option>
                <option value="Mr" {{ old('civilite') == 'Mr' ? 'selected' : '' }}>Mr</option>
                <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Mme</option>
                <option value="Mlle" {{ old('civilite') == 'Mlle' ? 'selected' : '' }}>Mlle</option>
            </select>
            <span class="field-error-message" data-error-for="civilite"></span>
        </div>

        <div class="form-group">
            <select id="grade" name="grade" class="@error('grade') is-invalid @enderror" required>
                <option value="">-- Choisir Grade --</option>
                <option value="Professeur titulaire" {{ old('grade') == 'Professeur titulaire' ? 'selected' : '' }}>Professeur titulaire</option>
                <option value="Maître de Conférences" {{ old('grade') == 'Maître de Conférences' ? 'selected' : '' }}>Maître de Conférences</option>
                <option value="Assistant d'Enseignement Supérieur et de Recherche" {{ old('grade') == "Assistant d'Enseignement Supérieur et de Recherche" ? 'selected' : '' }}>Assistant d'Enseignement Supérieur et de Recherche</option>
                <option value="Docteur HDR" {{ old('grade') == 'Docteur HDR' ? 'selected' : '' }}>Docteur HDR</option>
                <option value="Docteur en Informatique" {{ old('grade') == 'Docteur en Informatique' ? 'selected' : '' }}>Docteur en Informatique</option>
                <option value="Doctorant en informatique" {{ old('grade') == 'Doctorant en informatique' ? 'selected' : '' }}>Doctorant en informatique</option>
            </select>
            <span class="field-error-message" data-error-for="grade"></span>
        </div>

        <button type="submit">Ajouter le professeur</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénoms</th>
                <th>Civilité</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($professeurs as $p)
                <tr>
                    <td>{{ $p->idprof }}</td>
                    <td>{{ $p->nom }}</td>
                    <td>{{ $p->prenoms }}</td>
                    <td>{{ $p->civilite }}</td>
                    <td>{{ $p->grade }}</td>
                    <td>
                        <a href="{{ route('professeurs.edit', $p->idprof) }}" style="color: #3490dc; margin-right: 10px; text-decoration: none;"><i class="fa-regular fa-pen-to-square"></i></a>
                        <form action="{{ route('professeurs.destroy', $p->idprof) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce professeur ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;"><i class="fa-regular fa-trash-can"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color: var(--color-text-muted); padding: var(--space-lg) 0;">Aucun professeur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</main>
</div>

</body>
</html>