<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Professeurs</title>
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
</head>
<body>

<x-header />
<div class="app-body">
<x-sidebar />

<main class="main-content">

<div class="container">
    <h1>Gestion des Professeurs</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher un professeur</h3>
        <form id="professeur-search-form" action="{{ route('professeurs.index') }}" method="GET" class="flex-form" onsubmit="return false;">
            <input id="professeur-search-input" type="text" name="search" value="{{ $search }}" placeholder="Par identifiant, nom ou prénom...">
            <button id="professeur-search-btn" type="button" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if($search)
                <a id="professeur-reset" href="{{ route('professeurs.index') }}" style="align-self: center; color: #e74c3c;">Réinitialiser</a>
            @endif
            <div id="professeur-loader" style="display:none; align-self:center; margin-left:10px;">Chargement…</div>
        </form>
    </div>

    <h3>Ajouter un nouveau professeur</h3>
    <form action="{{ route('professeurs.store') }}" method="POST" class="flex-form">
        @csrf
        <input type="text" name="idprof" placeholder="ID Prof (ex: P001)" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenoms" placeholder="Prénoms" required>

        <select name="civilite" required>
            <option value="">-- Choisir Civilité --</option>
            <option value="Mr">Mr</option>
            <option value="Mme">Mme</option>
            <option value="Mlle">Mlle</option>
        </select>

        <select name="grade" required>
            <option value="">-- Choisir Grade --</option>
            <option value="Professeur titulaire">Professeur titulaire</option>
            <option value="Maître de Conférences">Maître de Conférences</option>
            <option value="Assistant d’Enseignement Supérieur et de Recherche">Assistant d’Enseignement Supérieur et de Recherche</option>
            <option value="Docteur HDR">Docteur HDR</option>
            <option value="Docteur en Informatique">Docteur en Informatique</option>
            <option value="Doctorant en informatique">Doctorant en informatique</option>
        </select>

        <button type="submit">Ajouter le professeur</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénoms</th>
                <th>Civilité</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="professeurs-results">
            @include('professeurs._rows')
        </tbody>
    </table>
</div>

</main>
</div>

<script>
    (function(){
        const input = document.getElementById('professeur-search-input');
        const btn = document.getElementById('professeur-search-btn');
        const results = document.getElementById('professeurs-results');
        const loader = document.getElementById('professeur-loader');
        let timer = null;

        function fetchResults(q){
            const url = new URL('{{ route('professeurs.index')}}', window.location.origin);
            if(q) url.searchParams.set('search', q);
            if(loader) loader.style.display = 'inline';
            fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(json => {
                    if(loader) loader.style.display = 'none';
                    const rows = (json.data || []).map(e => `
                        <tr>
                            <td>${e.idprof}</td>
                            <td>${e.nom}</td>
                            <td>${e.prenoms}</td>
                            <td>${e.civilite}</td>
                            <td>${e.grade}</td>
                            <td>
                                <a href="/professeurs/${e.idprof}/edit" style="color: #3490dc; margin-right: 10px; text-decoration: none;"><i class="fa-regular fa-pen-to-square"></i></a>
                                <form action="/professeurs/${e.idprof}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce professeur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;"><i class="fa-regular fa-trash-can"></i></button>
                                </form>
                            </td>
                        </tr>
                    `).join('');

                    if(rows.length === 0) {
                        results.innerHTML = '<tr><td colspan="6" style="text-align:center;">Aucun professeur trouvé.</td></tr>';
                    } else {
                        results.innerHTML = rows;
                    }
                })
                .catch(err => {
                    if(loader) loader.style.display = 'none';
                    console.error(err);
                });
        }

        function debounceFetch(){
            clearTimeout(timer);
            timer = setTimeout(()=> fetchResults(input.value.trim()), 300);
        }

        input.addEventListener('input', debounceFetch);
        btn.addEventListener('click', ()=> fetchResults(input.value.trim()));
    })();
</script>

</body>
</html>
