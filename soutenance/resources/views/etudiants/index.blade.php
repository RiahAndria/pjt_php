<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .flex-form { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        input, select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; min-width: 150px; }
        button { padding: 8px 15px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-search { background: #34495e; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background: #f8fafc; }
        .alert { padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
        .search-box { background: #eef2f7; padding: 15px; border-radius: 6px; margin-bottom: 25px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestion des Étudiants (PostgreSQL)</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher un étudiant</h3>
        <form action="{{ route('etudiants.index') }}" method="GET" class="flex-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Par matricule ou nom...">
            <button type="submit" class="btn-search">Rechercher</button>
            @if($search)
                <a href="{{ route('etudiants.index') }}" style="align-self: center; color: #e74c3c;">Réinitialiser</a>
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
            <option value="">-- Choisir Niveau --</option>
            <option value="L1">L1</option>
            <option value="L2">L2</option>
            <option value="L3">L3</option>
            <option value="M1">M1</option>
            <option value="M2">M2</option>
        </select>

        <select name="parcours" required>
            <option value="">-- Choisir Parcours --</option>
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
                <th>Actions</th> </tr>
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
                        <a href="{{ route('etudiants.edit', $etudiant->matricule) }}" style="color: #3490dc; margin-right: 10px; text-decoration: none;">Modifier</a>
                        
                        <form action="{{ route('etudiants.destroy', $etudiant->matricule) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet étudiant ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Aucun étudiant trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>