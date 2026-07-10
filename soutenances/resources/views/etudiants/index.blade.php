<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
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

        .form-group { position: relative; margin-bottom: 1.5rem; }
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
    <script>
    // Overture et fermeture du modal
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'flex';
       }

       function closeModal(id) {
           document.getElementById('modal-' + id).style.display = 'none';
        }
    </script>
    <script src="{{ asset('js/validation-etudiant.js') }}" defer></script>
</head>
<body>

<x-header />
<div class="app-body">
<x-sidebar />

<main class="main-content">

<div class="container">
    <h1>Gestion des Étudiants</h1>
    
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher un étudiant</h3>
        <form action="{{ route('etudiants.index') }}" method="GET" class="flex-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Par matricule ou nom...">
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if($search)
                <a href="{{ route('etudiants.index') }}" class="reset-link">Réinitialiser</a>
            @endif
        </form>
    </div>

    <h3>Ajouter un nouvel étudiant</h3>
    
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

    <form action="{{ route('etudiants.store') }}" method="POST" class="flex-form student-validation">
        @csrf
        <input 
            type="text" 
            id="matricule"
            name="matricule" 
            placeholder="Matricule (ex: ETU001)" 
            maxlength="50"
            value="{{ old('matricule') }}"
            class="@error('matricule') is-invalid @enderror"
            required
        >
        <span class="field-error-message" data-error-for="matricule"></span>
        @error('matricule')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror

        <input 
            type="text" 
            id="nom"
            name="nom" 
            placeholder="Nom" 
            maxlength="100"
            value="{{ old('nom') }}"
            class="@error('nom') is-invalid @enderror"
            required
        >
        <span class="field-error-message" data-error-for="nom"></span>
        @error('nom')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror

        <input 
            type="text" 
            id="prenoms"
            name="prenoms" 
            placeholder="Prénoms" 
            maxlength="100"
            value="{{ old('prenoms') }}"
            class="@error('prenoms') is-invalid @enderror"
            required
        >
        <span class="field-error-message" data-error-for="prenoms"></span>
        @error('prenoms')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror
        
        <select 
            id="niveau"
            name="niveau" 
            class="@error('niveau') is-invalid @enderror"
            required
        >
            <option value="">Choisir Niveau</option>
            <option value="L1" {{ old('niveau') == 'L1' ? 'selected' : '' }}>L1</option>
            <option value="L2" {{ old('niveau') == 'L2' ? 'selected' : '' }}>L2</option>
            <option value="L3" {{ old('niveau') == 'L3' ? 'selected' : '' }}>L3</option>
            <option value="M1" {{ old('niveau') == 'M1' ? 'selected' : '' }}>M1</option>
            <option value="M2" {{ old('niveau') == 'M2' ? 'selected' : '' }}>M2</option>
        </select>
        <span class="field-error-message" data-error-for="niveau"></span>
        @error('niveau')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror

        <select 
            id="parcours"
            name="parcours" 
            class="@error('parcours') is-invalid @enderror"
            required
        >
            <option value="">Choisir Parcours</option>
            <option value="GB" {{ old('parcours') == 'GB' ? 'selected' : '' }}>GB</option>
            <option value="SR" {{ old('parcours') == 'SR' ? 'selected' : '' }}>SR</option>
            <option value="IG" {{ old('parcours') == 'IG' ? 'selected' : '' }}>IG</option>
        </select>
        <span class="field-error-message" data-error-for="parcours"></span>
        @error('parcours')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror

        <input 
            type="email" 
            id="adr_email"
            name="adr_email" 
            placeholder="Adresse Email" 
            maxlength="150"
            value="{{ old('adr_email') }}"
            class="@error('adr_email') is-invalid @enderror"
            required
        >
        <span class="field-error-message" data-error-for="adr_email"></span>
        @error('adr_email')
            <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
        @enderror

        <button type="submit">Ajouter l'étudiant</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénoms</th>
                <th>Niveau</th>
                <th>Parcours</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
              @forelse($etudiants as $etudiant)
                <tr>
                    <td>{{ $etudiant->matricule }}</td>
                    <td>{{ $etudiant->nom }}</td>
                    <td>{{ $etudiant->prenoms }}</td>
                    <td>{{ $etudiant->niveau }}</td>
                    <td>{{ $etudiant->parcours }}</td>
                    <td>{{ $etudiant->adr_email }}</td>
                    <td>
                        <div class="actions-cell">
                            <button type="button" class="action-edit" onclick="openModal('{{ $etudiant->matricule }}')">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>

                            <form action="{{ route('etudiants.destroy', $etudiant->matricule) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet étudiant ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <x-edit-etudiant-modal :etudiant="$etudiant" :id="$etudiant->matricule" />

            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: var(--space-lg) 0;">Aucun étudiant trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</main>
</div>

</body>
</html>