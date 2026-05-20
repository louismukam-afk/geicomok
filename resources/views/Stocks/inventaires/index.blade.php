@extends('skeleton')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>Inventaires</h3>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><strong>Nouvel inventaire</strong></div>
        <div class="panel-body">
            <form action="{{route('inventaires_store')}}" method="post" class="row">
                {{csrf_field()}}
                <div class="col-md-3">
                    <label>Date de debut</label>
                    <input type="date" name="date_debut" value="{{$date_debut}}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Date de fin</label>
                    <input type="date" name="date_fin" value="{{$date_fin}}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Categorie</label>
                    <select name="categorie" class="form-control">
                        <option value="0">Toutes les categories</option>
                        @foreach($categories as $categorie)
                            <option value="{{$categorie->id}}" @if($categorie_id == $categorie->id) selected @endif>{{$categorie->libelle}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Observation</label>
                    <input type="text" name="observations" class="form-control">
                </div>
                <div class="col-md-12" style="margin-top: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-plus"></span> Creer l'inventaire
                    </button>
                    <a href="{{route('inventaires_rapport')}}" class="btn btn-default">
                        <span class="glyphicon glyphicon-list-alt"></span> Rapport
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><strong>Inventaires existants</strong></div>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Periode</th>
                <th>Statut</th>
                <th>Observation</th>
                <th>Date creation</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($inventaires as $inventaire)
                <tr>
                    <td>{{$inventaire->id}}</td>
                    <td>{{(new DateTime($inventaire->date_debut))->format('d-m-Y')}} au {{(new DateTime($inventaire->date_fin))->format('d-m-Y')}}</td>
                    <td>
                        @if($inventaire->statut == \GEICOM\Inventaire::STATUT_CONSOLIDE)
                            <span class="label label-success">Consolide</span>
                        @else
                            <span class="label label-warning">Brouillon</span>
                        @endif
                    </td>
                    <td>{{$inventaire->observations}}</td>
                    <td>{{(new DateTime($inventaire->created_at))->format('d-m-Y H:i')}}</td>
                    <td>
                        <a href="{{route('inventaires_show', $inventaire->id)}}" class="btn btn-xs btn-primary">
                            <span class="glyphicon glyphicon-eye-open"></span> Ouvrir
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">{{$inventaires->render()}}</div>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
