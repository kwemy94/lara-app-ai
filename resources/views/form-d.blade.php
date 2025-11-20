<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Formulaire D – Informations du BL</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 650px;
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
            margin-bottom: 6px;
            color: #444;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            box-sizing: border-box;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
        }

        .submit-btn {
            width: 100%;
            background: #28a745;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #1e7e34;
        }

        .group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>

</head>

<body>

    <div class="container">
        <h2>Formulaire D – Confirmation des Informations</h2>

        <form action="/form-d/submit" method="POST">
            @csrf

            <!-- Pays de destination (pré-rempli) -->
            <label>Pays de destination</label>
            <input type="text" name="destination" value="{{ $destination_country }}" readonly>

            <!-- Numéro du BL -->
            <label>Numéro du BL</label>
            <input type="text" name="numero_bl" placeholder="Ex : MSCU1234567" required>

            <!-- Nom destinataire -->
            <label>Nom du destinataire</label>
            <input type="text" name="nom_destinataire" placeholder="Nom complet" required>

            <!-- Adresse destinataire -->
            <label>Adresse du destinataire</label>
            <textarea name="adresse_destinataire" rows="3" placeholder="Adresse complète" required></textarea>

            <div class="group">
                <div>
                    <label>Date d'expédition</label>
                    <input type="date" name="date_expedition" required>
                </div>

                <div>
                    <label>Date d'arrivée estimée</label>
                    <input type="date" name="date_arrivee">
                </div>
            </div>

            <label>Description de la marchandise</label>
            <textarea name="description" rows="3" placeholder="Ex : Vêtements, appareils électroniques..." required></textarea>

            <button type="submit" class="submit-btn">
                Enregistrer
            </button>
        </form>
    </div>

</body>

</html>
