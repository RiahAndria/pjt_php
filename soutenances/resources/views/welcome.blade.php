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

        /* --- NOUVEAUX STYLES POUR LES ONGLETS --- */
        .tabs-container {
            margin-top: 2rem;
            background: #fff;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.06);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .tabs-nav {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .tab-btn {
            flex: 1;
            padding: 1rem var(--space-md);
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .tab-btn:hover {
            color: #0f172a;
            background: rgba(0, 0, 0, 0.01);
        }
        .tab-btn.active {
            color: var(--color-primary);
            border-bottom-color: var(--color-primary);
            background: #ffffff;
        }
        .tab-panel {
            display: none;
            padding: 1.5rem;
        }
        .tab-panel.active {
            display: block;
        }
    </style>
</head>
<body>

    <x-header />

    <div class="app-body">
        <x-sidebar />

        <main class="main-content">
            <div class="container">
                
                <!-- En-tête de la page -->
                <header class="page-header">
                    <h1>Gestion des soutenances</h1>
                    <p class="subtitle">Application de gestion de soutenance</p>
                </header>

                <!-- Section des statistiques -->
                <div class="welcome-body">
                    <p style="color: var(--color-text-muted); margin-bottom: 2rem;">
                        Bienvenue dans votre tableau de bord. Voici un aperçu global de l'application :
                    </p>

                    <section class="dashboard-section">
                        <h3 style="color: #1e293b; margin-bottom: 1.25rem;">Effectif d'étudiants par niveaux</h3>

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

                <!-- Structure en Onglets -->
                <div class="tabs-container">
                    <div class="tabs-nav">
                        <button class="tab-btn active" data-tab="tab-inscriptions">
                            <i class="fa-solid fa-filter"></i> Inscriptions par Classe
                        </button>
                        <button class="tab-btn" data-tab="tab-sans-soutenance">
                            <i class="fa-solid fa-user-slash"></i> Sans soutenance
                        </button>
                        <button class="tab-btn" data-tab="tab-rapport-notes">
                            <i class="fa-solid fa-calendar-days"></i> Rapport des notes
                        </button>
                    </div>

                    <!-- Contenu Onglet 1 : Inscriptions par classe -->
                    <div class="tab-panel active" id="tab-inscriptions">
                        <x-student-filter :etudiants="$tousEtudiants" />
                    </div>

                    <!-- Contenu Onglet 2 : Sans soutenance -->
                    <div class="tab-panel" id="tab-sans-soutenance">
                        <x-etudiants-sans-soutenance :etudiants="$etudiantsSansSoutenance" />
                    </div>

                    <!-- Contenu Onglet 3 : Rapport des notes (Filtre + Tableau) -->
                    <div class="tab-panel" id="tab-rapport-notes">
    
                        <!-- Boîte de filtrage avec des contrôles alignés et réduits -->
                        <div class="search-box" style="background: #f8fafc; padding: 1rem 1.25rem; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                            <h3 style="margin: 0; color: #1e293b; font-size: 1rem;">
                                <i class="fa-solid fa-filter" style="color: var(--color-primary); margin-right: 8px;"></i> 
                                Filtrer par période
                            </h3>
                            
                            <form action="{{ route('home') }}" method="GET" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <label style="font-size: 0.85rem; color: #64748b; font-weight: 600;">Du</label>
                                    <input type="date" name="date_debut" value="{{ $dateDebut ?? '' }}" style="padding: 0.35rem 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; background: #fff; font-size: 0.85rem; color: #334155;">
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <label style="font-size: 0.85rem; color: #64748b; font-weight: 600;">Au</label>
                                    <input type="date" name="date_fin" value="{{ $dateFin ?? '' }}" style="padding: 0.35rem 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; background: #fff; font-size: 0.85rem; color: #334155;">
                                </div>

                                <button type="submit" style="padding: 0.35rem 1rem; background: var(--color-primary); color: white; border: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                                    Rechercher
                                </button>

                                @if(($dateDebut ?? false) && ($dateFin ?? false))
                                    <a href="{{ route('home') }}" style="color: #ef4444; text-decoration: none; font-size: 0.85rem; font-weight: 600; margin-left: 4px;">Réinitialiser</a>
                                @endif
                            </form>
                        </div>

                        <!-- Affichage du tableau ou du message vide -->
                        @if(($dateDebut ?? false) && ($dateFin ?? false))
                            <x-soutenances-dates 
                                :notes-entre-dates="$notesEntreDates" 
                                :date-debut="$dateDebut" 
                                :date-fin="$dateFin" 
                            />
                        @else
                            <div style="background: #fff; padding: 1.25rem; border-radius: 10px; border: 1px solid rgba(0,0,0,0.06); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--color-text-muted); text-align: center; min-height: 200px;">
                                <i class="fa-solid fa-calendar-xmark" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.75rem;"></i>
                                <p style="margin: 0; font-size: 0.9rem;">Aucun filtre de date appliqué pour le rapport des notes.</p>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Script de gestion du basculement d'onglets et persistance après filtrage -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');

            // Détection : Si une recherche par date est active, on force l'ouverture du 3ème onglet
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('date_debut') || urlParams.has('date_fin')) {
                switchTab('tab-rapport-notes');
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.getAttribute('data-tab');
                    switchTab(target);
                });
            });

            function switchTab(tabId) {
                tabs.forEach(t => {
                    if(t.getAttribute('data-tab') === tabId) t.classList.add('active');
                    else t.classList.remove('active');
                });

                panels.forEach(p => {
                    if(p.id === tabId) p.classList.add('active');
                    else p.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>