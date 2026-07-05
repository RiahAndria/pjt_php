@props(['etudiant', 'id'])

<div id="modal-{{ $id }}" class="custom-modal" style="display: none;">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h2>Modifier l'Étudiant : {{ $etudiant->matricule }}</h2>
            <button class="close-modal-btn" onclick="closeModal('{{ $id }}')">&times;</button>
        </div>

        @if ($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin: 15px; border: 1px solid #f5c6cb;">
                <strong>Erreurs trouvées :</strong>
                <ul style="margin: 5px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('etudiants.update', $etudiant->matricule) }}" method="POST" class="student-validation">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nom-{{ $id }}">Nom <span style="color: #dc3545;">*</span></label>
                <input 
                    type="text" 
                    id="nom-{{ $id }}"
                    name="nom" 
                    value="{{ old('nom', $etudiant->nom) }}" 
                    maxlength="100"
                    class="@error('nom') is-invalid @enderror"
                    required
                >
                <span class="field-error-message" data-error-for="nom"></span>
                @error('nom')
                    <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="prenoms-{{ $id }}">Prénoms <span style="color: #dc3545;">*</span></label>
                <input 
                    type="text" 
                    id="prenoms-{{ $id }}"
                    name="prenoms" 
                    value="{{ old('prenoms', $etudiant->prenoms) }}" 
                    maxlength="100"
                    class="@error('prenoms') is-invalid @enderror"
                    required
                >
                <span class="field-error-message" data-error-for="prenoms"></span>
                @error('prenoms')
                    <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="niveau-{{ $id }}">Niveau <span style="color: #dc3545;">*</span></label>
                <select 
                    id="niveau-{{ $id }}"
                    name="niveau" 
                    class="@error('niveau') is-invalid @enderror"
                    required
                >
                    <option value="">-- Sélectionner un niveau --</option>
                    <option value="L1" {{ old('niveau', $etudiant->niveau) == 'L1' ? 'selected' : '' }}>L1</option>
                    <option value="L2" {{ old('niveau', $etudiant->niveau) == 'L2' ? 'selected' : '' }}>L2</option>
                    <option value="L3" {{ old('niveau', $etudiant->niveau) == 'L3' ? 'selected' : '' }}>L3</option>
                    <option value="M1" {{ old('niveau', $etudiant->niveau) == 'M1' ? 'selected' : '' }}>M1</option>
                    <option value="M2" {{ old('niveau', $etudiant->niveau) == 'M2' ? 'selected' : '' }}>M2</option>
                </select>
                <span class="field-error-message" data-error-for="niveau"></span>
                @error('niveau')
                    <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="parcours-{{ $id }}">Parcours <span style="color: #dc3545;">*</span></label>
                <select 
                    id="parcours-{{ $id }}"
                    name="parcours" 
                    class="@error('parcours') is-invalid @enderror"
                    required
                >
                    <option value="">-- Sélectionner un parcours --</option>
                    <option value="GB" {{ old('parcours', $etudiant->parcours) == 'GB' ? 'selected' : '' }}>GB</option>
                    <option value="SR" {{ old('parcours', $etudiant->parcours) == 'SR' ? 'selected' : '' }}>SR</option>
                    <option value="IG" {{ old('parcours', $etudiant->parcours) == 'IG' ? 'selected' : '' }}>IG</option>
                </select>
                <span class="field-error-message" data-error-for="parcours"></span>
                @error('parcours')
                    <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="adr_email-{{ $id }}">Adresse Email <span style="color: #dc3545;">*</span></label>
                <input 
                    type="email" 
                    id="adr_email-{{ $id }}"
                    name="adr_email" 
                    value="{{ old('adr_email', $etudiant->adr_email) }}" 
                    maxlength="150"
                    class="@error('adr_email') is-invalid @enderror"
                    required
                >
                <span class="field-error-message" data-error-for="adr_email"></span>
                @error('adr_email')
                    <span style="color: #dc3545; font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn-back" onclick="closeModal('{{ $id }}')">Annuler</button>
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openModal('{{ $id }}');
        });
    </script>
@endif