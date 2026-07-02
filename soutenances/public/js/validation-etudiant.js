document.addEventListener('DOMContentLoaded', function () {
    const validationRules = {
        matricule: {
            required: 'Le matricule est obligatoire.',
            pattern: /^[^\s]+$/, // pas d'espaces
            maxLength: 50,
            invalidMessage: 'Le matricule ne doit pas contenir d'espaces et ne doit pas dépasser 50 caractères.'
        },
        nom: {
            required: 'Le nom est obligatoire.',
            pattern: /^[a-zA-ZÀ-ÿ\s\-']+$/,
            maxLength: 100,
            invalidMessage: 'Le nom ne doit contenir que des lettres, espaces, tirets et apostrophes.'
        },
        prenoms: {
            required: 'Les prénoms sont obligatoires.',
            pattern: /^[a-zA-ZÀ-ÿ\s\-']+$/,
            maxLength: 100,
            invalidMessage: 'Les prénoms ne doivent contenir que des lettres, espaces, tirets et apostrophes.'
        },
        niveau: {
            required: 'Le niveau est obligatoire.',
            options: ['L1', 'L2', 'L3', 'M1', 'M2'],
            invalidMessage: 'Le niveau doit être L1, L2, L3, M1 ou M2.'
        },
        parcours: {
            required: 'Le parcours est obligatoire.',
            options: ['GB', 'SR', 'IG'],
            invalidMessage: 'Le parcours doit être GB, SR ou IG.'
        },
        adr_email: {
            required: 'L\'adresse email est obligatoire.',
            type: 'email',
            maxLength: 150,
            invalidMessage: 'L\'adresse email doit être valide et ne doit pas dépasser 150 caractères.'
        }
    };

    function getErrorMessage(input) {
        const name = input.name;
        const rule = validationRules[name];
        if (!rule) {
            return '';
        }

        const value = input.value.trim();

        if (rule.required && value.length === 0) {
            return rule.required;
        }

        if (rule.maxLength && value.length > rule.maxLength) {
            return rule.invalidMessage;
        }

        if (rule.type === 'email') {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(value)) {
                return rule.invalidMessage;
            }
        }

        if (rule.pattern && !rule.pattern.test(value)) {
            return rule.invalidMessage;
        }

        if (rule.options && !rule.options.includes(value)) {
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
            messageElement.style.color = '#dc3545';
            messageElement.style.fontSize = '0.875rem';
            messageElement.style.display = error ? 'block' : 'none';
        }

        if (error) {
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    }

    const forms = document.querySelectorAll('form.student-validation');
    if (!forms.length) {
        return;
    }

    forms.forEach((form) => {
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