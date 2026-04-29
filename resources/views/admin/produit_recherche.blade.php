<?php
/**
 * Created by PhpStorm.
 * User: Louis
 * Date: 13/08/2025
 * Time: 09:33
 */@extends('skeleton')
@section('content')
    @include('perso.functions')

    <div class="col-md-12">

        <!-- Boutons d'actions -->
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_pdt">
            <span class="glyphicon glyphicon-plus"></span> Nouveau produit
        </button>
        <a href="#multi-delete" id="multi-delete" onclick="sendToDelete('{{route('mdelete_produit')}}')" class="btn btn-danger btn-xs pull-right">
            <span class="glyphicon glyphicon-trash"></span> Remove
        </a>

        <!-- Champ de recherche -->
        <div class="form-group" style="margin-top: 15px;">
            <input type="text" id="searchProduit" class="form-control" placeholder="Rechercher un produit...">
        </div>

        <!-- Tableau Produits -->
        <table id="tableProduits" class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>@lang('main.prix_min')</th>
                <th>Prix de vente en détail</th>
                <th>Prix de vente en gros</th>
                <th>Prix de vente en semi-gros</th>
                <th>Prix de vente comptoir</th>
                <th>Prix d'achat</th>
                <td></td>
                <th><input type="checkbox" id="master-check" onchange="master_check_change(this)"></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($produit as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$p->reference}}</td>
                    <td>
                        {{--<a href="#edit_pdt" data-toggle="modal" data-backdrop="false" onclick="edit_pdt({{$p->id}},'{{$p->reference}}','{{sanitize($p->libelle)}}',{{$p->id_categorie}},'{{$p->prix_minimum}}',{{$p->prix}},{{$p->prix_gros}},{{$p->prix_semi_gros}},{{$p->prix_comptoir}},{{$p->prix_achat}},'{{sanitize($p->description)}}')">--}}
                        <a href="#edit_pdt" data-toggle="modal" data-backdrop="false" onclick="edit_pdt({{$p->id}},'{{$p->reference}}','{{sanitize($p->libelle)}}',{{$p->id_categorie}},'{{$p->prix_minimum}}',{{$p->prix}},{{$p->prix_achat}},'{{sanitize($p->description)}}')">
                            <strong><span class="glyphicon glyphicon-edit"></span> {{$p->libelle}}</strong>
                        </a>
                    </td>
                    <td>{{$p->categorie->libelle}}</td>
                    <td>{{$p->prix_minimum}} @lang('main.devise')</td>
                    <td>{{$p->prix}} @lang('main.devise')</td>
                    <td>{{$p->prix_gros}} @lang('main.devise')</td>
                    <td>{{$p->prix_semi_gros}} @lang('main.devise')</td>
                    <td>{{$p->prix_comptoir}} @lang('main.devise')</td>
                    <td>{{$p->prix_achat}} @lang('main.devise')</td>
                    <td>
                        <a href="{{route('produit_lies_management',$p->id)}}" class="btn btn-warning btn-xs">
                            <span class="glyphicon glyphicon-link"></span> Produits Liés
                        </a>
                    </td>
                    <td><input type="checkbox" name="delete[]" value="{{$p->id}}" class="check-list"></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="paginate" style="text-align: center;">
            <?php echo(str_replace('/?', '?', $produit->render())); ?>
        </div>

        <!-- Modal Ajout Produit -->
        <div class="modal fade" id="add_pdt">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Veuillez remplir les champs</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="formulaire" action="{{route('store_produit')}}">
                            @csrf
                            <fieldset>
                                <div class="form-group">
                                    <label for="reference">Référence :</label>
                                    <input class="form-control" id="reference" name="reference" type="text" value="{{ old('reference') }}">
                                </div>
                                <div class="form-group">
                                    <label for="libelle">Libellé :</label>
                                    <input class="form-control" id="libelle" name="libelle" type="text" value="{{ old('libelle') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_categorie">Catégorie :</label>
                                    <select id="id_categorie" name="id_categorie" class="form-control">
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}" @if($c->id == old('id_categorie')) selected @endif>{{ $c->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="prix">Prix de vente en détail:</label>
                                    <input class="form-control" id="prix" name="prix" type="number" step="0.01" value="{{ old('prix') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="prix_gros">Prix de vente en gros :</label>
                                    <input class="form-control" id="prix_gros" name="prix_gros" type="number" step="0.01" value="{{ old('prix_gros') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="prix_semi_gros">Prix de vente semi-gros :</label>
                                    <input class="form-control" id="prix_semi_gros" name="prix_semi_gros" type="number" step="0.01" value="{{ old('prix_semi_gros') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="prix_comptoir">Prix de vente comptoir :</label>
                                    <input class="form-control" id="prix_comptoir" name="prix_comptoir" type="number" step="0.01" value="{{ old('prix_comptoir') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="quantite_minimale">@lang('main.prix_min') :</label>
                                    <input class="form-control" id="quantite_minimale" name="quantite_minimale" type="number" step="0.01" value="{{ old('quantite_minimale') }}">
                                </div>
                                <div class="form-group">
                                    <label for="prix_a">Prix d'achat :</label>
                                    <input class="form-control" id="prix_a" name="prix_achat" type="number" step="0.01" value="{{ old('prix_achat') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description :</label>
                                    <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                                </div>
                                <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Ajouter</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Édition Produit -->
        <div class="modal fade" id="edit_pdt">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Modifier le produit</h4>
                    </div>
                    <div class="modal-body">
                        <form id="edit_produit_form" method="POST" action="{{route('update_produit')}}">
                            @csrf
                            <input type="hidden" id="edit_id" name="id">
                            <fieldset>
                                <div class="form-group">
                                    <label for="edit_reference">Référence :</label>
                                    <input class="form-control" id="edit_reference" name="reference" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="edit_libelle">Nom du produit :</label>
                                    <input class="form-control" id="edit_libelle" name="libelle" type="text" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_categorie">Catégorie :</label>
                                    <select id="edit_categorie" name="id_categorie" class="form-control">
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_prix">Prix de vente en detail :</label>
                                    <input class="form-control" id="edit_prix" name="prix" type="number" step="0.01" required>
                                </div>
                                {{-- <div class="form-group">
                                     <label for="prix_gros">Prix de vente en gros :</label>
                                     <input class="form-control" id="edit_prix_gros" name="prix_gros" type="number" step="0.01"  required>
                                 </div>
                                 <div class="form-group">
                                     <label for="prix_semi_gros">Prix de vente semi-gros :</label>
                                     <input class="form-control" id="edit_prix_semi_gros" name="prix_semi_gros" type="number" step="0.01"  required>
                                 </div>
                                 <div class="form-group">
                                     <label for="prix_comptoir">Prix de vente comptoir :</label>
                                     <input class="form-control" id="edit_prix_comptoir" name="prix_comptoir" type="number" step="0.01"  required>
                                 </div>--}}
                                <div class="form-group">
                                    <label for="edit_quantite_minimale">@lang('main.prix_min') :</label>
                                    <input class="form-control" id="edit_quantite_minimale" name="quantite_minimale" type="number" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="edit_prix_a">Prix d'achat :</label>
                                    <input class="form-control" id="edit_prix_a" name="prix_achat" type="number" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_description">Description :</label>
                                    <textarea class="form-control" id="edit_description" name="description"></textarea>
                                </div>
                                <button class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
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
        //        function edit_pdt(id,reference,libelle,id_categorie,quantite_minimale,prix,prix_gros,prix_semi_gros,prix_comptoir,prix_a,description) {
        function edit_pdt(id,reference,libelle,id_categorie,quantite_minimale,prix,prix_a,description) {
            $('#edit_id').val(id);
            $('#edit_reference').val(reference);
            $('#edit_libelle').val(libelle);
            $('#edit_categorie').val(id_categorie);
            $('#edit_quantite_minimale').val(quantite_minimale);
            $('#edit_prix').val(prix);
            /* $('#edit_prix_gros').val(prix_gros);
             $('#edit_prix_semi_gros').val(prix_semi_gros);
             $('#edit_prix_comptoir').val(prix_comptoir);*/
            $('#edit_prix_a').val(prix_a);
            $('#edit_description').val(description);
        }

        document.getElementById("searchProduit").addEventListener("keyup", function () {
            var value = this.value.toLowerCase();
            var rows = document.querySelectorAll("#tableProduits tbody tr");

            rows.forEach(function (row) {
                var text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
            });
        });

    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
