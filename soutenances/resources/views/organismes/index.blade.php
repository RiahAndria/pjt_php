<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Organismes</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        form { margin-bottom: 30px; }
        input { padding: 8px; margin-right: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 8px 15px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background: #f8fafc; }
        .alert { padding: 10px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
    </style>
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