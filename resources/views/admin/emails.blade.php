@extends('skeleton')
@section('content')
    @include('perso.functions')

    <div class="col-md-6">

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_pdt"
        ><span class="glyphicon glyphicon-plus"></span> Nouvel email</button>

        <table   class="table  table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Email</th>

            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($emails_list as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong>{{$p->email}}</strong></td>


                    <td>
                        <a href="{{route('destroy_email',$p->id)}}" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-remove"></span> Supprimer
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="add_pdt">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="formulaire" action="{{route('store_email')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="reference">Email : </label>
                                    <input class="form-control"  name="email" type="email"  value="{{ old('email') }}"  AUTOFOCUS required>

                                </div>




                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Ajouter</button>

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




@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
