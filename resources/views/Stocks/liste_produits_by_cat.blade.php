@extends('skeleton')
@section('content')
    @include('perso.functions')

    <div class="col-md-12">

        <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

        <div  style="margin-top: 10px">

            <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

        </div>

        <h3 style="text-align: center">{{$title}}</h3>
        <table   class="table  table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Libellé</th>
                <th>Prix de vente</th>
                <th>Prix d'achat</th>
            </tr>

            </thead>
            <tbody>
            @foreach($items as $p)
                <?php $i=1; ?>

                <tr >
                    <td class="text-primary text-uppercase" colspan="5" style="font-size: 14px;font-weight: 600; text-align: center">
                        {{$p->libelle}} : {{$p->produits->count()}}
                    </td>
                </tr>
                @foreach($p->produits as $pro)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$pro->reference}}</td>
                        <td><a href="#"  ><strong > {{$pro->libelle}}</strong></a></td>


                        <td>{{$pro->prix}} @lang('main.devise')</td>
                        <td>{{$pro->prix_achat}} @lang('main.devise')</td>
                    </tr>
                @endforeach

            @endforeach
            </tbody>
        </table>

    </div>



@endsection




@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
