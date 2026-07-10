<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
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
        .main-content { flex: 1; margin-left: 260px; padding: var(--space-lg); }
        .page-header { margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md); }
        .page-header h1 { margin: 0; font-size: var(--font-size-xl); color: var(--color-secondary); }
        .subtitle { color: var(--color-text-muted); margin: var(--space-sm) 0 0 0; font-size: var(--font-size-base); }

        /* --- Cartes du tableau de bord (remplacent les anciens placeholders) --- */
        .dashboard-card {
            background: #fff;
            padding: 1.25rem;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            min-width: 0; /* évite que le contenu (ex: tableau) ne force le débordement de la grille */
        }
        .dashboard-card h3 {
            margin: 0 0 1rem 0;
            color: #1e293b;
            font-size: 1.05rem;
        }
        .dashboard-card .date-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .dashboard-card .date-filter-form input[type="date"] {
            flex: 1 1 130px;
            min-width: 0;
            padding: 0.45rem 0.6rem;
            border: 1px solid var(--color-border, #cbd5e1);
            border-radius: 6px;
            font-size: 0.85rem;
        }
        .dashboard-card .date-filter-hint {
            font-size: 0.85rem;
            color: var(--color-text-muted);
            margin: 0 0 0.75rem 0;
        }
        .dashboard-card .table-scroll {
            overflow-x: auto;
        }
        .dashboard-card table.stats-table {
            width: 100%;
        }
    </style>
</head>
<body>

    <x-header />

    <div class="app-body">
        <x-sidebar />

        <main class="main-content">
            <div class="container">
                <header class="page-header">
                    <h1>Gestion des soutenances    Projet PHP n°2 en Binome : 3499 et 3502 GB Groupe1</h1>
                    <p class="subtitle">Application de gestion de soutenance</p>
                </header>

                <div class="welcome-body">
                    <p style="color: var(--color-text-muted); margin-bottom: 2rem;">
                        Bienvenue dans votre tableau de bord. Voici un aperçu global de l'application :
                    </p>

                    <section class="dashboard-section">
                        <h3 style="color: #1e293b; margin-bottom: 1.25rem;"> Effectif d'étudiants par niveaux</h3>

                        <div class="stats-cards-container">
                            @php
                                $niveauxCards = ['L1' => 0, 'L2' => 0, 'L3' => 0, 'M1' => 0, 'M2' => 0];
                                if(isset($effectifsParNiveau)) {
                                    foreach($effectifsParNiveau as $stat) {
                                        if(array_key_exists($stat->niveau, $niveauxCards)) {
                                            $niveauxCards[$stat->niveau] = $stat->effectif;
                                        }
                                    }
                                }
                            @endphp

                            @foreach($niveauxCards as $niveau => $effectif)
                                <div class="stat-card">
                                    <div class="stat-card__badge">{{ $niveau }}</div>
                                    <div class="stat-card__info">
                                        <span class="stat-card__count">{{ $effectif }}</span>
                                        <span class="stat-card__label">Étudiant{{ $effectif > 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="stat-card stat-card--total">
                                <div class="stat-card__badge"><i class="fa-solid fa-users"></i></div>
                                <div class="stat-card__info">
                                    <span class="stat-card__count">{{ $totalEtudiants ?? 0 }}</span>
                                    <span class="stat-card__label">Total Étudiants</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    </div>
            </div>
            <div class="dashboard-tables-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        
        <x-student-filter :etudiants="$tousEtudiants" />

        <div class="dashboard-card">
            <h3>Étudiants sans soutenance</h3>
            <div class="table-scroll">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénoms</th>
                            <th>Niveau</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etudiantsSansSoutenance as $etudiantSans)
                            <tr>
                                <td>{{ $etudiantSans->matricule }}</td>
                                <td>{{ $etudiantSans->nom }}</td>
                                <td>{{ $etudiantSans->prenoms }}</td>
                                <td>{{ $etudiantSans->niveau }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 1rem 0; color: var(--color-text-muted);">Tous les étudiants ont déjà une soutenance.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card">
            <h3>Notes des étudiants entre deux dates</h3>
            <form method="GET" class="date-filter-form">
                <input type="date" name="date_debut" value="{{ $dateDebut ?? '' }}">
                <input type="date" name="date_fin" value="{{ $dateFin ?? '' }}">
                <button type="submit" class="btn-search">Filtrer</button>
            </form>
            @if(($dateDebut ?? false) && ($dateFin ?? false))
                @php
                    try {
                        $dDeb = \Carbon\Carbon::createFromFormat('Y-m-d', $dateDebut)->format('d/m/Y');
                        $dFin = \Carbon\Carbon::createFromFormat('Y-m-d', $dateFin)->format('d/m/Y');
                    } catch (Exception $e) {
                        $dDeb = $dateDebut;
                        $dFin = $dateFin;
                    }
                @endphp
                <p class="date-filter-hint">Notes filtrées de <strong>{{ $dDeb }}</strong> à <strong>{{ $dFin }}</strong> :</p>
            @endif
            <div class="table-scroll">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Matricule</th>
                            <th>Année Univ</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notesEntreDates as $soutenanceFiltree)
                                <tr>
                                    <td>{{ optional($soutenanceFiltree->date_soutenance) ? \Carbon\Carbon::parse($soutenanceFiltree->date_soutenance)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $soutenanceFiltree->matricule }}</td>
                                    <td>{{ $soutenanceFiltree->annee_univ }}</td>
                                    <td>{{ $soutenanceFiltree->note }}/20</td>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 1rem 0; color: var(--color-text-muted);">
                                    @if(($dateDebut ?? false) && ($dateFin ?? false))
                                        Aucune note trouvée pour cette plage de dates.
                                    @else
                                        Choisissez une plage de dates pour afficher des notes.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
        </main>
    </div>

</body>
</html>