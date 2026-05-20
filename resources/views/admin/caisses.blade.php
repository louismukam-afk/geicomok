@extends('skeleton')
@section('content')
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">Operation effectuee avec succes.</div>
        @endif

        <form method="POST" action="{{route('store_caisse')}}" class="form-inline" style="margin-bottom: 15px">
            {{csrf_field()}}
            <div class="form-group">
                <input type="text" name="nom" class="form-control" placeholder="Nom de la caisse" required>
            </div>
            <div class="form-group">
                <select name="type" class="form-control" required>
                    @foreach($types as $value => $label)
                        <option value="{{$value}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Creer</button>
            <a href="{{route('caisses_affectations')}}" class="btn btn-warning">Affecter une caisse</a>
            <a href="{{route('caisses_transfert')}}" class="btn btn-info">Transfert caisse</a>
            <a href="{{route('ma_caisse_etat')}}" class="btn btn-success">Etat de ma caisse</a>
        </form>

        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Type</th>
                <th>Solde</th>
                <th>Utilisateurs affectes</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($caisses as $caisse)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong>{{$caisse->nom}}</strong></td>
                    <td>{{$types[$caisse->type]}}</td>
                    <td><strong>{{number_format($caisse->solde(), 2)}} @lang('main.devise')</strong></td>
                    <td>
                        @foreach($caisse->users as $user)
                            <span class="label label-primary">{{$user->name}}</span>
                        @endforeach
                    </td>
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
