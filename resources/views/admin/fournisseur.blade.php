@extends('skeleton')
@section('content')

    <div class="col-md-12">

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_fournisseur"
        ><span class="glyphicon glyphicon-plus"></span> Nouveau Fournisseur</button>
        <td><a href="#multi-delete" id="multi-delete" onclick="sendToDelete('{{route('mdelete_fournisseur')}}')"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> remove</a></td>

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
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
                <th><input type="checkbox" id="master-check" onchange="master_check_change(this)"> </th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($fournisseur as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="#edit_f" data-toggle="modal" data-backdrop="false" onclick="edit_f({{$c->id}},'{{$c->nom}}','{{$c->telephone}}',{{$c->id_pays}},'{{$c->ville}}','{{$c->email}}','{{$c->adresse}}','{{$c->boite_postale}}')"><strong ><span class="glyphicon glyphicon-edit"></span> {{$c->nom}}</strong></a></td>
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
                    <td><input type="checkbox" name="delete[]" value="{{$c->id}}" class="check-list" ></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="add_fournisseur">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="personnel_form" role="form" method="POST" action="{{route('store_fournisseur')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="nom">Noms : </label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="telephone">Numéro(s) de téléphone(s) : </label>
                                    <input class="form-control" id="telephone" name="telephone" type="text"  value="{{ old('telephone') }}" >

                                </div>
                                <div class="form-group">
                                    <label for="ville">Ville : </label>
                                    <input class="form-control" id="ville" name="ville" type="text"  value="{{ old('ville') }}" >

                                </div>

                                <div class="form-group">
                                    <label for="adresse">Adresse : </label>
                                    <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ old('adresse') }}" >

                                </div>
                                <div class="form-group">
                                    <label for="email">Email : </label>
                                    <input class="form-control" id="email" name="email" type="email"  value="{{ old('email') }}" >

                                </div>
                                <div class="form-group">
                                    <label for="boite_postale">Boite postale : </label>
                                    <input class="form-control" id="boite_postale" name="boite_postale" type="text"  value="{{ old('boite_postale') }}" >

                                </div>

                                <div class="form-group">
                                    <label for="id_pays">Pays d'origine : </label>
                                    <select id="id_pays" name="id_pays" form="personnel_form" class="form-control">
                                        <option value="0"> --- Choisir un pays --- </option>
                                        @foreach($pays as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('id_pays')) selected @endif>{{$c->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add</button>

                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="modal fade" id="edit_f">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="formulaire" role="form" method="POST" action="{{route('update_fournisseur')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit-id" name="id" >
                            <fieldset>

                                <div class="form-group">
                                    <label for="edit-nom">Noms : </label>
                                    <input class="form-control" id="edit-nom" name="nom" type="text"   required >

                                </div>


                                <div class="form-group">
                                    <label for="edit-adresse">Adresse : </label>
                                    <input class="form-control" id="edit-adresse" name="adresse" type="text"  >

                                </div>
                                <div class="form-group">
                                    <label for="edit-telephone">Numéros téléphone(s) : </label>
                                    <input class="form-control" id="edit-telephone" name="telephone" type="text"   >

                                </div>
                                <div class="form-group">
                                    <label for="ville">Ville : </label>
                                    <input class="form-control" id="edit-ville" name="ville" type="text"  value="{{ old('ville') }}" >

                                </div>
                                <div class="form-group">
                                    <label for="edit-email">Email : </label>
                                    <input class="form-control" id="edit-email" name="email" type="email"   >

                                </div>
                                <div class="form-group">
                                    <label for="edit-boite_postale">Boite postale : </label>
                                    <input class="form-control" id="edit-boite_postale" name="boite_postale" type="text"   >

                                </div>
                                <div class="form-group">
                                    <label for="edit-id_pays">Pays d'origine : </label>
                                    <select id="edit-id_pays" name="id_pays" form="formulaire" class="form-control">
                                        <option value="0"> --- Choisir un pays --- </option>

                                        @foreach($pays as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('id_pays')) selected @endif>{{$c->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Edit</button>


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

    </div>

@endsection
@section('scripts')
    <script>
        function edit_f(id,nom,telephone,id_pays,ville,email,adresse,boite_postale) {
            $('#edit-id').val(id);
            $('#edit-nom').val(nom);
            $('#edit-boite_postale').val(boite_postale);
            $('#edit-id_pays').val(id_pays);
            $('#edit-ville').val(ville);
            $('#edit-telephone').val(telephone);
            $('#edit-adresse').val(adresse);
            $('#edit-email').val(email);
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
