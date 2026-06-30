<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des soutenances</title>
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
    </style>
</head>
<body>

    <x-header />

    <div class="app-body">
        <x-sidebar />

        <main class="main-content">
            <div class="container">
                <header class="page-header">
                    <h1>Gestion des soutenances</h1>
                    <p class="subtitle">Application de gestion de soutenance</p>
                </header>

                <div class="welcome-body">
                    <p>Sélectionnez une option dans le menu latéral pour commencer à gérer votre application.</p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>