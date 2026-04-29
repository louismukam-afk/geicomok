@extends('skeleton')
@section('content')

    <div class="col-md-6">
        @include('perso.functions')
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
        <h3>Utilisateur: <strong>{{$user->name}}</strong></h3>
        <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>#</th>
            <th>Roles</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($roles as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >
                            {{getRole($c->value)}}
                        </strong></td>
                    <td><a href="{{route('delete_role',$c->id)}}"   class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>Ajouter un role</button>

        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="role-form" action="{{ route('store_role') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="role">Role : </label>
                                    <select  name="role" form="role-form" class="form-control">
                                        @if($validated)
                                            <option value="1">Administrateur</option>
                                        @endif
                                        <option value="2">Editeur</option>
                                        <option value="16">Vendeur</option>

                                    </select>
                                </div>
                                <input class="btn  btn-success " type="submit" value="Confirmer">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

    </div>

@endsection

@section('scripts')

@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">

        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li><a href="{{route('user_management')}}"><strong>Gestion des utilisateurs</strong></a></li>

        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
