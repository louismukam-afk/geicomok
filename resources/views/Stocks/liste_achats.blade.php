@extends('skeleton')
@section('content')
    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />
    <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

    <div class="header-receipt">
        <div class="left">
            <div><strong class="text-uppercase" style="text-align: center;">{{$param->where('nom','=','nom_e')->first()->valeur}}</strong></div>
            <div class="text-compress-1">
                <?php
                $adresse=$param->where('nom','=','adresse')->first();
                $bp=$param->where('nom','=','boite_postale')->first();
                $tel=$param->where('nom','=','telephone')->first();
                $web=$param->where('nom','=','web')->first();
                $email=$param->where('nom','=','email')->first();
                ?>
            </div>
    @if(isset($fournisseur))
        <h3>Fournisseur: <strong>{{$fournisseur->nom}}</strong></h3>
    @endif
    <br>
    <h3>Récapitulatif des achats: Du {{(new DateTime($dd))->format('d/m/Y')}} au {{(new DateTime($df))->format('d/m/Y')}}</h3>


    <div class="col-md-12">


        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th class="numero">Numéro</th>
                <th>Fournisseur</th>
                <th>Montant</th>
                <th>Utilisateur</th>
                <th>Date</th>

            </tr>

            </thead>
            <tbody>
            <?php $i=1;$total=0; ?>
            @foreach($livraisons as $f)
                <?php $total+=$f->total ?>

                <tr>
                    <td>{{$i++}}</td>
                    <td class="numero"><a href="{{route('details_achats',$f->id)}}" ><strong class="@if($f->paye==0) text-danger @endif" ><span class="glyphicon glyphicon-arrow-up"></span> N° {{$f->numero}}</strong></a></td>
                    <td>@if($f->fournisseur){{$f->fournisseur->nom}} @endif</td>
                    <td><strong>{{number_format($f->total,2)}} @lang('main.devise')</strong></td>
                    <td>{{$f->utilisateur->name}}</td>
                    <td>{{(new DateTime($f->date_approv))->format('d-m-Y')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h4 class="alert alert-info">Total: <strong>{{number_format($total,2)}} @lang('main.devise')</strong> </h4>




    </div>

@endsection


@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stock</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
