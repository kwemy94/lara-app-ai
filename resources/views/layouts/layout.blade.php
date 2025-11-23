<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>BESC TEST</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 220px;
            background: #2c3e50;
            height: 100vh;
            padding-top: 20px;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .sidebar ul li:hover {
            background: #34495e;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #ecf0f1;
        }

        /* --- Container --- */
        .container {
            max-width: 550px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-left: 260px;
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

    <!-- MENU LATERAL -->
    <div class="sidebar">
        <h3>Menu</h3>
        <ul>
            <li>
                <a href="{{ route('home') }}">OCR SPACE</a>
            </li>
            <li>
                <a href="{{ route('taggun.index') }}">OCR TAGGUN</a>
            </li>
            <li>
                <a href="#">API 4 AI</a>
            </li>
        </ul>
    </div>

    <!-- CONTENU PRINCIPAL -->
    <div class="container">
        @yield('content')
    </div>

</body>

</html>