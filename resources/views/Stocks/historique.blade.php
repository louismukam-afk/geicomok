@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />
        <div class="col-md-12">
            <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>
        </div>

        <div class="col-md-6">
            <h3>Historique: <strong>{{$produit->libelle}}</strong></h3>
            <h3>Quantité actuelle: <strong>{{number_format($stock_calcule, 0, ',', ' ')}}</strong></h3>
            @if($stock_calcule != $stock_enregistre)
                <div class="alert alert-warning" style="padding: 8px 12px;margin-bottom: 10px;">
                    Stock enregistré en base: <strong>{{number_format($stock_enregistre, 0, ',', ' ')}}</strong>.
                    Stock recalculé par l'historique: <strong>{{number_format($stock_calcule, 0, ',', ' ')}}</strong>.
                    <form action="{{route('sync_historique_stock')}}" method="post" style="display: inline-block;margin-left: 10px;">
                        {{csrf_field()}}
                        <input type="hidden" name="produit" value="{{$produit->id}}">
                        <button type="submit" class="btn btn-xs btn-warning">Synchroniser le stock</button>
                    </form>
                </div>
            @endif

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
                    <td colspan="6">
                        <h4> <strong>{{(new DateTime($u->date_utilisation))->format('d-m-Y')}}</strong></h4>

                    </td>
                </tr>
            @endif

            <tr @if($u->is_inventaire) class="warning" @endif>
                <td></td>
                <td><strong>{{(new DateTime($u->date_utilisation))->format('H:i')}}</strong></td>
                <td>
                    @if($u->is_inventaire)
                        <span class="label label-warning">Consolidation inventaire</span><br>
                    @endif
                    {{$u->details}}
                </td>
                <td>
                    Mouvement:
                    @if($u->sens == 'sortie')
                        <strong class="text-danger">-{{$u->quantite}}</strong>
                    @else
                        <strong class="text-success">+{{$u->quantite}}</strong>
                    @endif
                </td>
                <td>Stock avant: <strong>{{number_format($u->stock_avant, 0, ',', ' ')}}</strong> </td>
                <td>
                    Stock restant: <strong>{{number_format($u->stock_restant, 0, ',', ' ')}}</strong>
                    @if($u->stock_ecart)
                        <br><small class="text-warning">Stock enregistré: {{number_format($u->stock_enregistre, 0, ',', ' ')}}</small>
                    @endif
                </td>

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

