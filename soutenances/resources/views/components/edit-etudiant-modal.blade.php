@props(['etudiant', 'id'])

<div id="modal-{{ $id }}" class="custom-modal" style="display: none;">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h2>Modifier l'Étudiant : {{ $etudiant->matricule }}</h2>
            <button class="close-modal-btn" onclick="closeModal('{{ $id }}')">&times;</button>
        </div>

        <form action="{{ route('etudiants.update', $etudiant->matricule) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" value="{{ $etudiant->nom }}" required>
            </div>

            <div class="form-group">
                <label>Prénoms</label>
                <input type="text" name="prenoms" value="{{ $etudiant->prenoms }}" required>
            </div>

            <div class="form-group">
                <label>Niveau</label>
                <select name="niveau" required>
                    <option value="L1" {{ $etudiant->niveau == 'L1' ? 'selected' : '' }}>L1</option>
                    <option value="L2" {{ $etudiant->niveau == 'L2' ? 'selected' : '' }}>L2</option>
                    <option value="L3" {{ $etudiant->niveau == 'L3' ? 'selected' : '' }}>L3</option>
                    <option value="M1" {{ $etudiant->niveau == 'M1' ? 'selected' : '' }}>M1</option>
                    <option value="M2" {{ $etudiant->niveau == 'M2' ? 'selected' : '' }}>M2</option>
                </select>
            </div>

            <div class="form-group">
                <label>Parcours</label>
                <select name="parcours" required>
                    <option value="GB" {{ $etudiant->parcours == 'GB' ? 'selected' : '' }}>GB</option>
                    <option value="SR" {{ $etudiant->parcours == 'SR' ? 'selected' : '' }}>SR</option>
                    <option value="IG" {{ $etudiant->parcours == 'IG' ? 'selected' : '' }}>IG</option>
                </select>
            </div>

            <div class="form-group">
                <label>Adresse Email</label>
                <input type="email" name="adr_email" value="{{ $etudiant->adr_email }}" required>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn-back" onclick="closeModal('{{ $id }}')">Annuler</button>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>