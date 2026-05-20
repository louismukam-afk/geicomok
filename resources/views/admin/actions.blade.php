@extends('skeleton')

@section('content')
    <style>
        .user-action {
            display: inline-block;
            margin: 0 15px 8px 0;
            font-weight: 600;
            font-size: 11px;
        }

        fieldset h5 {
            color: black;
        }

        .action-box {
            min-height: 118px;
        }
    </style>

    <div class="col-md-12">
        @if(isset($success))
            <div class="alert alert-success">
                Fonctions mises a jour avec succes.
            </div>
        @endif
        @if(isset($sync_success))
            <div class="alert alert-info">
                Synchronisation terminee. Les fonctions trouvees dans les routes protegees ont ete ajoutees.
            </div>
        @endif

        <form action="{{route('update_actions')}}" class="form" method="POST" id="update-actions-form">
            <input type="hidden" name="id" value="{{$user->id}}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            @foreach($sections as $section => $groups)
                <legend class="text-uppercase" style="margin-top: 15px">{{$section}}</legend>
                <div class="col-md-12 alert alert-warning">
                    @foreach($groups as $title => $items)
                        <div class="col-md-6 action-box">
                            <fieldset>
                                <h5 class="text-uppercase"><strong>{{$title}}</strong></h5>
                                @foreach($items as $item)
                                    <span class="user-action {{isset($item['danger']) ? 'text-danger' : 'text-primary'}}">
                                        <input name="actions[]" type="checkbox" value="{{$item['value']}}" @if(in_array($item['value'], $actions_array)) checked @endif>
                                        {{$item['label']}}
                                    </span>
                                @endforeach
                            </fieldset>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="row">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-primary" value="Confirmer">
                    <button type="submit" class="btn btn-warning" formaction="{{route('sync_user_actions',$user->id)}}" formmethod="POST">
                        Synchroniser
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li><a href="{{route('user_management')}}"><strong>Utilisateurs</strong></a></li>
        <li class="active"><strong>{{$titre}}</strong></li>
    </ol>
@endsection
