@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />
        <div class="col-md-12">
            <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>
        </div>

        <div class="col-md-6">
            <h3>Historique: <strong>{{$produit->libelle}}</strong></h3>
            <h3>Quantité actuelle: <strong>{{$produit->stock->quantite}}</strong></h3>

        </div>
        <div class="col-md-6">

            <h3 class="pull-right"> {{$moyenne_produit}} par @if($moyenne_jour>1) {{$moyenne_jour}} @endif jour(s)</h3>

        </div>
    </div>
    <?php $cur_date=''; ?>
    <table class="table">

        @foreach($usages as $u)
            @if((new DateTime($u->date_utilisation))->format('Y-m-d') != $cur_date)
                <?php $cur_date=(new DateTime($u->date_utilisation))->format('Y-m-d') ?>
                <tr class="bg-primary">
                    <td colspan="5">
                        <h4> <strong>{{(new DateTime($u->date_utilisation))->format('d-m-Y')}}</strong></h4>

                    </td>
                </tr>
            @endif

            <tr>
                <td></td>
                <td><strong>{{(new DateTime($u->date_utilisation))->format('H:i')}}</strong></td>
                <td>{{$u->details}}</td>
                <td>Quantite: <strong>{{$u->quantite}}</strong> </td>
                <td>Stock: <strong>{{$u->stock}}</strong> </td>

            </tr>


        @endforeach

    </table>



    <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?produit='.$produit->id.'&', $usages->render()) ); ?></div>


@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection

