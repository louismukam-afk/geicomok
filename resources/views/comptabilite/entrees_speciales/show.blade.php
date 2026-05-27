@extends('skeleton')
@section('content')
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">Operation enregistree.</div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <h3>{{$entree->numero}} - {{isset($types[$entree->type]) ? $types[$entree->type] : $entree->type}}</h3>
                <p>
                    Caisse: <strong>{{$entree->caisse ? $entree->caisse->nom : ''}}</strong> |
                    Date: <strong>{{(new DateTime($entree->date_apport))->format('d-m-Y')}}</strong> |
                    Statut: <strong>{{$entree->statut}}</strong>
                </p>
            </div>
            <div class="col-md-4 text-right" style="padding-top: 20px;">
                <a href="{{route('entrees_speciales')}}" class="btn btn-default">Retour</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"><div class="alert alert-info">Montant: <strong>{{number_format($entree->montant, 2)}}</strong></div></div>
            <div class="col-md-3"><div class="alert alert-success">Rembourse: <strong>{{number_format($entree->montantRembourse(), 2)}}</strong></div></div>
            <div class="col-md-3"><div class="alert alert-warning">Reste: <strong>{{number_format($entree->resteARembourser(), 2)}}</strong></div></div>
            <div class="col-md-3"><div class="alert alert-default">Source: <strong>{{$entree->source_nom}}</strong></div></div>
        </div>
        @if($entree->source_telephone)
            <div class="alert alert-info">Telephone source pret: <strong>{{$entree->source_telephone}}</strong></div>
        @endif

        @if($entree->type == \GEICOM\EntreeSpeciale::TYPE_PRET)
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Rembourser le pret</strong></div>
                <div class="panel-body">
                    <form method="post" action="{{route('entrees_speciales_remboursement', $entree->id)}}">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-3">
                                <label>Echeance</label>
                                <select name="id_echeance" class="form-control">
                                    <option value="">Sans echeance precise</option>
                                    @foreach($entree->echeances as $echeance)
                                        @if($echeance->statut != 'paye')
                                            <option value="{{$echeance->id}}">{{(new DateTime($echeance->date_echeance))->format('d-m-Y')}} - reste {{number_format($echeance->reste(), 2)}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Caisse centrale</label>
                                <select name="id_caisse" class="form-control" required>
                                    @foreach($caisses as $caisse)
                                        <option value="{{$caisse->id}}">{{$caisse->nom}} - solde {{number_format($caisse->solde(), 2)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Date</label>
                                <input type="date" name="date_remboursement" value="{{date('Y-m-d')}}" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label>Montant</label>
                                <input type="number" step="0.01" min="1" name="montant" class="form-control" required>
                            </div>
                            <div class="col-md-2" style="padding-top: 25px;">
                                <button class="btn btn-success" type="submit">Rembourser</button>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 10px;">
                            <label>Observations</label>
                            <input type="text" name="observations" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h4>Echeances</h4>
                <table class="table table-bordered table-condensed">
                    <thead><tr><th>Date</th><th class="text-right">Montant</th><th class="text-right">Paye</th><th>Statut</th></tr></thead>
                    <tbody>
                    @foreach($entree->echeances as $echeance)
                        <tr>
                            <td>{{(new DateTime($echeance->date_echeance))->format('d-m-Y')}}</td>
                            <td class="text-right">{{number_format($echeance->montant, 2)}}</td>
                            <td class="text-right">{{number_format($echeance->montant_paye, 2)}}</td>
                            <td>{{$echeance->statut}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h4>Remboursements</h4>
                <table class="table table-bordered table-condensed">
                    <thead><tr><th>Numero</th><th>Date</th><th>Caisse</th><th class="text-right">Montant</th></tr></thead>
                    <tbody>
                    @foreach($entree->remboursements as $r)
                        <tr>
                            <td>{{$r->numero}}</td>
                            <td>{{(new DateTime($r->date_remboursement))->format('d-m-Y')}}</td>
                            <td>{{$r->caisse ? $r->caisse->nom : ''}}</td>
                            <td class="text-right">{{number_format($r->montant, 2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('comptabilite')}}"><strong>Comptabilite</strong></a></li>
        <li><a href="{{route('entrees_speciales')}}"><strong>Entrees speciales</strong></a></li>
        <li class="active"><strong>{{$entree->numero}}</strong></li>
    </ol>
@endsection
