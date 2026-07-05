document.addEventListener('DOMContentLoaded', function () {

    // Note : la propriété "required" ci-dessous n'est pas utilisée pour afficher
    // un message en direct (voir getErrorMessage) : l'attribut HTML5 "required"
    // des champs suffit à empêcher l'envoi d'un champ vide. Elle est conservée
    // ici à titre indicatif/documentaire.
    const validationRules = {
        idprof: {
            pattern: /^[^\s]+$/, // pas d'espaces
            maxLength: 50,
            invalidMessage: 'L\'identifiant Professeur ne doit pas contenir d\'espaces et ne doit pas dépasser 50 caractères.'
        },
        nom: {
            pattern: /^[A-ZÀ-Ý\s\-']+$/, // en majuscules uniquement après transformation
            maxLength: 100,
            invalidMessage: 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes (converti automatiquement en majuscules).'
        },
        prenoms: {
            pattern: /^[a-zA-ZÀ-ÿ\s\-']+$/,
            maxLength: 150,
            invalidMessage: 'Les prénoms ne doivent contenir que des lettres, espaces, tirets et apostrophes (1ère lettre de chaque mot en majuscule).'
        },
        civilite: {
            options: ['Mr', 'Mme', 'Mlle'],
            invalidMessage: 'Veuillez choisir une civilité valide.'
        },
        grade: {
            options: [
                'Professeur titulaire',
                'Maître de Conférences',
                "Assistant d'Enseignement Supérieur et de Recherche",
                'Docteur HDR',
                'Docteur en Informatique',
                'Doctorant en informatique'
            ],
            invalidMessage: 'Veuillez choisir un grade valide.'
        }
    };

    // ---------- Transformation de casse en direct (sans changer la position du curseur) ----------

    function toUpperCaseFr(str) {
        return str.toLocaleUpperCase('fr-FR');
    }

    // Met en majuscule la première lettre de chaque "mot" (séparé par espace, apostrophe ou tiret),
    // et le reste en minuscule. Ex: "jean-pierre o'connor" -> "Jean-Pierre O'Connor"
    function toTitleCaseFr(str) {
        return str
            .toLocaleLowerCase('fr-FR')
            .replace(/(^|[\s\-'])([a-zà-ÿ])/g, function (match, sep, letter) {
                return sep + letter.toLocaleUpperCase('fr-FR');
            });
    }

    // Applique une transformation sur un champ texte tout en gardant la position du curseur intacte
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
        const nomInput = form.querySelector('input[name="nom"]');
        const prenomsInput = form.querySelector('input[name="prenoms"]');

        if (nomInput) {
            nomInput.addEventListener('input', function () {
                applyLiveTransform(nomInput, toUpperCaseFr);
            });
        }

        if (prenomsInput) {
            prenomsInput.addEventListener('input', function () {
                applyLiveTransform(prenomsInput, toTitleCaseFr);
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

        if (rule.options && !rule.options.includes(value)) {
            return rule.invalidMessage;
        }

        return '';
    }

    function positionErrorMessage(input, messageElement) {
        const form = input.closest('form');
        if (!form || !messageElement) {
            return;
        }
        // Le message est positionné en absolu par rapport au formulaire
        // (voir CSS : la classe .prof-validation doit être en position: relative).
        const formRect = form.getBoundingClientRect();
        const inputRect = input.getBoundingClientRect();
        messageElement.style.left = `${inputRect.left - formRect.left}px`;
        messageElement.style.top = `${inputRect.bottom - formRect.top + 6}px`;
    }

    function showFieldError(input) {
        const form = input.closest('form');
        const messageElement = form ? form.querySelector(`.field-error-message[data-error-for="${input.name}"]`) : null;
        const error = getErrorMessage(input);

        if (messageElement) {
            messageElement.textContent = error;
            if (error) {
                positionErrorMessage(input, messageElement);
            }
            messageElement.style.display = error ? 'block' : 'none';
        }

        if (error) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }

    const forms = document.querySelectorAll('form.prof-validation');
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

        // Recalcule la position des bulles d'erreur visibles si la fenêtre est redimensionnée
        window.addEventListener('resize', () => {
            inputs.forEach((input) => {
                const messageElement = form.querySelector(`.field-error-message[data-error-for="${input.name}"]`);
                if (messageElement && messageElement.style.display === 'block') {
                    positionErrorMessage(input, messageElement);
                }
            });
        });
    });
});