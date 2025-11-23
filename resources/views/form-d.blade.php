@extends('layouts.layout')

@section('content')
    <h2>Formulaire D – Confirmation des Informations</h2>

    <form action="/form-d/submit" method="POST">
        @csrf

        <!-- Pays de destination (pré-rempli) -->
        <label>Pays de destination</label>
        <input type="text" name="destination" value="{{ $destinationCountry }}" readonly>

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
@endsection
