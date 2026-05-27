@extends('skeleton')
@section('content')
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Importer les produits</strong>
            </div>
            <div class="panel-body">
                @if(session('import_result'))
                    <div class="alert alert-info">
                        <p><strong>{{ session('import_result.created') }}</strong> produit(s) importe(s).</p>
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
                    <a href="{{ route('template_produit_import') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-download"></span> Telecharger le modele Excel
                    </a>
                </p>

                <form method="POST" action="{{ route('import_produit_store') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label for="fichier">Fichier Excel ou CSV</label>
                        <input type="file" id="fichier" name="fichier" class="form-control" accept=".xlsx,.csv,.txt" required>
                    </div>

                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-upload"></span> Importer
                    </button>
                    <a href="{{ route('produit_management') }}" class="btn btn-default">Retour</a>
                </form>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Format attendu</strong>
            </div>
            <div class="panel-body">
                <p>Le fichier doit etre au format Excel (.xlsx) ou CSV avec une premiere ligne d'en-tetes.</p>
                <pre>id;libelle;description;quantite_minimale;reference;id_categorie;categorie;prix;prix_achat;prix_minimum;prix_semi_gros;prix_comptoir;prix_gros
;Savon;Savon parfum citron;0;REF001;;Cosmetiques;1000;700;0;950;980;900
;Jus orange;Bouteille 50cl;0;REF002;;Boissons;500;350;0;475;490;450</pre>
                <p>Colonnes obligatoires : <strong>libelle</strong> et <strong>categorie</strong> ou <strong>id_categorie</strong>.</p>
                <p>La colonne <strong>id</strong> est presente dans le modele pour correspondre a la table, mais elle est ignoree pendant l'import.</p>
                <p>Les categories inexistantes renseignees par nom sont creees automatiquement. Les produits deja existants sont ignores.</p>

                @if(count($categories) > 0)
                    <h4>Categories disponibles</h4>
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libelle</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $categorie)
                            <tr>
                                <td>{{ $categorie->id }}</td>
                                <td>{{ $categorie->libelle }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li><a href="{{route('produit_management')}}"><strong>Gestion produits</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
