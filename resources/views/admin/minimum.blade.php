@extends('skeleton')
@section('content')

    @include('perso.functions')
    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

    <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

   {{-- <button class="btn btn-primary" onclick="imprimerCommande()">
        <i class="glyphicon glyphicon-print"></i> Imprimer la liste à commander
    </button>--}}
    <div class="col-md-12">

        <!-- Champ de recherche -->
        <div class="form-group" style="margin-top: 15px;">
            <input type="text" id="searchProduit" class="form-control" placeholder="Rechercher un produit...">
        </div>

        <h2  style="text-align: center ; font-family: 'Arial Black'">Produits à commander en urgence</h2>
        <p><strong>Nombre de produits à commander :</strong> {{ $nbCommander }}</p>

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Libellé</th>
                <th>Stock Minimum</th>
                <th>Quantité en stock</th>
                <th>Observations</th>
                <th>Statut</th>
            </tr>
            </thead>
            <tbody>
            <?php $j = 1; ?>
            @foreach($produit as $p)
                @php
                    $sec = $p->securite->first();
                    $stockMin = $sec ? $sec->stock_minimum : 0;
                    $obs = $sec ? $sec->observation : '';
                    $quantite = isset($p->quantite) ? $p->quantite : 0;
                    $statut = ($quantite <= $stockMin) ? 'Commander' : 'Équilibre';

                @endphp
                @if($quantite <= $stockMin)
                    <tr>
                        <td>{{ $j++ }}</td>
                        <td>{{ $p->reference }}</td>
                        <td>{{ $p->libelle }}</td>
                        <td>{{ $stockMin }}</td>
                        <td>{{ $quantite }}</td>
                        <td>{{ $obs }}</td>
                        <td style="color: red; font-weight: bold;">{{ $statut }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    <h2 style="text-align: center ; font-family: 'Arial Black'">Listes de tous les produits de l'entreprise</h2>
        <table class="table table-bordered table-striped table-condensed table-inverse" id="tableProduits" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Libellé</th>
                <th>Stock Minimum</th>
                <th>Quantité en stock</th>
                <th>Observations</th>
                <th>Statut</th>
             {{--   <th>Action</th>--}}
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; $nbCommander = 0; ?>
            @foreach($produit as $p)
                @php
                    $sec = $p->securite->first(); // Prend uniquement la première sécurité associée
                   $stockMin = $sec ? $sec->stock_minimum : 0;
                    $obs = $sec ? $sec->observation : '';
                    $quantite = isset($p->quantite) ? $p->quantite : 0;
                    $statut = ($quantite <= $stockMin) ? 'Commander' : 'Équilibre';


                @endphp

                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $p->reference }}</td>
                    <td>
                        <a href="#edit_pdt" data-toggle="modal" data-backdrop="false"
                           onclick="edit_pdt('{{ $p->id }}', '{{ sanitize($p->libelle) }}')">
                            <strong><span class="glyphicon glyphicon-edit"></span> {{ $p->libelle }}</strong>
                        </a>
                    </td>
                    <td>{{ $stockMin }}</td>
                    <td>{{ $quantite }}</td>
                    <td>{{ $obs }}</td>
                    <td>{{ $statut }}</td>
                    {{--
                                           <td>
                                          @if($sec)
                                                   <a href="#edit_pdt2" data-toggle="modal" data-backdrop="false"
                                                      onclick="edit_pdt2({{ json_encode($sec->id_produit) }}, {{ json_encode($stockMin) }}, {{ json_encode($obs) }})"
                                                   >
                                                       <strong><span class="glyphicon glyphicon-edit"></span> Modifier</strong>
                                                   </a>
                                              <a href="#edit_pdt2" data-toggle="modal" data-backdrop="false"
                                                  onclick="edit_pdt2('{{ $sec->id_produit }}','{{ $stockMin }}','{{ $obs  }}')">
                                                   <strong><span class="glyphicon glyphicon-edit"></span> Modifier</strong>
                                               </a>
                    @else
                        <span class="text-muted">Non défini</span>
                        @endif
                        </td>--}}
                </tr>
            @endforeach

            </tbody>
        </table>




        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $produit->render()) ); ?></div>




        <div class="modal fade" id="edit_pdt">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Please fill the fields</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" id="edit_produit_form" role="form" method="POST" action="{{route('securite_update_management',$p->id)}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="edit_id" name="id_produit" readonly>                            <fieldset>

                                {{--<div class="form-group">
                                    <label for="edit_libelle">Nom du produit : </label>
                                    <input class="form-control" id="edit_libelle" name="libelle" type="text"  required >

                                </div>--}}
                                <div class="form-group">
                                    <label for="observation">obervation : </label>
                                    <input class="form-control" id="observation" name="observation" type="text"   >

                                </div>
                                <div class="form-group">
                                    <label for="stock_minimum">Stock minimum: </label>
                                    <input class="form-control" id="stock_minimum" name="stock_minimum" type="text" step="0.01"  required >

                                </div>

                                <button class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Edit</button>


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

    </div>
    <div class="modal fade" id="edit_pdt2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Remplir le formulaire</h4>
                </div>

                <div class="modal-body">
                    <form id="edit_produit_form1" method="POST" action="">
                        @csrf
                        <input type="hidden" name="_method" value="POST">


                        <input type="number" id="edit_id" name="id_produit" readonly class="form-control" style="margin-bottom:10px;">

                        <div class="form-group">
                            <label for="observation">Observation :</label>
                            <input class="form-control" id="observation" name="observation" type="text">
                        </div>

                        <div class="form-group">
                            <label for="stock_minimum">Stock minimum :</label>
                            <input class="form-control" id="stock_minimum" name="stock_minimum" type="number" step="0.01" required>
                        </div>

                        <button class="btn btn-primary pull-right">
                            <span class="glyphicon glyphicon-pencil"></span> Modifier
                        </button>
                    </form>
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
        function edit_pdt(id,libelle) {
            $('#edit_id').val(id);
            $('#edit_libelle').val(libelle);
        }
        function edit_pdt2(id_produit, stock_minimum, observation) {
            $('#edit_id').val(id_produit);
            $('#stock_minimum').val(stock_minimum);
            $('#observation').val(observation);

            let url = "{{ route('securite_update_management1', ':id') }}";
            url = url.replace(':id', id_produit);
            $('#edit_produit_form1').attr('action', url);
        }

        function imprimerCommande() {
            var contenu = document.getElementById('tableProduits').innerHTML;
            var original = document.body.innerHTML;
            document.body.innerHTML = contenu;
            window.print();
            document.body.innerHTML = original;
        }

        /*   function edit_pdt2(id_produit,stock_minimum,observation) {
                   $('#edit_id').val(id_produit);
                   $('#stock_minimum').val(stock_minimum);
                   $('#observation').val(observation);
               }*/
        function edit_pdt1(id,reference,libelle,id_categorie,quantite_minimale,prix,prix_gros,prix_semi_gros,prix_comptoir,prix_a,description) {

            //function edit_pdt(id,reference,libelle,id_categorie,quantite_minimale,prix,prix_a,description) {
            $('#edit_id').val(id);
            $('#edit_reference').val(reference);
            $('#edit_libelle').val(libelle);
            $('#edit_categorie').val(id_categorie);
            $('#edit_quantite_minimale').val(quantite_minimale);
            $('#edit_prix').val(prix);
            $('#edit_prix_gros').val(prix_gros);
            $('#edit_prix_semi_gros').val(prix_semi_gros);
            $('#edit_prix_comptoir').val(prix_comptoir);
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
@yield('styles')
<style>
    /* Garde les styles de Bootstrap à l'impression */
    @media print {
        body {
            font-size: 14px;
            color: #000;
            background: #fff;
        }
        table {
            border-collapse: collapse !important;
            width: 100%;
        }
        table th, table td {
            border: 1px solid #000 !important;
            padding: 6px;
            text-align: left;
        }
        .btn, .no-print {
            display: none !important; /* Masquer les boutons à l'impression */
        }
    }
</style>
@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
