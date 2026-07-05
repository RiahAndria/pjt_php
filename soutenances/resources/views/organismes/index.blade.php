<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Organismes</title>
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

        /* --- Ajouts pour la validation en direct --- */
        .form-group { position: relative; display: inline-block; }
        input.is-invalid { border-color: #dc3545; }
        .field-error-message {
            display: none;
            position: absolute;
            left: 0;
            top: calc(100% + 0.35rem);
            z-index: 20;
            padding: 0.45rem 0.65rem;
            background: rgba(220, 53, 69, 0.96);
            color: white;
            border-radius: 0.35rem;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            white-space: normal;
            max-width: 280px;
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
    <script src="{{ asset('js/validation-organisme.js') }}" defer></script>
</head>
<body>

<x-header />
<div class="app-body">
<x-sidebar />

<main class="main-content">

<div class="container">
    <h1>Gestion des Organismes</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <form action="{{ route('organismes.store') }}" method="POST" class="organisme-validation">
        @csrf

        <div class="form-group">
            <input type="text" id="design" name="design" placeholder="Désignation (ex: Ministère)" value="{{ old('design') }}" maxlength="150" class="@error('design') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="design"></span>
        </div>

        <div class="form-group">
            <input type="text" id="lieu" name="lieu" placeholder="Lieu (ex: Paris)" value="{{ old('lieu') }}" maxlength="100" class="@error('lieu') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="lieu"></span>
        </div>

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
                        <a href="{{ route('organismes.edit', $organisme->idorg) }}" style="color: #3490dc; margin-right: 10px; text-decoration: none;"><i class="fa-regular fa-pen-to-square"></i></a>
                        <form action="{{ route('organismes.destroy', $organisme->idorg) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cet organisme ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;"><i class="fa-regular fa-trash-can"></i></button>
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

</main>
</div>

</body>
</html>