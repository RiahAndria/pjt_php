<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

<div class="container">
    <h1>Gestion des Étudiants</h1>
    
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher un étudiant</h3>
        <form action="{{ route('etudiants.index') }}" method="GET" class="flex-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Par matricule ou nom...">
            <button type="submit" class="btn-search">Rechercher</button>
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
                            <a href="{{ route('etudiants.edit', $etudiant->matricule) }}" class="action-edit">Modifier</a>
                            
                            <form action="{{ route('etudiants.destroy', $etudiant->matricule) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet étudiant ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: var(--space-lg) 0;">Aucun étudiant trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>