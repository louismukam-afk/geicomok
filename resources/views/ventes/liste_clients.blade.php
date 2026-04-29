@extends('skeleton')
@section('content')

    <div class="col-md-12">

        <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

        <div class="col-md-12" style="margin-top: 10px">

            <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

        </div>
        <h3 style="text-align: center"> {{$title}}</h3>
        <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Noms</th>
                <th>Téléphone</th>
                <th>Pays</th>
                <th>Ville</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Boite postale</th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($clients as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="#"  ><strong > {{$c->nom}}</strong></a></td>
                    <td>{{$c->telephone}}</td>
                    <td>
                        @if($c->pays)
                                {{$c->pays->nom}}
                            @endif
                    </td>
                    <td>{{$c->ville}}</td>
                    <td>{{$c->email}}</td>
                    <td>{{$c->adresse}}</td>
                    <td>{{$c->boite_postale}}</td>
                </tr>
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
