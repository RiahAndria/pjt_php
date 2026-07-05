@props(['etudiants'])

<div class="dashboard-card-table" style="margin-bottom: 1.5rem;">
    <div class="card-table-header">
        <h4>
            <i class="fa-solid fa-user-slash" style="color: #ef4444; margin-right: 8px;"></i> 
            Étudiants sans soutenance
        </h4>
    </div>

    <div class="table-responsive-sm">
        <table class="small-dashboard-table">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom & Prénoms</th>
                    <th>Niveau</th>
                </tr>
            </thead>
            <tbody>
                @forelse($etudiants as $etudiantSans)
                    <tr>
                        <td><strong>{{ $etudiantSans->matricule }}</strong></td>
                        <td>{{ $etudiantSans->nom }} {{ $etudiantSans->prenoms }}</td>
                        <td><span style="background: #eff6ff; color: #1e40af; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">{{ $etudiantSans->niveau }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--color-text-muted); padding: 1.5rem 0;">
                            Tous les étudiants ont déjà une soutenance.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>