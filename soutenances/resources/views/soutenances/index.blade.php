<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Soutenances</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
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
                <a href="{{ route('soutenances.index') }}" class="reset-link">Réinitialiser</a>
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
                    <td><span class="note-tag">{{ $soutenance->note }}/20</span></td>
                    <td>{{ $soutenance->president }}</td>
                    <td>{{ $soutenance->examinateur }}</td>
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('soutenances.edit', $soutenance->id) }}" class="action-edit">Modifier</a>
                            
                            <form action="{{ route('soutenances.destroy', $soutenance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette soutenance ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: var(--space-lg) 0;">Aucune soutenance trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>