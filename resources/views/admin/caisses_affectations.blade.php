@extends('skeleton')
@section('content')
    <div class="col-md-8">
        @if(session('success'))
            <div class="alert alert-success">Affectation enregistree.</div>
        @endif

        <form method="POST" action="{{route('store_caisse_affectation')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label>Caisse</label>
                <select name="id_caisse" class="form-control" required>
                    @foreach($caisses as $caisse)
                        <option value="{{$caisse->id}}">{{$caisse->nom}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Utilisateur</label>
                <select name="id_user" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}} ({{$user->username}})</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary">Affecter</button>
            <a href="{{route('caisses_management')}}" class="btn btn-default">Retour</a>
        </form>
    </div>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('caisses_management')}}"><strong>Caisses</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
