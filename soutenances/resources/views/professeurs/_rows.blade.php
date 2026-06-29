@forelse($professeurs as $professeur)
    <tr>
        <td>{{ $professeur->idprof }}</td>
        <td>{{ $professeur->nom }}</td>
        <td>{{ $professeur->prenoms }}</td>
        <td>{{ $professeur->civilite }}</td>
        <td>{{ $professeur->grade }}</td>
        <td>
            <a href="{{ route('professeurs.edit', $professeur->idprof) }}" style="color: #3490dc; margin-right: 10px; text-decoration: none;">Modifier</a>
            <form action="{{ route('professeurs.destroy', $professeur->idprof) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce professeur ?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;">Supprimer</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" style="text-align: center;">Aucun professeur trouvé.</td>
    </tr>
@endforelse
