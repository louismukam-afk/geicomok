@extends('skeleton')
@section('content')
    @include('perso.functions')

    <div class="col-md-12">

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_pdt"
        ><span class="glyphicon glyphicon-plus"></span> @lang('main.nouveau') @lang('main.boutique')/ @lang('main.magasin')</button>
        <td><a href="#multi-delete" id="multi-delete" onclick="sendToDelete('{{route('mdelete_boutique')}}')"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> remove</a></td>

        <table   class="table  table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Type</th>
                <th>adresse</th>
                <th>Boite postale</th>
                <th>Telephone</th>
                <th>Email</th>
                <th>Status</th>
                <td></td>
                <th><input type="checkbox" id="master-check" onchange="master_check_change(this)"> </th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($boutiques as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="#edit_pdt" data-toggle="modal" data-backdrop="false" onclick="edit_btq({{$p->id}},'{{sanitize($p->nom)}}',{{$p->type}},'{{$p->adresse}}','{{$p->boite_postale}}','{{$p->telephone}}','{{$p->email}}',{{$p->active}})"><strong ><span class="glyphicon glyphicon-edit"></span> {{$p->nom}}</strong></a></td>

                    <td style="font-weight: 600">
                            @if($p->type == 0)
                                {{  trans('main.magasin') }}
                        @else
                            {{  trans('main.boutique') }}

                        @endif
                    </td>
                    <td>{{$p->adresse}}</td>
                    <td>{{$p->boite_postale}} </td>
                    <td>{{$p->telephone}}</td>
                    <td>{{$p->email}}</td>
                    <td>
                        @if($p->active == 0)
                            <span class="text-danger" style="font-weight: 600"> <span class="glyphicon glyphicon-ban-circle"></span> {{  trans('main.desactive') }}</span>

                        @else
                            <span class="text-success" style="font-weight: 600"> <span class="glyphicon glyphicon-ok-circle"></span> {{  trans('main.active') }}</span>
                        @endif
                    </td>


                    <td><input type="checkbox" name="delete[]" value="{{$p->id}}" class="check-list" ></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="add_pdt">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">X</button>
                        <h4 class="modal-title">Remplir les champs</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="formulaire-boutique" action="{{route('store_boutique')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="nom">Nom : </label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}"  AUTOFOCUS required>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Choisissez le type : <span class="required"></span></label>
                                        <select id="type" name="type" form="formulaire-boutique" class="form-control">
                                            <option value="1" @if(old('type')==1) selected @endif>@lang('main.boutique')</option>
                                            <option value="0" @if(old('type')==0) selected @endif>@lang('main.magasin')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adresse">Adresse : </label>
                                        <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ old('adresse') }}" required >

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="boite_postale">Boite postale : </label>
                                        <input class="form-control" id="boite_postale" name="boite_postale" type="text"  value="{{ old('boite_postale') }}"  >

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telephone">Telephone : </label>
                                        <input class="form-control" id="telephone" name="telephone" type="text"  value="{{ old('telephone') }}"  >

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email : </label>
                                        <input class="form-control" id="email" name="email" type="email"  value="{{ old('email') }}"  >

                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Ajouter</button>

                                </div>


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="modal fade" id="edit_pdt">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="edit-form-boutique" role="form" method="POST" action="{{route('update_boutique')}}" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit_id" name="id" >
                            <fieldset>


                                <div class="form-group">
                                    <label >Nom : </label>
                                    <input class="form-control" id="edit-nom" name="nom" type="text"     required>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Choisissez le type : <span class="required"></span></label>
                                        <select id="edit-type" name="type" form="edit-form-boutique" class="form-control">
                                            <option value="1" >@lang('main.boutique')</option>
                                            <option value="0" >@lang('main.magasin')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Adresse : </label>
                                        <input class="form-control" id="edit-adresse" name="adresse" type="text"   required>

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Boite postale : </label>
                                        <input class="form-control" id="edit-boite_postale" name="boite_postale" type="text"   >

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Telephone : </label>
                                        <input class="form-control" id="edit-telephone" name="telephone" type="text"  >

                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Email : </label>
                                        <input class="form-control" id="edit-email" name="email" type="email"   >

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Status : </label>
                                        <select id="edit-status" name="active" form="edit-form-boutique" class="form-control">
                                            <option value="1" >@lang('main.active')</option>
                                            <option value="0" >@lang('main.desactive')</option>
                                        </select>
                                    </div>
                                </div>








                                <button class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

    </div>

    <script>
        /* function edite_pdt(id,libelle,reference,quantite_minimale,prix,id_categorie)
        {

       document.getElementById("edit_id").value = id;
            document.getElementById("edit_reference").value = reference;
            document.getElementById("edit_libelle").value = libelle;
            document.getElementById("edit_id_categorie").value = id_categorie;
            document.getElementById("edit_quantite_minimale").value = quantite_minimale;
            document.getElementById("edit_prix").value = prix;
        }*/
    </script>
    <!--    -->

@endsection



@section('scripts')
    <script>
       function edit_btq(id,nom,type,adresse,boite_postale,telephone,email,status) {
            $('#edit_id').val(id);
            $('#edit-nom').val(nom);
            $('#edit-type').val(type);
            $('#edit-adresse').val(adresse);
            $('#edit-boite_postale').val(boite_postale);
            $('#edit-telephone').val(telephone);
           $('#edit-email').val(email);

           $('#edit-status').val(status);

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
