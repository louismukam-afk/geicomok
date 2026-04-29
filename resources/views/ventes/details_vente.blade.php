@extends('skeleton')
@section('content')


<div class="col-md-12">
    <div class="recu">
        <h3>N° {{$facture->numero}}</h3>
        @if($facture->client) <h4>Client: <strong>{{$facture->client->nom}}</strong></h4> @endif
        <div class="col-md-12">
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
                    <th>Réduction</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
                </thead>

                <tbody>
                <?php $total=0 ;?>

                @foreach($facture->ventes as $v)
                    <tr>
                        <td><strong>{{$v->produit->libelle}}</strong></td>
                        <td>{{$v->prix_unitaire}}</td>
                        <td>{{$v->reduction}}</td>
                        <td>{{$v->quantite}}</td>
                        <td>{{$v->total}}</td>
                        <?php $total+=$v->total ?>
                    </tr>
                @endforeach

                @if($facture->tva>0)
                    <tr>
                        <td colspan="3"><strong>TVA</strong></td>
                        <td colspan="2"><strong class="pull-right">{{$facture->tva}} % ( {{round($total*$facture->tva/100,2)}} @lang('main.devise') ) </strong></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3"><strong>TOTAL</strong></td>
                    <td colspan="2"><strong class="pull-right">{{number_format($facture->total,2)}} @lang('main.devise')</strong></td>
                </tr>


                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px" class="receipt-footer">
            <span class="visa"><strong> Le {{(new DateTime($facture->date_vente))->format('d-m-Y')}} </strong></span>
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

