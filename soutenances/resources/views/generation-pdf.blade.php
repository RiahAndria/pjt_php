<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Génération PDF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
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
        .container { max-width: 1100px; margin: auto; }
        .page-header { margin-bottom: var(--space-lg); border-bottom: 1px solid rgba(148, 163, 184, 0.3); padding-bottom: var(--space-md); }
        .page-header h1 { margin: 0; font-size: 2rem; color: #0f172a; }
        .subtitle { color: #475569; margin: .5rem 0 0; font-size: 1rem; }
        .pdf-card { background: white; border-radius: 16px; box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08); overflow: hidden; border: 1px solid rgba(148, 163, 184, 0.18); }
        .pdf-card__header { padding: 24px 32px; background: #0f172a; color: white; display: flex; align-items: center; justify-content: space-between; }
        .pdf-card__title { margin: 0; font-size: 1.4rem; }
        .pdf-card__body { padding: 32px; }
        .pdf-card__actions { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 24px; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.9rem 1.4rem; border-radius: 0.8rem; border: none; font-size: 0.95rem; font-weight: 600; text-decoration: none; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-secondary { background: white; color: #0f172a; border: 1px solid rgba(15, 23, 42, 0.12); }
        .pdf-preview { width: 100%; min-height: 720px; border: 1px solid rgba(148, 163, 184, 0.3); border-radius: 1rem; overflow: hidden; background: #f8fafc; }
        .pdf-preview iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>
    <x-header />
    <div class="app-body">
        <x-sidebar />
        <main class="main-content">
            <div class="container">
                <header class="page-header">
                    <h1>Page de génération PDF</h1>
                    <p class="subtitle">Utilise le modèle contenu dans le fichier PDF existant et respecte le style général de l'application.</p>
                </header>

                <div class="pdf-card">
                    <div class="pdf-card__header">
                        <div>
                            <p class="pdf-card__title">Modèle PDF</p>
                            <p style="margin: .5rem 0 0; color: rgba(255,255,255,.75);">Basé sur le fichier `Projet 2.pdf`</p>
                        </div>
                        <div class="pdf-card__actions">
                            <a href="{{ route('generation-pdf.modele') }}" class="btn btn-secondary" target="_blank"><i class="fa-solid fa-eye"></i> Voir le modèle</a>
                            <a href="{{ route('generation-pdf.export') }}" class="btn btn-primary"><i class="fa-solid fa-download"></i> Télécharger le PDF</a>
                        </div>
                    </div>
                    <div class="pdf-card__body">
                        <div class="pdf-preview">
                            <iframe src="{{ route('generation-pdf.modele') }}"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
