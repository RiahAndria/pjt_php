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
