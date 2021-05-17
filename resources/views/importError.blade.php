@extends('app')
@section('title', 'import ficher php')

@section('main')
<h1>Importer un fichier php</h1>
<hr>

<form action="{{ route('getAST') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Veuillez le chemin de votre fichier php (exemple: C:/Files/test.php)</label>
        <br>
        <input id="fichierimport" type="text" name="fichierimport" size="62" autofocus>
        <button type="submit" class="btn btn-primary">Importer</button>
        <br><br>
        <div class="alert alert-danger col-md-4 col-md-offset-4" role="alert" size="50">
          Le chemin que vous avez donné n'existe pas, veuillez réssayer avec le bon chemin.
        </div>

    </div>

</form>
<br><br>
<hr>
<h5>Suite à l'importation de votre fichier php, vous aurez un AST généré par le programme représentant votre fichier.</h5>

@endsection
