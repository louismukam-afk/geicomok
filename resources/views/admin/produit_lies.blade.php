@extends('skeleton')
@section('content')
    @include('perso.functions')

    <div class="col-md-6">

       @if(sizeof($produit_lies)<1) <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_pdt"
        ><span class="glyphicon glyphicon-plus"></span> Nouveau produit</button> @endif

        <h3>Produit: <strong>{{$produit->libelle}}</strong></h3>

        <table   class="table  table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Libellé</th>
                <td>Quantité</td>
                <th></th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($produit_lies as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong>{{$p->produit_c->libelle}}</strong></td>
                    <td>{{$p->quantite}}</td>

                    <td>
                        <a href="{{route('delete_produit_lies',$p->id)}}" class="btn btn-danger btn-xs"> <span class="glyphicon glyphicon-remove"></span> Supprimer
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
                        <h4 class="modal-title">Formulaire</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="formulaire" action="{{route('store_produit_lies')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$produit->id}}">
                            <fieldset>

                                <div class="form-group">
                                    <label >Choisissez un produit: </label>
                                    <select  name="produit" form="formulaire" class="form-control" required>

                                        @foreach($produits as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('produit')) selected @endif>{{$c->libelle}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label >Quantité: <span class="required">*</span></label>
                                    <input type="number" class="form-control" value="{{old('quantite')}}" name="quantite" step="1" required>
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
        <li><a href="{{route('produit_management')}}"><strong>Gestion des produits</strong></a></li>

        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
