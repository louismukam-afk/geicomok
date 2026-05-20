@extends('skeleton')
@section('content')

    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                Success
            </div>

        @endif

        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>#</th>
            <th>Nom d'utilisateur</th>
            <th>Nom</th>
            <th>@lang('main.boutique') / @lang('main.magasin')</th>
            <th>Role(s) </th>
            <th>Fonctions</th>
            <th>Actif</th>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($utilisateurs as $u)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >{{$u->username}}</strong></td>
                    <td> <strong>{{$u->name}}</strong></td>
                    <td>
                        <strong class="text-primary">
                            @if($u->id_boutique==0)
                                @lang('main.tous')
                            @elseif($u->boutique)
                                {{$u->boutique->nom}}
                            @endif
                        </strong>

                    </td>
                    <td><a href="{{route('role_management',$u->id)}}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-lock"></span> Role(s)</a></td>
                    <td><a href="{{route('action_management',$u->id)}}" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-list-alt"></span> Fonctions</a></td>

                    @if($u->active==1)
                        <td>Oui</td>
                        <td>
                            <a  class="btn btn-danger btn-xs"   href="{{route('deactivate_user',$u->id)}}"> <span class="glyphicon glyphicon-ban-circle"></span> Désactiver</a>
                    @else
                        <td>Non</td>
                        <td>
                            <a href="#" class="btn btn-success btn-xs" onclick="activate({{$u->id}},{{$u->id_boutique}})"><span class="glyphicon glyphicon-ok"  ></span> Activer</a>

                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $utilisateurs->render()) ); ?></div>




    </div>

    <div class="modal" id="mod_activate_user">

        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title">Sélectionnez</h3>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" method="GET" action="{{route('activate_user')}}" id="form-activate-user">
                        <input type="hidden" id="edit-id" name="id" >
                        <fieldset>

                            <div class="form-group">
                                <label > @lang('main.boutique') / @lang('main.magasin') : </label>
                                <select class="form-control"  name="boutique" id="edit-boutique" form="form-activate-user" required>
                                    <option value="" > -- Choisir -- </option>
                                    <option value="0">@lang('main.tous')</option>
                                    <optgroup label="@lang('main.boutique')">
                                        @foreach($boutiques as $b)
                                            @if($b->type==1)
                                                <option value="{{$b->id}}">{{$b->nom}}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="@lang('main.magasin')">
                                        @foreach($boutiques as $b)
                                            @if($b->type==0)
                                                <option value="{{$b->id}}">{{$b->nom}}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>

                                </select>

                            </div>
                            <button class="btn btn-primary pull-right"><span class="glyphicon glyphicon-ok"></span> Activer</button>


                        </fieldset>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function activate(id,boutique) {
            $('#edit-id').val(id);
            $('#edit-boutique').val(boutique);

            $('#mod_activate_user').modal('show');
        }


    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">

        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
