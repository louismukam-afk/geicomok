@extends('skeleton')
@section('content')
    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

    <div class="btn-group" style="margin-top: 10px">
        @if($facture->total>$facture->verse)
            <a href="#" class="btn btn-danger "><span class="glyphicon glyphicon-remove"></span> Facture non payée</a>
        @else
            <a href="#" class="btn btn-success"><span class="glyphicon glyphicon-ok-circle"></span> Facture Payée</a>


        @endif
            <a href="{{route('new_vente')}}"><H3 class="glyphicon glyphicon-apple">Nouvelle vente</H3></a>
        <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

    </div>



<div class="col-md-12">
    <div class="recu">
        <h3 style="text-align: center;font-weight: 800">FACTURE</h3>

        <div class="header-receipt">
            <div class="left">
                <div><strong class="text-uppercase">{{$param->where('nom','=','nom_e')->first()->valeur}}</strong></div>
                <div class="text-compress-1">
                    <?php
                        $adresse=$param->where('nom','=','adresse')->first();
                    $bp=$param->where('nom','=','boite_postale')->first();
                    $tel=$param->where('nom','=','telephone')->first();
                    $web=$param->where('nom','=','web')->first();
                    $email=$param->where('nom','=','email')->first();


                    ?>
                    <div><strong class="text-uppercase traduction">{{$boutique->nom}}</strong></div>
                    <div><strong class="text-uppercase traduction">{{$boutique->adresse?$boutique->adresse:($adresse?$adresse->valeur:'')}}</strong></div>
                    <div><strong class="text-uppercase traduction">{{$bp?$bp->valeur:''}}</strong></div>

                    <div><strong class="text-uppercase traduction">{{$boutique->telephone?$boutique->telephone:($tel?$tel->valeur:'')}}</strong></div>
                    <div><strong class="traduction">{{$web?$web->valeur:''}}</strong></div>
                        <div><strong class="traduction">{{$boutique->email?$boutique->email:($email?$email->valeur:'')}}</strong></div>

                </div>

            </div>
            <div class="right">
                <div><strong class="text-uppercase" style="color: firebrick!important;"> N° {{$facture->numero}} </strong></div>
                <div class="text-compress-1">
                    @if($facture->client)
                    <div ><strong class="text-uppercase traduction">   {{$facture->client->nom}} </strong></div>
                    <div ><strong class="text-uppercase traduction">   {{$facture->client->boite_postale}} </strong></div>
                    <div ><strong class="text-uppercase traduction">   {{$facture->client->adresse}} </strong></div>
                    <div ><strong class="text-uppercase traduction">   {{$facture->client->telephone}} </strong></div>
                    @endif
                </div>



            </div>
        </div>


        <div class="col-md-12">
            <span class="pull-left"><strong> Date: {{(new DateTime($facture->date_vente))->format('d-m-Y')}} </strong></span>

            <div class="pull-right">
                <small class="text-gray"> Vendeur: <strong>{{$facture->vendeur->name}}</strong></small>
            </div>
        </div>
        <div class="body-receipt" >
            <table class="table table-striped ">
                <thead class="thead-inverse">
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
                </thead>

                <tbody>
                <?php $total=0 ;?>

                @foreach($facture->ventes as $v)
                    <tr>
                        <td><strong>{{$v->produit->libelle}}</strong></td>
                        <td>{{$v->prix_unitaire-$v->reduction}}</td>
                        <td>{{$v->quantite}}</td>
                        <td>{{$v->total}}</td>
                        <?php $total+=$v->total ?>
                    </tr>
                @endforeach

                @if($facture->tva>0)
                    <tr>
                        <td colspan="2"><strong>TVA</strong></td>
                        <td colspan="2"><strong class="pull-right">{{$facture->tva}} % ( {{round($total*$facture->tva/100,2)}} @lang('main.devise') ) </strong></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td colspan="2"><strong class="pull-right">{{number_format($facture->total,2)}} @lang('main.devise')</strong></td>
                </tr>


                </tbody>
            </table>
        </div>
        <div style="margin-top: 10px;padding-left: 10px; font-size: 11px" class="receipt-footer">

                <div><strong>Numero facture Manuel:</strong> {{$facture->numfacture_manuel}}</div>
                <div><strong>Reduction générale :</strong> {{number_format($facture->reduction,2)}} @lang('main.devise')</div>
                <div><strong>@lang('main.m_verse') :</strong> {{number_format($facture->verse,2)}} @lang('main.devise')</div>
                <div ><strong>@lang('main.reste_ap') :</strong> {{number_format((($facture->verse - $facture->total) * -1),2)}} @lang('main.devise')</div>
        </div>

    </div>
</div>


@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('ventes')}}"><strong>Ventes</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection

