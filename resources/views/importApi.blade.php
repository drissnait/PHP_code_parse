@extends('app')
@section('title', 'import ficher php')

@section('main')
<h1>Importer un fichier php pour avoir les détails</h1>
<hr>

<form  method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Veuillez le chemin de votre fichier php (exemple: C:/Files/test.php)</label>
        <!-- <input type="file" name="file" class="form-control-file" name="fichierimport"> -->
        <br>
        <input id="fichierimportAPI" type="text" name="fichierimportAPI" size="50" autofocus><br><br>
        <button type="submit" class="btn btn-primary" href="{{ route('getDetailsCode') }}">Détails du code</button>
    </div>

</form>
<br><br>
<hr>
<h5>Suite à l'importation de votre fichier php, vous aurez tous les détails de votre code php importé.</h5>

@endsection
