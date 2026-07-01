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
    <form action="{{ route('etudiants.store') }}" method="POST" class="flex-form">
        @csrf
        <input type="text" name="matricule" placeholder="Matricule (ex: ETU001)" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenoms" placeholder="Prénoms" required>
        
        <select name="niveau" required>
            <option value=""> Choisir Niveau </option>
            <option value="L1">L1</option>
            <option value="L2">L2</option>
            <option value="L3">L3</option>
            <option value="M1">M1</option>
            <option value="M2">M2</option>
        </select>

        <select name="parcours" required>
            <option value=""> Choisir Parcours </option>
            <option value="GB">GB</option>
            <option value="SR">SR</option>
            <option value="IG">IG</option>
        </select>

        <input type="email" name="adr_email" placeholder="Adresse Email" required>
        <button type="submit">Ajouter l'étudiant</button>
    </form>

    <section class="stats-section">
        <h3>Effectif des étudiants par niveau</h3>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Niveau</th>
                    <th>Effectif</th>
                </tr>
            </thead>
            <tbody>
                @foreach($effectifsParNiveau as $stat)
                    <tr>
                        <td>{{ $stat->niveau }}</td>
                        <td>{{ $stat->effectif }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $totalEtudiants }}</strong></td>
                </tr>
            </tbody>
        </table>
    </section>

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