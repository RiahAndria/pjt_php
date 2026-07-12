@props(['notesEntreDates', 'dateDebut', 'dateFin'])

<div class="dashboard-card-table" style="margin-bottom: 1.5rem;">
    <div class="card-table-header">
        <h4>
            <i class="fa-solid fa-calendar-days" style="color: var(--color-primary); margin-right: 8px;"></i> 
            Notes des étudiants entre 
            {{ $dateDebut ? \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') : '...' }} 
            et 
            {{ $dateFin ? \Carbon\Carbon::parse($dateFin)->format('d/m/Y') : '...' }}
        </h4>
    </div>

    <div class="table-responsive-sm">
        <table class="small-dashboard-table">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Année Univ</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notesEntreDates as $soutenanceFiltree)
                    <tr>
                        <td><strong>{{ $soutenanceFiltree->matricule }}</strong></td>
                        <td>{{ $soutenanceFiltree->annee_univ }}</td>
                        <td><span class="note-tag" style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600;">{{ $soutenanceFiltree->note }}/20</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--color-text-muted); padding: 1.5rem 0;">
                            Aucune note trouvée pour cette plage de dates.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>