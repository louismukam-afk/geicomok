@extends('skeleton')
@section('content')
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Importer les categories de produits</strong>
            </div>
            <div class="panel-body">
                @if(session('import_result'))
                    <div class="alert alert-info">
                        <p><strong>{{ session('import_result.created') }}</strong> categorie(s) importee(s).</p>
                        <p><strong>{{ session('import_result.skipped') }}</strong> ligne(s) ignoree(s).</p>
                        @if(count(session('import_result.errors')) > 0)
                            <ul>
                                @foreach(session('import_result.errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p>
                    <a href="{{ route('template_categorie_import') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-download"></span> Telecharger le modele Excel
                    </a>
                </p>

                <form method="POST" action="{{ route('import_categorie_store') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label for="fichier">Fichier Excel ou CSV</label>
                        <input type="file" id="fichier" name="fichier" class="form-control" accept=".xlsx,.csv,.txt" required>
                    </div>

                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-upload"></span> Importer
                    </button>
                    <a href="{{ route('categorie_management') }}" class="btn btn-default">Retour</a>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Format attendu</strong>
            </div>
            <div class="panel-body">
                <p>Le fichier doit etre au format Excel (.xlsx) ou CSV avec une premiere ligne d'en-tetes.</p>
                <pre>id;libelle
;Boissons
;Epicerie</pre>
                <p>La colonne <strong>id</strong> est presente dans le modele pour correspondre a la table, mais elle est ignoree pendant l'import.</p>
                <pre>libelle
Boissons
Epicerie
Cosmetiques</pre>
                <p>Les separateurs virgule et point-virgule sont acceptes. Les categories deja existantes sont ignorees.</p>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li><a href="{{route('categorie_management')}}"><strong>Gestion des categories</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
