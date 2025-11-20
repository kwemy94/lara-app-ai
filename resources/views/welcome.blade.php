<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire C – Upload Document</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 550px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #444;
        }

        .file-input {
            border: 2px dashed #aab;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            color: #777;
            transition: 0.3s;
        }

        .file-input:hover {
            background: #eef1f8;
            border-color: #667;
        }

        input[type="file"] {
            display: none;
        }

        .submit-btn {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #0056d2;
        }

        .file-name {
            margin-top: 10px;
            text-align: center;
            color: #333;
            font-style: italic;
            display: none;
        }
    </style>

    <script>
        function showFileName(input) {
            const label = document.getElementById("fileName");
            if (input.files.length > 0) {
                label.style.display = "block";
                label.textContent = "Fichier sélectionné : " + input.files[0].name;
            }
        }
    </script>

</head>

<body>

    <div class="container">
        <h2>Uploader BL</h2>

        <form action="/form-c/upload" method="POST" enctype="multipart/form-data">
            <!-- CSRF pour Laravel -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <label>Choisir un fichier :</label>

            <label class="file-input">
                Cliquez ici pour sélectionner un fichier
                <input type="file" name="document" required accept=".pdf,.jpg,.jpeg,.png,.webp,.docx"
                    onchange="showFileName(this)">
            </label>

            <div id="fileName" class="file-name"></div>

            <button type="submit" class="submit-btn">Envoyer</button>
        </form>
    </div>

</body>

</html>
