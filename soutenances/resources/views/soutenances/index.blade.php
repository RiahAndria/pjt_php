<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Soutenances</title>
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

        /* --- Bouton d'action "Générer PDF" --- */
        .action-pdf { color: #16a34a; background: none; border: none; cursor: pointer; font-size: 1rem; padding: 4px; }
        .action-pdf:hover { color: #15803d; }

        /* --- Modale de confirmation de génération PDF --- */
        .pdf-modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.55);
            align-items: center; justify-content: center;
            z-index: 200;
        }
        .pdf-modal {
            background: #fff;
            border-radius: 12px;
            padding: 28px 32px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .pdf-modal h3 { margin: 0 0 12px; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .pdf-modal p { margin: 6px 0; color: #334155; }
        .pdf-modal-note { font-size: 0.85rem; color: #64748b; }
        .pdf-modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .pdf-modal-cancel {
            padding: 0.6rem 1.1rem; border-radius: 0.6rem; border: 1px solid #cbd5e1;
            background: #fff; color: #334155; cursor: pointer; font-weight: 600;
        }
        .pdf-modal-confirm {
            padding: 0.6rem 1.1rem; border-radius: 0.6rem; border: none;
            background: #16a34a; color: #fff; text-decoration: none; font-weight: 600;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .pdf-modal-confirm:hover { background: #15803d; }
    </style>
</head>
<body>

<x-header />
<div class="app-body">
<x-sidebar />

<main class="main-content">

<div class="container">
    <h1>Gestion des Soutenances</h1>
    
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="search-box">
        <h3>Rechercher une soutenance</h3>
        <form action="{{ route('soutenances.index') }}" method="GET" class="flex-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Matricule ou Année...">
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if($search)
                <a href="{{ route('soutenances.index') }}" class="reset-link">Réinitialiser</a>
            @endif
        </form>
    </div>

    <h3>Ajouter une nouvelle soutenance</h3>
    <form action="{{ route('soutenances.store') }}" method="POST" class="flex-form">
        @csrf
        
        <select name="matricule" required>
            <option value="">-- Étudiant --</option>
            @foreach($etudiants as $etudiant)
                <option value="{{ $etudiant->matricule }}">{{ $etudiant->matricule }} - {{ $etudiant->nom }}</option>
            @endforeach
        </select>

        <select name="idorg" required>
            <option value="">-- Organisme --</option>
            @foreach($organismes as $organisme)
                <option value="{{ $organisme->idorg }}">{{ $organisme->design }} ({{ $organisme->lieu }})</option>
            @endforeach
        </select>

        <input type="text" name="annee_univ" placeholder="Année univ (ex: 2022-2023)" required>
        <input type="text" id="date_soutenance_display" placeholder="jj/mm/aaaa" maxlength="10" inputmode="numeric" autocomplete="off">
        <input type="hidden" name="date_soutenance" id="date_soutenance_hidden">
        <input type="number" name="note" placeholder="Note" min="0" max="20" required>
        
        <select name="president" required>
            <option value="">-- Président du Jury --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <select name="examinateur" required>
            <option value="">-- Examinateur --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <select name="rapporteur_int" required>
            <option value="">-- Rapporteur Int. --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <select name="rapporteur_ext" required>
            <option value="">-- Rapporteur Ext. --</option>
            @foreach($professeurs as $prof)
                <option value="{{ $prof->idprof }}">{{ $prof->civilite }} {{ $prof->nom }} {{ $prof->prenoms }}</option>
            @endforeach
        </select>

        <button type="submit">Ajouter la soutenance</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Organisme</th>
                <th>Année Univ</th>
                <th>Note</th>
                <th>Président (ID)</th>
                <th>Examinateur (ID)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($soutenances as $soutenance)
                <tr>
                    <td>{{ $soutenance->matricule }}</td>
                    <td>{{ $soutenance->idorg }}</td>
                    <td>{{ $soutenance->annee_univ }}</td>
                    <td><span class="note-tag">{{ $soutenance->note }}/20</span></td>
                    <td>{{ $soutenance->president }}</td>
                    <td>{{ $soutenance->examinateur }}</td>
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('soutenances.edit', $soutenance->id) }}" class="action-edit"><i class="fa-regular fa-pen-to-square"></i></a>

                            <button
                                type="button"
                                class="action-pdf"
                                title="Générer le procès-verbal PDF"
                                data-url="{{ route('soutenances.pdf', $soutenance->id) }}"
                                data-etudiant="{{ $soutenance->etudiant ? $soutenance->etudiant->nom . ' ' . $soutenance->etudiant->prenoms : $soutenance->matricule }}"
                                data-annee="{{ $soutenance->annee_univ }}"
                                onclick="openPdfModal(this)"
                            >
                                <i class="fa-solid fa-file-pdf"></i>
                            </button>

                            <form action="{{ route('soutenances.destroy', $soutenance->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette soutenance ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: var(--space-lg) 0;">Aucune soutenance trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
    
</main>
</div>

<!-- Modale de confirmation avant génération du PDF -->
<div id="pdf-modal-overlay" class="pdf-modal-overlay">
    <div class="pdf-modal">
        <h3><i class="fa-solid fa-file-pdf"></i> Générer le procès-verbal</h3>
        <p>Voulez-vous générer le PDF de la soutenance de <strong id="pdf-modal-etudiant"></strong> — <span id="pdf-modal-annee"></span> ?</p>
        <p class="pdf-modal-note">Le fichier sera automatiquement téléchargé après confirmation.</p>
        <div class="pdf-modal-actions">
            <button type="button" class="pdf-modal-cancel" onclick="closePdfModal()">Annuler</button>
            <a href="#" id="pdf-modal-confirm" class="pdf-modal-confirm">
                <i class="fa-solid fa-download"></i> Générer et télécharger
            </a>
        </div>
    </div>
</div>

<script>
    function openPdfModal(btn) {
        document.getElementById('pdf-modal-etudiant').textContent = btn.dataset.etudiant;
        document.getElementById('pdf-modal-annee').textContent = btn.dataset.annee;
        document.getElementById('pdf-modal-confirm').setAttribute('href', btn.dataset.url);
        document.getElementById('pdf-modal-overlay').style.display = 'flex';
    }

    function closePdfModal() {
        document.getElementById('pdf-modal-overlay').style.display = 'none';
    }

    // Ferme la modale si on clique en dehors de la boîte, ou après avoir lancé le téléchargement
    document.getElementById('pdf-modal-overlay').addEventListener('click', function (e) {
        if (e.target === this) closePdfModal();
    });
    document.getElementById('pdf-modal-confirm').addEventListener('click', function () {
        setTimeout(closePdfModal, 300);
    });

    // --- Masque de saisie jj/mm/aaaa pour "Date de soutenance" ---
    // On n'utilise volontairement pas <input type="date"> : son format d'affichage
    // dépend de la langue/du système du navigateur (parfois mm/dd/yyyy, parfois yyyy-mm-dd...).
    // Ici on force visuellement jj/mm/aaaa, et on convertit vers le format ISO (aaaa-mm-jj)
    // attendu par Laravel dans un champ caché juste avant l'envoi du formulaire.
    (function () {
        const display = document.getElementById('date_soutenance_display');
        const hidden = document.getElementById('date_soutenance_hidden');
        if (!display || !hidden) return;

        display.addEventListener('input', function () {
            let digits = display.value.replace(/\D/g, '').slice(0, 8);
            let formatted = digits;
            if (digits.length > 4) {
                formatted = digits.slice(0, 2) + '/' + digits.slice(2, 4) + '/' + digits.slice(4);
            } else if (digits.length > 2) {
                formatted = digits.slice(0, 2) + '/' + digits.slice(2);
            }
            display.value = formatted;
        });

        display.closest('form').addEventListener('submit', function (e) {
            const match = display.value.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
            if (display.value && !match) {
                e.preventDefault();
                alert('Merci de saisir une date valide au format jj/mm/aaaa.');
                return;
            }
            hidden.value = match ? `${match[3]}-${match[2]}-${match[1]}` : '';
        });
    })();
</script>

</body>
</html>