document.addEventListener('DOMContentLoaded', function () {

    // Regex alignées sur celles utilisées côté serveur dans OrganismeController.php
    const validationRules = {
        design: {
            // (?=.*\p{L}) : exige au moins une lettre, pour rejeter les designations
            // purement numériques comme "5646545" (aligné sur la regex serveur).
            pattern: /^(?=.*\p{L})[\p{L}0-9][\p{L}0-9\s'\-.,&()]*$/u,
            maxLength: 150,
            invalidMessage: 'La désignation doit contenir au moins une lettre (chiffres et ponctuation courante autorisés en complément).'
        },
        lieu: {
            pattern: /^[\p{L}][\p{L}\s\-]*$/u,
            maxLength: 100,
            invalidMessage: 'Le lieu ne doit contenir que des lettres, espaces et tirets, et doit commencer par une lettre.'
        }
    };

    // ---------- Transformation de casse en direct (sans changer la position du curseur) ----------

    // Met en majuscule la première lettre de chaque "mot" (séparé par espace, apostrophe ou tiret),
    // et le reste en minuscule. Ex: "ministere de l'enseignement" -> "Ministere De L'Enseignement"
    // (aligné sur mb_convert_case(..., MB_CASE_TITLE, 'UTF-8') utilisé côté serveur)
    function toTitleCaseFr(str) {
        return str
            .toLocaleLowerCase('fr-FR')
            .replace(/(^|[\s\-'])([a-zà-ÿ])/g, function (match, sep, letter) {
                return sep + letter.toLocaleUpperCase('fr-FR');
            });
    }

    function applyLiveTransform(input, transformFn) {
        const cursorStart = input.selectionStart;
        const cursorEnd = input.selectionEnd;
        const oldValue = input.value;
        const newValue = transformFn(oldValue);

        if (newValue !== oldValue) {
            input.value = newValue;
            input.setSelectionRange(cursorStart, cursorEnd);
        }
    }

    function attachLiveCaseTransform(form) {
        const designInput = form.querySelector('input[name="design"]');
        const lieuInput = form.querySelector('input[name="lieu"]');

        if (designInput) {
            designInput.addEventListener('input', function () {
                applyLiveTransform(designInput, toTitleCaseFr);
            });
        }

        if (lieuInput) {
            lieuInput.addEventListener('input', function () {
                applyLiveTransform(lieuInput, toTitleCaseFr);
            });
        }
    }

    // ---------- Validation + affichage des messages d'erreur sous chaque champ ----------

    function getErrorMessage(input) {
        const name = input.name;
        const rule = validationRules[name];
        if (!rule) {
            return '';
        }

        const value = input.value.trim();

        // Champ vide (jamais saisi, ou saisie effacée/annulée) : aucun message.
        // L'attribut HTML5 "required" empêche déjà l'envoi d'un champ vide.
        if (value.length === 0) {
            return '';
        }

        if (rule.maxLength && value.length > rule.maxLength) {
            return rule.invalidMessage;
        }

        if (rule.pattern && !rule.pattern.test(value)) {
            return rule.invalidMessage;
        }

        return '';
    }

    function showFieldError(input) {
        const form = input.closest('form');
        const messageElement = form ? form.querySelector(`.field-error-message[data-error-for="${input.name}"]`) : null;
        const error = getErrorMessage(input);

        if (messageElement) {
            messageElement.textContent = error;
            messageElement.style.display = error ? 'block' : 'none';
        }

        if (error) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }

    const forms = document.querySelectorAll('form.organisme-validation');
    if (!forms.length) {
        return;
    }

    forms.forEach((form) => {
        attachLiveCaseTransform(form);

        const inputs = form.querySelectorAll('input[name], select[name]');

        inputs.forEach((input) => {
            input.addEventListener('input', () => showFieldError(input));
            input.addEventListener('blur', () => showFieldError(input));
        });

        form.addEventListener('submit', function (event) {
            let hasError = false;

            inputs.forEach((input) => {
                showFieldError(input);
                if (getErrorMessage(input)) {
                    hasError = true;
                }
            });

            if (hasError) {
                event.preventDefault();
            }
        });
    });
});