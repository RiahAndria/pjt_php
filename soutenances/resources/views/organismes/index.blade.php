<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Organismes</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

<div class="container">
    <h1>Gestion des Organismes (PostgreSQL)</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <form action="{{ route('organismes.store') }}" method="POST">
        @csrf
        <input type="text" name="design" placeholder="Désignation (ex: Ministère)" required>
        <input type="text" name="lieu" placeholder="Lieu (ex: Paris)" required>
        <button type="submit">Ajouter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Désignation</th>
                <th>Lieu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($organismes as $organisme)
                <tr>
                    <td>{{ $organisme->idorg }}</td>
                    <td>{{ $organisme->design }}</td>
                    <td>{{ $organisme->lieu }}</td>
                    <td>
                        <a href="{{ route('organismes.edit', $organisme->idorg) }}" style="color: #3490dc; margin-right: 10px; text-decoration: none;">Modifier</a>
                        <form action="{{ route('organismes.destroy', $organisme->idorg) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet organisme ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Aucun organisme pour le moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>