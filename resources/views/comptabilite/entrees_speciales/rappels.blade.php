@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <div class="text-right" style="margin-bottom: 10px;">
            <a href="{{route('entrees_speciales')}}" class="btn btn-default">Retour</a>
        </div>
        <div class="alert alert-warning">
            Echeances de remboursement a rappeler entre aujourd'hui et les 30 prochains jours.
        </div>
        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>Date echeance</th>
                <th>Entree</th>
                <th>Caisse</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Paye</th>
                <th class="text-right">Reste</th>
                <th>Statut</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($echeances as $echeance)
                <tr>
                    <td>{{(new DateTime($echeance->date_echeance))->format('d-m-Y')}}</td>
                    <td>{{$echeance->entreeSpeciale ? $echeance->entreeSpeciale->numero : ''}}</td>
                    <td>{{$echeance->entreeSpeciale && $echeance->entreeSpeciale->caisse ? $echeance->entreeSpeciale->caisse->nom : ''}}</td>
                    <td class="text-right">{{number_format($echeance->montant, 2)}}</td>
                    <td class="text-right">{{number_format($echeance->montant_paye, 2)}}</td>
                    <td class="text-right">{{number_format($echeance->reste(), 2)}}</td>
                    <td>{{$echeance->statut}}</td>
                    <td>
                        @if($echeance->entreeSpeciale)
                            <a class="btn btn-xs btn-primary" href="{{route('entrees_speciales_show', $echeance->entreeSpeciale->id)}}">Ouvrir</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            @if($echeances->count() == 0)
                <tr><td colspan="8" class="text-center">Aucune echeance a rappeler.</td></tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('comptabilite')}}"><strong>Comptabilite</strong></a></li>
        <li><a href="{{route('entrees_speciales')}}"><strong>Entrees speciales</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
