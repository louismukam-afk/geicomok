@extends('skeleton')
@section('content')

    <div class="col-md-8">

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_salaire"
        ><span class="glyphicon glyphicon-plus"></span> Nouveau salaire</button>
        <td><a href="#multi-delete" id="multi-delete" onclick="sendToDelete('{{route('mdelete_salaire')}}')"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> remove</a></td>

        <table class="table table-bordered  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Noms du personnel</th>
                <th>Salaire </th>
                <th><input type="checkbox" id="master-check" onchange="master_check_change(this)"> </th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($salaire as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="#edit_s" data-toggle="modal" data-backdrop="false" onclick="edit_s({{$c->id}},'{{$c->montant}}',{{$c->id_personnel}})"><strong ><span class="glyphicon glyphicon-edit"></span>
                                @foreach($personnel as $p)
                                    @if($p->id == $c->id_personnel)
                                        {{$p->nom}}
                                    @endif
                                    <php break; ?>
                        @endforeach
                    </td>
                    <td>{{$c->montant}} @lang('main.devise')</td>
                    <td><input type="checkbox" name="delete[]" value="{{$c->id}}" class="check-list" ></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="add_salaire">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="salaires" role="form" method="POST" action="{{route('store_salaire')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="id_personnel">Choisir le personnel : </label>
                                    <select  name="id_personnel" id="id_personnel" form="salaires" class="form-control">
                                        @foreach($personnel1 as $p)
                                            <option value="{{  $p->id  }}" @if($p->id == old('id_personnel')) selected @endif>{{  $p->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="montant"> Montant :  </label>
                                    <div class="input-group">
                                        <input class="form-control" id="montant" name="montant" type="text"  value="{{ old('montant') }}">
                                        <span class="input-group-addon">@lang('main.devise')</span>
                                    </div>
                                </div>

                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add</button>

                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="modal fade" id="edit_s">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">




                        <form accept-charset="UTF-8" id="edit_salaires" role="form" method="POST" action="{{route('update_salaire')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit-id" name="id" >
                            <fieldset>
                                <div class="form-group">
                                    <label for="edit-personnel">Choisir un personnel : </label>
                                    <select  name="id_personnel" form="edit_salaires" id="edit-personnel" class="form-control">
                                        @foreach($personnel as $p)
                                            <option value="{{  $p->id  }}" >{{  $p->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit-montant"> Montant :  </label>
                                    <div class="input-group">
                                        <input class="form-control" id="edit-montant" name="montant" type="text" >
                                        <span class="input-group-addon">FCFA</span>
                                    </div>
                                </div>

                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add</button>

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
        function edit_s(id,montant,id_personnel) {
            $('#edit-id').val(id);
            $('#edit-montant').val(montant);
            $('#edit-personnel').val(id_personnel);
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
