<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire B - Pré-remplissage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef1f5;
        }
        .card {
            border-radius: 14px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <div class="col-md-7 mx-auto">
        <div class="card p-4">
            <h3 class="text-center mb-3">📝 Formulaire B</h3>
            <p class="text-center text-muted">Données extraites automatiquement</p>

            <form action="/formB-submit" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ $data['nom'] ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Numéro</label>
                    <input type="text" name="numero" class="form-control" value="{{ $data['numero'] ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="text" name="date" class="form-control" value="{{ $data['date'] ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" rows="3">{{ $data['adresse'] ?? '' }}</textarea>
                </div>

                <button class="btn btn-success w-100 mt-3">✅ Valider</button>

            </form>
        </div>
    </div>

</div>

</body>
</html>
