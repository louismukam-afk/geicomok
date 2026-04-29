@extends('skeleton')
@section('content')

    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />
    <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>


    <div class="col-md-12">
    <div class="recu">
        <h3>N° {{$livraison->numero}}</h3>
        @if($livraison->fournisseur) <h4>Client: <strong>{{$livraison->fournisseur->nom}}</strong></h4> @endif



        <div class="col-md-12">
            <div class="pull-right">
                <small class="text-gray"> Utilisateur: <strong>{{$livraison->utilisateur->name}}</strong></small>
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

                @foreach($livraison->achats as $v)
                    <tr>
                        <td><strong>{{$v->produit->libelle}}</strong></td>
                        <td>{{$v->prix}}</td>
                        <td>{{$v->quantite}}</td>
                        <td>{{$v->total}}</td>
                        <?php $total+=$v->total ?>
                    </tr>
                @endforeach

                @if($livraison->tva>0)
                    <tr>
                        <td colspan="3"><strong>TVA</strong></td>
                        <td colspan="2"><strong class="pull-right">{{$livraison->tva}} % ( {{round($total*$livraison->tva/100,2)}} @lang('main.devise') ) </strong></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3"><strong>TOTAL</strong></td>
                    <td colspan="2"><strong class="pull-right">{{number_format($livraison->total,2)}} @lang('main.devise')</strong></td>
                </tr>


                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px" class="receipt-footer">
            <span class="visa"><strong> Le {{(new DateTime($livraison->date_approv))->format('d-m-Y')}} </strong></span>
        </div>

    </div>
</div>


@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection

