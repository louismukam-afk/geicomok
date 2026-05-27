@extends('skeleton')
@section('content')

    <div class="col-md-6">

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cat"
        ><span class="glyphicon glyphicon-plus"></span> Nouvelle catégorie</button>
        <a href="{{ route('import_categorie') }}" class="btn btn-success">
            <span class="glyphicon glyphicon-upload"></span> Importer
        </a>
        <td><a href="#multi-delete" id="multi-delete" onclick="sendToDelete('{{route('mdelete_categorie')}}')"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> remove</a></td>

        <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Libellé</th>
                <th><input type="checkbox" id="master-check" onchange="master_check_change(this)"> </th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($categories as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><a href="#edit_cat" data-toggle="modal" data-backdrop="false" onclick="edit_cat({{$c->id}},'{{$c->libelle}}')"><strong ><span class="glyphicon glyphicon-edit"></span> {{$c->libelle}}</strong></a></td>
                    <td><input type="checkbox" name="delete[]" value="{{$c->id}}" class="check-list" ></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="add_cat">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" action="{{route('store_categorie')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="libelle">Libelle : </label>
                                    <input class="form-control" id="libelle" name="libelle" type="text"  value="{{ old('libelle') }}" required AUTOFOCUS>

                                </div>

                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add</button>

                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="modal fade" id="edit_cat">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" action="{{route('update_categorie')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit-id" name="id" >
                            <fieldset>

                                <div class="form-group">
                                    <label for="edit-libelle">Name : </label>
                                    <input class="form-control" id="edit-libelle" name="libelle" type="text"  required >

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
    function edit_cat(id,libelle) {
        $('#edit-id').val(id);
        $('#edit-libelle').val(libelle);
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
