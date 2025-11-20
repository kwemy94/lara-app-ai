<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire A - Upload Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <div class="col-md-6 mx-auto">
        <div class="card p-4">
            <h3 class="text-center mb-3">📄 Formulaire A</h3>
            <p class="text-center text-muted">Upload des documents pour analyse OCR</p>

            <form action="{{ route('formA.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Document 1 *</label>
                    <input type="file" name="documents[]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Document 2 *</label>
                    <input type="file" name="documents[]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Document 3 *</label>
                    <input type="file" name="documents[]" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100 mt-3">📤 Soumettre</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
