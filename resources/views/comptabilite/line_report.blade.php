@extends('skeleton')
@section('content')
    <div class="col-md-10">
        @include('perso.functions')
        
        <div class="row">
            <div class="col-md-12">
                <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimer</a>
            </div>
        </div>
        <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">
        <link rel="stylesheet" media="print" href="{{URL::asset('css/facture.css')}}">

        <h3>Ligne budgétaire: <strong>{{ $line->libelle_ligne }}</strong> || {{ toDateString($line->date_debut) }} - {{ toDateString($line->date_fin) }}</h3>
        <table class="table table-striped table-bordered table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px"></caption>
            <th>#</th>
            <th>Numero de compte</th>
            <th>Nom</th>
            <th>Total</th>
            <th>Montant réalisé</th>
            <th>Reste</th>

            </thead>
            <tbody>
            <?php $i=1; $totalSum = 0; $rSum =0 ?>
            @foreach($lineData as $c)
            <?php $totalSum += $c->total_amount; $rSum += $c->realized_amount ?>

                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$c->numero_compte}}</td>
                    <td><strong> {{ $c->libelle }} </strong></td>
                    <td>{{number_format($c->total_amount, 0, '.', ' ')}} FCFA</td>
                    <td>{{number_format($c->realized_amount, 0, '.', ' ')}} FCFA</td>
                    <td>{{number_format($c->total_amount - $c->realized_amount, 0, '.', ' ')}} FCFA</td>

                </tr>
            @endforeach
                <tr>
                    <td colspan="3">Total</td>
                    <td>{{number_format($totalSum, 0, '.', ' ')}} FCFA</td>
                    <td>{{number_format($rSum, 0, '.', ' ')}} FCFA</td>
                    <td>{{number_format($totalSum - $rSum, 0, '.', ' ')}} FCFA</td>
                </tr>
            </tbody>
        </table>

        <h5>Date: {{ date('d-m-Y') }}</h5>

    <!--    -->
    </div>
@endsection

        @section('breadcrumb')
            <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
                <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
                <li><a href="{{route('comptabilite')}}"><strong>Comptabilite</strong></a></li>
                <li><a href="{{route('index_ligne')}}"><strong>Lignes Budgétaires</strong></a></li>
                <li class="active">Lignes Budgétaires</li>
            </ol>
        @endsection
