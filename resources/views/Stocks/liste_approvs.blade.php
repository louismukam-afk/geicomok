@extends('skeleton')
@section('content')

    @if(isset($magasin))
        <h3>Magasin: <strong>{{$magasin->nom}}</strong></h3>
    @endif
    <br>
    <h3>Récapitulatif des approvisionnements: Du {{(new DateTime($dd))->format('d/m/Y')}} au {{(new DateTime($df))->format('d/m/Y')}}</h3>


    <div class="col-md-12">


        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Numéro</th>
                <th>Magasin</th>
                <th>Montant</th>
                <th>Utilisateur</th>
                <th>Date</th>

            </tr>

            </thead>
            <tbody>
            <?php $i=1;$total=0; ?>
            @foreach($approvisionnements as $f)
                <?php $total+=$f->total ?>

                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="{{route('details_approvs',$f->id)}}" ><strong class="" ><span class="glyphicon glyphicon-arrow-down"></span> N° {{$f->numero}}</strong></a></td>
                    <td>@if($f->magasin){{$f->magasin->nom}} @endif</td>
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
