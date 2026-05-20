@extends('skeleton')
@section('content')

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Recherche</strong></div>
            <div class="panel-body">
                <form action="{{route('show_log')}}" method="get" class="row">
                    <div class="col-md-3">
                        <label>Utilisateur</label>
                        <select name="user" class="form-control">
                            <option value="0">Tous les utilisateurs</option>
                            @foreach($users as $u)
                                <option value="{{$u->id}}" @if($user_id == $u->id) selected @endif>{{$u->name}} ({{$u->username}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Operation</label>
                        <select name="operation" class="form-control">
                            <option value="">Toutes les operations</option>
                            @foreach($operations as $op)
                                <option value="{{$op}}" @if($operation == $op) selected @endif>{{$op}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Date de debut</label>
                        <input type="date" name="date_debut" value="{{$date_debut}}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Date de fin</label>
                        <input type="date" name="date_fin" value="{{$date_fin}}" class="form-control">
                    </div>
                    <div class="col-md-2" style="padding-top: 25px;">
                        <button class="btn btn-primary" type="submit">Afficher</button>
                        <button class="btn btn-default" type="button" onclick="window.print()">Imprimer</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Utilisateur</th>
                <th>Operation</th>
                <th>Message</th>
                <th>Route</th>
                <th>Methode</th>
                <th>IP</th>
                <th>Date</th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($logs as $l)
                <tr>
                    <td>{{$i++}}</td>
                    <td>
                        @if($l->user)
                            {{$l->user->name}}<br><small>{{$l->user->username}}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td><span class="label label-info">{{$l->operation ?: 'operation'}}</span></td>
                    <td>{{$l->message}}</td>
                    <td>
                        {{$l->route_name}}
                        @if($l->url)<br><small>{{$l->url}}</small>@endif
                    </td>
                    <td>{{$l->method}}</td>
                    <td>{{$l->ip}}</td>
                    <td>{{(new DateTime($l->created_at))->format('d-m-Y H:i:s')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="paginate" style="text-align: center;"> <?php echo($logs->appends(request()->except('page'))->render()); ?></div>




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
