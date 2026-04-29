@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

            <button class="btn btn-primary" data-toggle="modal" data-backdrop="true" href="#add_cycle"
            ><span class="glyphicon glyphicon-plus"></span> {{  trans('admin.ajouter_personnel')  }}</button>
            <span class="col-md-12"><strong>Total : {{count($personnel)}}</strong></span>
            <h3 style="font-size: 22px">{{  trans('admin.personnels')  }}</h3>

            <table class="table  table-striped table-condensed table-inverse" id="table-personnel" style="margin-top: 5px;">
            <thead>
            <th>#</th>
            <th>Nom</th>
            <th>Lieu de naissance</th>
            <th>{{trans('inscription.sexe')}}</th>
            <th>Tel</th>
            <th></th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($personnel as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="{{ URL::to('/personnel/edit/').'/'.$c->id }}"><span class="glyphicon glyphicon-edit"></span> <strong >{{$c->nom}}</strong></a></td>
                    <td><strong >{{$c->lieu_naiss}}</strong></td>
                    <td><strong >{{$c->sexe}}</strong></td>
                    <td><strong >{{$c->tel1}}</strong></td>

                    <td><a href="{{ URL::to('/personnel/destroy/').'/'.$c->id }}"   class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-remove"></span> {{trans('admin.del')}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?view=personnel\search_personnel&', $personnel->render()) ); ?></div>

            <div class="modal fade" id="add_cycle">
                <div class="modal-dialog" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">


                        <form accept-charset="UTF-8" role="form" id="select_sexe" method="POST" action="{{ url('/personnel/store/') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <fieldset>

                    <div class="form-group">
                        <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                                <input readonly class="form-control" id="date_naiss" name="date_naiss" type="text"  value="{{ old('date_naiss') }}" placeholder="yyyy/mm/dd" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ old('lieu_naiss') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nationalite">{{  trans('inscription.nationalite')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="nationalite" name="nationalite" type="text"  value="{{ old('nationalite') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sexe">{{  trans('inscription.sexe')  }} : <span class="required">*</span></label>
                                <select name="sexe" form="select_sexe" class="form-control">
                                    <option value="H">{{  trans('inscription.homme')  }}</option>
                                    <option value="F">{{  trans('inscription.femme')  }}</option>
                                </select>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-6">
                                <label for="diplome">{{  trans('admin.diplome')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="diplome" name="diplome" type="text"  value="{{ old('diplome') }}" required >
                            </div>
                            <div class="col-md-6">
                                <label for="num_contribuable">{{  trans('admin.num_contribuable')  }} :</label>
                                <input class="form-control" id="num_contribuable" name="num_contribuable" type="text"  value="{{ old('num_contribuable') }}"  >

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="num_ss">{{  trans('admin.num_ss')  }} :</label>
                                <input class="form-control" id="num_ss" name="num_ss" type="text"  value="{{ old('num_ss') }}" >
                            </div>
                            <div class="col-md-6">
                                <label for="categorie">{{  trans('admin.categorie')  }} :</label>
                                <input class="form-control" id="categorie" name="categorie" type="text"  value="{{ old('categorie') }}" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="echelon">{{  trans('admin.echelon')  }} :</label>
                                <input class="form-control" id="echelon" name="echelon" type="text"  value="{{ old('echelon') }}" >
                            </div>
                            <div class="col-md-6">
                                <label for="date_rec">{{  trans('admin.date_rec')  }} :</label>
                                <input readonly class="form-control" id="date_rec" name="date_rec" type="text"  value="{{ old('date_rec') }}" placeholder="yyyy/mm/dd">
                            </div>
                        </div>
                        <label >Type :</label>
                        <select name="type" form="select_sexe" class="form-control">
                            <option value="0">@lang('admin.enseignant')</option>
                            <option value="1">@lang('admin.autre')</option>
                        </select>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="adresse">{{  trans('admin.adresse')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ old('adresse') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email">{{  trans('admin.email')  }} :</label>
                                <input class="form-control" id="email" name="email" type="text"  value="{{ old('email') }}" >
                            </div>
                        </div>







                        <div class="row">
                            <div class="col-md-6">
                                <label for="tel1">{{  trans('admin.tel1')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="tel1" name="tel1" type="text"  value="{{ old('tel1') }}" required >
                            </div>

                            <div class="col-md-6">
                                <label for="tel2">{{  trans('admin.tel2')  }} :</label>
                                <input class="form-control" id="nom" name="tel2" type="text"  value="{{ old('tel2') }}">
                            </div>
                        </div>






                        <label for="matieres">{{  trans('admin.titre_matiere')  }} :</label>
                        <textarea  class="form-control" id="matieres" name="matieres">{{ old('matieres') }}</textarea>

                        <label for="autre_information">{{  trans('admin.autre')  }} informations:</label>
                        <textarea  class="form-control" id="autre_information" name="autre_information">{{ old('autre_information') }}</textarea>

                    </div>
                    <input class="btn  btn-success pull-right" type="submit" value="{{  trans('admin.confirmer')  }}">


                </fieldset>
            </form>

    </div>
</div>
</div>
    </div>
    </div>

    <script>

        $('#table-personnel').dataTable( {
            dom: 'Bfrtip',
            pageLength:50,
            buttons: [
                //'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Export to Excel',
                    title: 'Liste personnel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
                ,
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    titleAttr: 'PDF',
                    title: 'Liste personnel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
                ,
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> @lang("inscription.imprimer")',
                    titleAttr: 'Imprimer',
                    title: 'Liste personnel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        } );



    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('personnel')}}">@lang('menu.gestion_personnel')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection