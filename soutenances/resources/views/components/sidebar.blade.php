<aside class="sidebar">
    <nav class="sidebar__nav">
        <ul class="sidebar__menu">
            <li>
                <a href="{{ url('/') }}" class="sidebar__link {{ request()->is('/') ? 'sidebar__link--active' : '' }}">
                    <i class="fas fa-home sidebar__link-icon"></i>
                    <span class="sidebar__link-label">Accueil</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/etudiants') }}" class="sidebar__link {{ request()->is('etudiants*') ? 'sidebar__link--active' : '' }}">
                    <i class="fas fa-graduation-cap sidebar__link-icon"></i>
                    <span class="sidebar__link-label">Étudiants</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/professeurs') }}" class="sidebar__link {{ request()->is('professeurs*') ? 'sidebar__link--active' : '' }}">
                    <i class="fas fa-chalkboard-teacher sidebar__link-icon"></i>
                    <span class="sidebar__link-label">Professeurs</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/organismes') }}" class="sidebar__link {{ request()->is('organismes*') ? 'sidebar__link--active' : '' }}">
                    <i class="fas fa-building sidebar__link-icon"></i>
                    <span class="sidebar__link-label">Organismes</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/soutenances') }}" class="sidebar__link {{ request()->is('soutenances*') ? 'sidebar__link--active' : '' }}">
                    <i class="fas fa-project-diagram sidebar__link-icon"></i>
                    <span class="sidebar__link-label">Soutenances</span>
                </a>
            </li>
            <li>
                <a href="{{ url('/generation-pdf') }}" class="sidebar__link {{ request()->is('generation-pdf*') ? 'sidebar__link--active' : '' }}">
                    <i class="fas fa-file-pdf sidebar__link-icon"></i>
                    <span class="sidebar__link-label">Génération PDF</span>
                </a>
            </li>
            </ul>
    </nav>
</aside>