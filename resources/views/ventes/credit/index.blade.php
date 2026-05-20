@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <h3>Bons de credit client</h3>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Nouveau bon de credit</strong></div>
            <div class="panel-body">
                <form action="{{route('bons_credit_store')}}" method="post" class="row">
                    {{csrf_field()}}
                    <div class="col-md-3">
                        <label>Client</label>
                        <select name="client" class="form-control" required>
                            @foreach($clients as $client)
                                <option value="{{$client->id}}">{{$client->nom}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Montant credit</label>
                        <input type="number" step="0.01" min="1" name="montant_credit" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Date debut</label>
                        <input type="date" name="date_debut" value="{{date('Y-m-d')}}" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Date fin</label>
                        <input type="date" name="date_fin" value="{{date('Y-m-d')}}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Observation</label>
                        <input type="text" name="observations" class="form-control">
                    </div>
                    <div class="col-md-3" style="margin-top: 10px;">
                        <label>Debut remboursement</label>
                        <input type="date" name="date_debut_remboursement" class="form-control">
                    </div>
                    <div class="col-md-3" style="margin-top: 10px;">
                        <label>Fin remboursement</label>
                        <input type="date" name="date_fin_remboursement" class="form-control">
                    </div>
                    <div class="col-md-12" style="margin-top: 12px;">
                        <button class="btn btn-primary" type="submit">Creer le bon</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>Numero</th>
                <th>Client</th>
                <th>Periode</th>
                <th class="text-right">Credit</th>
                <th class="text-right">Consomme</th>
                <th class="text-right">Disponible</th>
                <th class="text-right">Rembourse</th>
                <th class="text-right">Reste a rembourser</th>
                <th>Statut</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($bons as $bon)
                <tr>
                    <td>{{$bon->numero}}</td>
                    <td>{{$bon->client ? $bon->client->nom : ''}}</td>
                    <td>{{(new DateTime($bon->date_debut))->format('d-m-Y')}} au {{(new DateTime($bon->date_fin))->format('d-m-Y')}}</td>
                    <td class="text-right">{{number_format($bon->montant_credit, 0, ',', ' ')}}</td>
                    <td class="text-right">{{number_format($bon->montantConsomme(), 0, ',', ' ')}}</td>
                    <td class="text-right">{{number_format($bon->soldeDisponible(), 0, ',', ' ')}}</td>
                    <td class="text-right">{{number_format($bon->montantRembourse(), 0, ',', ' ')}}</td>
                    <td class="text-right">{{number_format($bon->resteARembourser(), 0, ',', ' ')}}</td>
                    <td>
                        @if($bon->statut == \GEICOM\BonCredit::STATUT_ACTIF)
                            <span class="label label-success">Actif</span>
                        @elseif($bon->statut == \GEICOM\BonCredit::STATUT_INACTIF)
                            <span class="label label-default">Inactif</span>
                        @else
                            <span class="label label-danger">Cloture</span>
                        @endif
                    </td>
                    <td style="min-width: 130px;">
                        <a href="{{route('bons_credit_show', $bon->id)}}" class="btn btn-xs btn-primary">Ouvrir</a>
                        <form action="{{route('bons_credit_delete', $bon->id)}}" method="post" style="display:inline;" onsubmit="return confirm('Supprimer ce bon de credit ?');">
                            {{csrf_field()}}
                            <button type="submit" class="btn btn-xs btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">{{$bons->render()}}</div>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('ventes')}}"><strong>Ventes</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
