<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'Organisme</title>
    <style>
        body { font-family: sans-serif; margin: 40px; background: #f4f6f9; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; position: relative; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input.is-invalid { border-color: #dc3545; background-color: #fff5f5; }
        button { padding: 10px 20px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 20px; border-radius: 4px; margin-right: 10px; }
        .field-error-message {
            display: none;
            position: absolute;
            left: 0;
            top: calc(100% + 0.35rem);
            z-index: 20;
            padding: 0.45rem 0.65rem;
            background: rgba(220, 53, 69, 0.96);
            color: white;
            border-radius: 0.35rem;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            white-space: nowrap;
            pointer-events: none;
        }
        .field-error-message::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 12px;
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent rgba(220, 53, 69, 0.96) transparent;
        }
    </style>
    <script src="{{ asset('js/validation-organisme.js') }}" defer></script>
</head>
<body>

<div class="container">
    <h1>Modifier l'Organisme : {{ $organisme->idorg }}</h1>

    @if($errors->any())
        <div class="alert" style="background:#f8d7da;color:#721c24">{{ implode(' - ', $errors->all()) }}</div>
    @endif

    <form action="{{ route('organismes.update', $organisme->idorg) }}" method="POST" class="organisme-validation">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Désignation</label>
            <input type="text" id="design" name="design" value="{{ old('design', $organisme->design) }}" maxlength="150" class="@error('design') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="design"></span>
        </div>

        <div class="form-group">
            <label>Lieu</label>
            <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $organisme->lieu) }}" maxlength="100" class="@error('lieu') is-invalid @enderror" required>
            <span class="field-error-message" data-error-for="lieu"></span>
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('organismes.index') }}" class="btn-back">Retour</a>
            <button type="submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>

</body>
</html>