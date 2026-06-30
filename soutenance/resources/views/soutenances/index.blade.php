<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Soutenances</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
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
    <h1>Gestion des Soutenances</h1>
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher une soutenance</h3>
        <form action="{{ route('soutenances.index') }}" method="GET" class="flex-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Matricule ou Année...">
            <button type="submit" class="btn-search">Rechercher</button>
            @if($search)
                <a href="{{ route('soutenances.index') }}" style="align-self: center; color: #e74c3c;">Réinitialiser</a>
            @endif
        </form>
    </div>

    <h3>Ajouter une nouvelle soutenance</h3>
    <form action="{{ route('soutenances.store') }}" method="POST" class="flex-form">
        @csrf
        
        <select name="matricule" required>
            <option value="">-- Étudiant --</option>
            @foreach($etudiants as $etudiant)
                <option value="{{ $etudiant->matricule }}">{{ $etudiant->matricule }} - {{ $etudiant->nom }}</option>
            @endforeach
        </select>

        <select name="idorg" required>
            <option value="">-- Organisme --</option>
            @foreach($organismes as $organisme)
                <option value="{{ $organisme->idorg }}">{{ $organisme->design }} ({{ $organisme->lieu }})</option>
            @endforeach
        </select>

        <input type="text" name="annee_univ" placeholder="Année univ (ex: 2022-2023)" required>
        <input type="number" name="note" placeholder="Note" min="0" max="20" required>
        
        <select name="president" required>
            <option value="">-- Président du Jury --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <select name="examinateur" required>
            <option value="">-- Examinateur --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <select name="rapporteur_int" required>
            <option value="">-- Rapporteur Int. --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <select name="rapporteur_ext" required>
            <option value="">-- Rapporteur Ext. --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <button type="submit">Ajouter la soutenance</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Organisme</th>
                <th>Année Univ</th>
                <th>Note</th>
                <th>Président (ID)</th>
                <th>Examinateur (ID)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($soutenances as $soutenance)
                <tr>
                    <td>{{ $soutenance->matricule }}</td>
                    <td>{{ $soutenance->idorg }}</td>
                    <td>{{ $soutenance->annee_univ }}</td>
                    <td><strong>{{ $soutenance->note }}/20</strong></td>
                    <td>{{ $soutenance->president }}</td>
                    <td>{{ $soutenance->examinateur }}</td>
                    <td>
                        <a href="{{ route('soutenances.edit', $soutenance->id) }}" style="color: #3490dc; margin-right: 10px; text-decoration: none;">Modifier</a>
                        
                        <form action="{{ route('soutenances.destroy', $soutenance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette soutenance ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Aucune soutenance trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>