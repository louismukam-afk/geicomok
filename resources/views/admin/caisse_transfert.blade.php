@extends('skeleton')
@section('content')
    <div class="col-md-8">
        @if(session('success'))
            <div class="alert alert-success">Transfert enregistre.</div>
        @endif

        <form method="POST" action="{{route('store_caisse_transfert')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label>Caisse source</label>
                <select name="caisse_source" class="form-control" required>
                    @foreach($caisses as $caisse)
                        <option value="{{$caisse->id}}">{{$caisse->nom}} - solde {{number_format($caisse->solde(), 2)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Caisse destination</label>
                <select name="caisse_destination" class="form-control" required>
                    @foreach($caisses as $caisse)
                        <option value="{{$caisse->id}}">{{$caisse->nom}} - solde {{number_format($caisse->solde(), 2)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Montant</label>
                <input type="number" step="0.001" min="1" name="montant" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="{{date('Y-m-d')}}" required>
            </div>
            <button class="btn btn-primary">Transferer</button>
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
