@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <form method="GET" action="{{route('ma_caisse_etat')}}" class="form-inline" style="margin-bottom: 15px">
            <div class="form-group">
                <label>Date debut</label>
                <input type="date" name="dd" class="form-control" value="{{$dd}}">
            </div>
            <div class="form-group">
                <label>Date fin</label>
                <input type="date" name="df" class="form-control" value="{{$df}}">
            </div>
            <button class="btn btn-primary">Afficher</button>
        </form>

        <div class="alert alert-info">
            <strong>Ventes:</strong> {{number_format($total_ventes, 2)}} @lang('main.devise') |
            <strong>Achats:</strong> {{number_format($total_achats, 2)}} @lang('main.devise') |
            <strong>Entrees:</strong> {{number_format($total_entrees, 2)}} @lang('main.devise') |
            <strong>Sorties:</strong> {{number_format($total_sorties, 2)}} @lang('main.devise') |
            <strong>Reste periode:</strong> {{number_format($total_entrees - $total_sorties, 2)}} @lang('main.devise')
        </div>

        <h4>Mes caisses</h4>
        @foreach($caisses as $caisse)
            <span class="label label-primary" style="font-size: 13px">{{$caisse->nom}}: {{number_format($caisse->solde(), 2)}}</span>
        @endforeach

        <table class="table table-striped table-condensed" style="margin-top: 15px">
            <thead>
            <tr>
                <th>Date</th>
                <th>Caisse</th>
                <th>Type</th>
                <th>Source</th>
                <th>Montant</th>
                <th>Solde avant</th>
                <th>Solde apres</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mouvements as $m)
                <tr>
                    <td>{{(new DateTime($m->date_mouvement))->format('d-m-Y H:i')}}</td>
                    <td>{{$m->caisse ? $m->caisse->nom : ''}}</td>
                    <td>{{$m->type}}</td>
                    <td>{{$m->source_type}}</td>
                    <td>{{number_format($m->montant, 2)}}</td>
                    <td>{{number_format($m->solde_avant, 2)}}</td>
                    <td><strong>{{number_format($m->solde_apres, 2)}}</strong></td>
                    <td>{{$m->description}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
