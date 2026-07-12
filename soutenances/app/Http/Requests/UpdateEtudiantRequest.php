<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEtudiantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalise la casse de "nom" (MAJUSCULES) et "prenoms" (Première Lettre En Majuscule)
     * avant que les règles de validation ne s'appliquent, pour rester cohérent avec
     * ce qui est affiché/transformé côté interface (validation-etudiant.js).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nom' => $this->filled('nom')
                ? mb_strtoupper(trim($this->input('nom')), 'UTF-8')
                : $this->input('nom'),
            'prenoms' => $this->filled('prenoms')
                ? mb_convert_case(trim($this->input('prenoms')), MB_CASE_TITLE, 'UTF-8')
                : $this->input('prenoms'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:100|regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/',
            'prenoms' => 'required|string|max:100|regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/',
            'niveau' => ['required', Rule::in(['L1', 'L2', 'L3', 'M1', 'M2'])],
            'parcours' => ['required', Rule::in(['GB', 'SR', 'IG'])],
            'adr_email' => [
                'required',
                'email',
                'max:150',
                // ON CHANGE ICI : On utilise 'matricule' qui correspond au paramètre de ta route web.php
                Rule::unique('etudiants', 'adr_email')->ignore($this->route('matricule'), 'matricule'),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.regex' => 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes.',
            'nom.max' => 'Le nom ne doit pas dépasser 100 caractères.',
            'prenoms.required' => 'Les prénoms sont obligatoires.',
            'prenoms.regex' => 'Les prénoms ne doivent contenir que des lettres, espaces, tirets et apostrophes.',
            'prenoms.max' => 'Les prénoms ne doivent pas dépasser 100 caractères.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.in' => 'Le niveau doit être L1, L2, L3, M1 ou M2.',
            'parcours.required' => 'Le parcours est obligatoire.',
            'parcours.in' => 'Le parcours doit être GB, SR ou IG.',
            'adr_email.required' => 'L\'adresse email est obligatoire.',
            'adr_email.email' => 'L\'adresse email doit être valide.',
            'adr_email.unique' => 'Cette adresse email est déjà utilisée.',
            'adr_email.max' => 'L\'adresse email ne doit pas dépasser 150 caractères.',
        ];
    }
}