<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Professeurs</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

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
            <button id="professeur-search-btn" type="button" class="btn-search">Rechercher</button>
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
                                <a href="/professeurs/${e.idprof}/edit" style="color: #3490dc; margin-right: 10px; text-decoration: none;">Modifier</a>
                                <form action="/professeurs/${e.idprof}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce professeur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:#e3342f; cursor:pointer; padding:0;">Supprimer</button>
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
