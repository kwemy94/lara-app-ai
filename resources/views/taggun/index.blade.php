@extends('layouts.layout')

@section('content')
    <h2>TAGGUN OCR</h2>

    <form action="{{ route('taggun.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Choisir un fichier :</label>

        <label class="file-input">
            Cliquez ici pour sélectionner un fichier
            <input type="file" name="document" required accept=".pdf,.jpg,.jpeg,.png,.webp,.docx"
                onchange="showFileName(this)">
        </label>

        <div id="fileName" class="file-name"></div>

        <button type="submit" class="submit-btn">Envoyer</button>
    </form>
@endsection
