@props(['etudiants'])

<div class="dashboard-card-table">
    <div class="card-table-header">
        <h4><i class="fa-solid fa-filter" style="color: var(--color-primary); margin-right: 8px;"></i> Inscriptions par Classe</h4>
        
        <div class="filter-controls">
            <select id="filterNiveau" class="filter-select">
                <option value="L1" selected>L1</option>
                <option value="L2">L2</option>
                <option value="L3">L3</option>
                <option value="M1">M1</option>
                <option value="M2">M2</option>
            </select>

            <select id="filterParcours" class="filter-select">
                <option value="GB" selected>GB</option>
                <option value="SR">SR</option>
                <option value="IG">IG</option>
            </select>
        </div>
    </div>

    <div class="table-responsive-sm">
        <table class="small-dashboard-table">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom & Prénoms</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody id="filteredStudentsBody">
                @foreach($etudiants as $etudiant)
                    <tr class="student-row" data-niveau="{{ $etudiant->niveau }}" data-parcours="{{ $etudiant->parcours }}">
                        <td><strong>{{ $etudiant->matricule }}</strong></td>
                        <td>{{ $etudiant->nom }} {{ $etudiant->prenoms }}</td>
                        <td style="color: var(--color-text-muted); font-size: 0.85rem;">{{ $etudiant->adr_email }}</td>
                    </tr>
                @endforeach
                
                <tr id="noStudentsRow" style="display: none;">
                    <td colspan="3" style="text-align: center; color: var(--color-text-muted); padding: 1.5rem 0;">
                        Aucun étudiant inscrit dans cette section.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Styles pour le bloc de filtre de l'accueil */
    .dashboard-card-table {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .card-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.75rem;
    }
    .card-table-header h4 {
        margin: 0;
        color: #0f172a;
        font-size: 1rem;
    }
    .filter-controls {
        display: flex;
        gap: 0.5rem;
    }
    .filter-select {
        padding: 0.35rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background-color: #f8fafc;
        color: #334155;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        outline: none;
    }
    .filter-select:focus {
        border-color: #3b82f6;
    }
    .small-dashboard-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    .small-dashboard-table th {
        text-align: left;
        padding: 0.6rem 0.75rem;
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
    }
    .small-dashboard-table td {
        padding: 0.65rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .small-dashboard-table tr:hover td {
        background: #f8fafc;
    }
    .table-responsive-sm {
        max-height: 280px; /* Limite la hauteur avec un scroll si la liste est longue */
        overflow-y: auto;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectNiveau = document.getElementById('filterNiveau');
        const selectParcours = document.getElementById('filterParcours');
        const rows = document.querySelectorAll('.student-row');
        const noStudentsRow = document.getElementById('noStudentsRow');

        function filterStudents() {
            const selectedNiveau = selectNiveau.value;
            const selectedParcours = selectParcours.value;
            let hasVisibleStudents = false;

            rows.forEach(row => {
                const rowNiveau = row.getAttribute('data-niveau');
                const rowParcours = row.getAttribute('data-parcours');

                // Si la ligne correspond aux deux filtres sélectionnées
                if (rowNiveau === selectedNiveau && rowParcours === selectedParcours) {
                    row.style.display = '';
                    hasVisibleStudents = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Afficher ou masquer le message "Aucun étudiant"
            if (hasVisibleStudents) {
                noStudentsRow.style.display = 'none';
            } else {
                noStudentsRow.style.display = '';
            }
        }

        // Écouter les changements sur les menus déroulants
        selectNiveau.addEventListener('change', filterStudents);
        selectParcours.addEventListener('change', filterStudents);

        // Lancer un premier filtrage au chargement (L1 GB par défaut)
        filterStudents();
    });
</script>