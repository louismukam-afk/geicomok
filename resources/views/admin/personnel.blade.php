@extends('skeleton')
@section('content')

    <div class="col-md-12">

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_pers"
        ><span class="glyphicon glyphicon-plus"></span> Nouveau Personnel</button>
        <td><a href="#multi-delete" id="multi-delete" onclick="sendToDelete('{{route('mdelete_personnel')}}')"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> remove</a></td>

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Noms</th>
                <th>Sexe</th>
                <th>Pays</th>
                <th>Date d'entrée</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Email</th>
                <th><input type="checkbox" id="master-check" onchange="master_check_change(this)"> </th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($personnel as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="#edit_p" data-toggle="modal" data-backdrop="false" onclick="edit_p({{$p->id}},'{{$p->nom}}','{{$p->sexe}}',{{$p->id_pays}},'{{$p->date_entree}}','{{$p->telephone}}','{{$p->addresse}}','{{$p->email}}','{{$p->autres}}')"><strong ><span class="glyphicon glyphicon-edit"></span> {{$p->nom}}</strong></a></td>
                    <td>{{$p->sexe}}</td>
                    <td>
                        @foreach($pays as $pa)
                            @if($pa->id == $p->id_pays)
                            {{$pa->nom}}
                            @endif
                            <php break; ?>
                            @endforeach
                    </td>
                    <td>{{$p->date_entree}}</td>
                    <td>{{$p->telephone}}</td>
                    <td>{{$p->addresse}}</td>
                    <td>{{$p->email}}</td>
                    <td><input type="checkbox" name="delete[]" value="{{$p->id}}" class="check-list" ></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="add_pers">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="personnel_form" role="form" method="POST" action="{{route('store_personnel')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="nom">Noms : </label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="date_nais">Date de naissance : </label>
                                    <input class="form-control" id="date_nais" name="date_nais" type="date"  value="{{ old('date_nais') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="lieu_naiss">Lieu de naissance : </label>
                                    <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ old('lieu_naiss') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="sexe">Sexe : </label>
                                    <select form="personnel_form" id="sexe" name="sexe" class="form-control">
                                        <option value="Masculin" @if(strcmp(old('sexe'),'M')==0) selected @endif>Masculin</option>
                                        <option value="Féminin" @if(strcmp(old('sexe'),'F')==0) selected @endif>Féminin</option>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="addresse">Adresse : </label>
                                    <input class="form-control" id="addresse" name="addresse" type="text"  value="{{ old('addresse') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="telephone">Numéros téléphone(s) : </label>
                                    <input class="form-control" id="telephone" name="telephone" type="text"  value="{{ old('telephone') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="date_entree">Date d'entrée : </label>
                                    <input class="form-control" id="date_entree" name="date_entree" type="date"  value="{{ old('date_entree') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="email">Email : </label>
                                    <input class="form-control" id="email" name="email" type="email"  value="{{ old('email') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="id_pays">Pays d'origine : <span class="required">*</span></label>
                                    <select id="id_pays" name="id_pays" form="personnel_form" class="form-control">

                                        @foreach($pays as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('id_pays')) selected @endif>{{$c->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="autres">Autres informations :</label>
                                    <textarea  class="form-control" id="autres" name="autres">{{ old('autres') }}</textarea>


                                </div>

                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add</button>

                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="modal fade" id="edit_p">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="formulaire" role="form" method="POST" action="{{route('update_personnel')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit-id" name="id" >
                            <fieldset>

                                <div class="form-group">
                                    <label for="edit-nom">Noms : </label>
                                    <input class="form-control" id="edit-nom" name="nom" type="text"   required AUTOFOCUS>

                                </div>

                                <div class="form-group">
                                    <label for="edit-sexe">Sexe : </label>
                                    <select  id="edit-sexe" form="formulaire" name="sexe" class="form-control">
                                        <option value="Masculin" @if(strcmp(old('sexe'),'M')==0) selected @endif>Masculin</option>
                                        <option value="Féminin" @if(strcmp(old('sexe'),'F')==0) selected @endif>Féminin</option>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="edit-addresse">Adresse : </label>
                                    <input class="form-control" id="edit-addresse" name="addresse" type="text"  required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="edit-telephone">Numéros téléphone(s) : </label>
                                    <input class="form-control" id="edit-telephone" name="telephone" type="text"   required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="edit-date_entree">Date d'entrée : </label>
                                    <input class="form-control" id="edit-date_entree" name="date_entree" type="date"  value="{{ old('date_entree') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="edit-email">Email : </label>
                                    <input class="form-control" id="edit-email" name="email" type="email"  value="{{ old('email') }}" required AUTOFOCUS>

                                </div>
                                <div class="form-group">
                                    <label for="edit-id_pays">Pays d'origine : <span class="required">*</span></label>
                                    <select id="edit-id_pays" name="id_pays" form="formulaire" class="form-control">

                                        @foreach($pays as $c)
                                            <option value="{{  $c->id  }}" >{{$c->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="edit-autres">Autres informations : </label>
                                    <textarea  class="form-control" id="edit-autres" name="autres">{{ old('autres') }}</textarea>


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
        function edit_p(id,nom,sexe,id_pays,date_entree,telephone,addresse,email,autre) {
            $('#edit-id').val(id);
            $('#edit-nom').val(nom);
            $('#edit-sexe').val(sexe);
            $('#edit-id_pays').val(id_pays);
            $('#edit-date_entree').val(date_entree);
            $('#edit-telephone').val(telephone);
            $('#edit-addresse').val(addresse);
            $('#edit-email').val(email);
            $('#edit-autres').val(autre);

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
