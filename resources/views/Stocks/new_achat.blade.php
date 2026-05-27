@extends('skeleton')
@section('content')
    @include('perso.functions')


    <div class="vente-container">

        <div class="container-fluid">
            <div id="demo"></div>
            <input type="hidden" value="1" id="numero-produit">
            <form action="{{route('store_achat')}}" id="form-facture" method="post">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-6 col-sm-10 col-xs-10">
                        <h4>Ajouter un produit :</h4>
                        <input type="text" id="myInput" onkeyup="findProduct()" placeholder="Tapez un nom"  onfocus="" >

                        <ul id="myUL" style="max-height: 450px;overflow-x: hidden;overflow-y: auto">


                        </ul>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                        <div class="btn btn-default btn-lg" style="margin-top: 40px" onclick="hideAll()">X</div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12" style="padding-top: 15px;margin-bottom: 10px">
                        <div class="form-group">
                            <label for="client">Fournisseur: </label>
                            <select name="fournisseur" id="fournisseur" form="form-facture" class="form-control" style="padding: 8px 20px 12px 20px;height: 40px">
                                @foreach($fournisseurs as $c )
                                    <option value="{{$c->id}}">{{$c->nom}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_caisse">Caisse de sortie: </label>
                            <select name="id_caisse" id="id_caisse" form="form-facture" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($caisses_sortie as $caisse)
                                    <option value="{{$caisse->id}}">{{$caisse->nom}} ({{number_format($caisse->solde(), 2)}})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <table class="table table-condensed table-striped" >
                    <thead class="thead-inverse">
                    <tr>
                        <th>Ref</th>
                        <th style="min-width: 250px">Produit</th>
                        <th style="width: 85px">Quantité</th>
                        <th style="width: 160px">Montant total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="body-facture">

                    </tbody>
                    <tbody>
                    <tr style="display: none">
                        <td colspan="4"><h4><strong>Réduction générale</strong></h4></td>
                        <td><div class="input-group">
                                <input type="number" step="0.05" value="0" min="0" id="reduction" name="reduction_generale" class="facture-input form-control" onchange="evaluateTotal()"/>
                                <div class="input-group-addon">%</div>
                            </div>
                        </td>
                    </tr>
                    @if($tva_achat)
                        @if($tva_achat->valeur==1)
                            @if($tva>0)
                                <tr >
                                    <td colspan="3"><h4><strong>TVA</strong></h4></td>
                                    <td colspan="2"><h4 class="pull-right"><strong><span id="tva">{{$tva}}</span> %</strong> ( <span id="tva-value">0</span> @lang('main.devise') )</h4></td>

                                </tr>
                            @endif
                        @endif
                    @endif

                    <tr>
                        <td colspan="4"><h4><strong>TOTAL</strong></h4></td>
                        <td ><h4><strong><span id="total">0</span> @lang('main.devise')</strong></h4></td>
                    </tr>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Date: </label>
                            <input type="date"  name="date" class="form-control" value="{{date('Y-m-d')}}">
                        </div>
                    </div>

                    <button class="btn btn-success pull-right" onclick="validateFacture();"><span class="glyphicon glyphicon-ok-sign"></span> Valider l'achat</button>
                </div>
            </form>

        </div>


    </div>

    <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_pdt"
    ><span class="glyphicon glyphicon-plus"></span> Nouveau produit</button>
    <div class="modal fade" id="add_pdt">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Please fill the fields</h4>
                </div>
                <div class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="formulaire" action="{{route('store_produit1')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <div class="form-group">
                                <label for="reference">Reférence : </label>
                                <input class="form-control" id="reference" name="reference" type="text"  value="{{ old('reference') }}"  AUTOFOCUS>

                            </div>

                            <div class="form-group">
                                <label for="libelle">Libelle : </label>
                                <input class="form-control" id="libelle" name="libelle" type="text"  value="{{ old('libelle') }}" required >
                            </div>
                            <div class="form-group">
                                <label for="id_categorie">Choisissez sa catégorie : <span class="required">*</span></label>
                                <select id="id_categorie" name="id_categorie" form="formulaire" class="form-control">

                                    @foreach($categories as $c)
                                        <option value="{{  $c->id  }}" @if($c->id == old('id_categorie')) selected @endif>{{$c->libelle}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="prix">Prix de vente: </label>
                                <input class="form-control" id="prix" name="prix" type="number" step="0.01" value="{{ old('prix') }}" required >

                            </div>

                            <div class="form-group">
                                <label for="quantite_minimale"> @lang('main.prix_min') : </label>
                                <input class="form-control" id="quantite_minimale" name="quantite_minimale" type="number"  step="0.01" value="{{ old('quantite_minimale') }}"  >

                            </div>

                            <div class="form-group">
                                <label for="prix_a">Prix d'achat: </label>
                                <input class="form-control" id="prix_a" name="prix_achat" type="number" step="0.01" value="{{ old('prix_achat') }}" required >

                            </div>

                            <div class="form-group">
                                <label for="description">Description :</label>
                                <textarea  class="form-control" id="description" name="description">{{ old('description') }}</textarea>


                            </div>



                            <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Add</button>

                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>

    <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_fournisseur"
    ><span class="glyphicon glyphicon-plus"></span> Nouveau Fournisseur</button>
    <div class="modal fade" id="add_fournisseur">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Please fill the fields</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" id="personnel_form" role="form" method="POST" action="{{route('store_fournisseur1')}}">
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


    <div class="edit_produit">
        <a href="{{route('liste_achats')}}"><H3 class="btn btn-warning pull-center">Imprimer la facture</H3></a>
    </div>
    <div class="edit_produit">
    <a href="{{route('produit_management1')}}"><H3 class="btn btn-warning pull-right">modifier un produit</H3></a>
    </div>

@endsection

@section('styles')
    <style>
        #myInput {
            background-image: url('{{asset('images/searchicon.png')}}'); /* Add a search icon to input */
            background-position: 10px 12px; /* Position the search icon */
            background-size: 25px 25px;
            background-repeat: no-repeat; /* Do not repeat the icon image */
            width: 100%; /* Full-width */
            font-size: 16px; /* Increase font-size */
            padding: 12px 20px 12px 40px; /* Add some padding */
            border: 1px solid #ddd; /* Add a grey border */
            margin-bottom: 12px; /* Add some space below the input */
        }

        @media(max-width: 505px){
            #myUL {

                width: 350px;


            }
        }

        #myUL {
            /* Remove default list styling */
            list-style-type: none;
            padding: 0;
            margin: 0;
            position: absolute;
            z-index: 2000;

        }

        #myUL li{
            /* Remove default list styling
          display: none;*/
        }

        #myUL li  {
            border: 1px solid #ddd; /* Add a border to all links */
            margin-top: -1px; /* Prevent double borders */
            background-color: #f6f6f6; /* Grey background color */
            padding: 12px; /* Add some padding */
            text-decoration: none; /* Remove default text underline */
            font-size: 18px; /* Increase the font-size */
            color: black; /* Add a black text color */
           /* display: none; /* Make it into a block element to fill the whole list */
        }

        #myUL li a:hover:not(.header) {
            background-color: #eee; /* Add a hover effect to all links, except for headers */
        }
    </style>
@endsection
@section('scripts')
    <script>
        var lockPattern;
        function hideAll(){
            var  ul, li;

            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName('li');
            $('#myInput').val('');
            for (i = 0; i < li.length; i++) {
                    li[i].style.display = "none";

            }
        }
        function myFunction() {
            // Declare variables
            var input, filter, ul, li, a, i;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByClassName("product-li");

            // Loop through all list items, and hide those who don't match the search query
            var name,category,reference,match;
            for (i = 0; i < li.length; i++) {
                match=false;

                name = li[i].getElementsByClassName("product-name")[0];
                category = li[i].getElementsByClassName("product-reference")[0];
                reference = li[i].getElementsByClassName("category-name")[0];
                if (name.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    match=true;
                }
                if (category.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    match=true;
                }
                if (reference.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    match=true;
                }


                if (match) {
                    li[i].style.display = "block";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
        function findProduct(){
            input=$('#myInput');
            contain=$('#myUL');
            contain.empty();
            lockPattern=input.val();
            if(input.val()){
                $.ajax({
                    url:'{{route('find_produit')}}',
                    type:'GET',
                    data:{
                        pattern: input.val()
                    },
                    success:function(data){
                        if(lockPattern===data['pattern']){
                            produits=data['produits'];
                            str="";

                            for(i=0;i<produits.length;i++){
                                produits[i].stock = produits[i].stock || {quantite: 0};
                                produits[i].categorie = produits[i].categorie || {libelle: ''};
                                str+= '<li class="product-li">'+
                                    '<h4  style="margin-top: 0;margin-bottom: 0"><strong class="product-name">'+ produits[i].libelle +' <small style="color: grey" class="product-reference">\ '+ (produits[i].reference?produits[i].reference:"") +'</small></strong></h4>'+
                                    '<hr style="margin-top: 10px;margin-bottom: 5px;">'+
                                    '<div class="product-detail row" style="padding-left: 20px">'+
                                    '<div class="col-md-6">'+
                                    ' <h4 class="product-quantite">Qté en stock : <strong> '+ produits[i].stock.quantite +'</strong></h4>'+

                                    '</div>'+
                                    '<small style="color: grey"><strong class="category-name">' + produits[i].categorie.libelle + '</strong></small>'+
                                    '<div class="btn btn-success pull-right" style="margin-right: 5px" onclick="addProduct('+ produits[i].id +',\'' + sanitize(produits[i].libelle) + '\',\''+ sanitize(produits[i].reference) +'\','+ produits[i].prix_achat +','+ produits[i].stock.quantite +');hideAll()"><span class="glyphicon glyphicon-plus"></span> Ajouter</div>'+
                                    '</div>'+
                                    '</li>';

                            }

                            contain.html(str);


                        }
                        }
                       ,
                    error:function () {
                        console.log('Une erreur est survenue');
                    }

                })
            }

        }

        function addProduct(id,libelle,reference,prix,stock) {
            var n,numEl;
            numEl=$('#numero-produit');
            n=parseInt(numEl.val());
            $('#body-facture').append('<tr id="row-produit'+ n +'">' +
                '<td><h4>'+ reference +'</h4></td>' +
                '<td><h4><strong class="text-uppercase">'+ libelle +'</strong></h4><input type="hidden" name="id[]" value="'+ id +'" /><span id="prix'+ n +'" style="display:none">'+ prix +'</span></td>' +
                '<td><input type="number" step="1" value="1" id="quantite'+ n +'" min="1"  name="quantite[]" class="form-control" onkeyup="kevaluateMiniTotal(event,'+ n +')"  onchange="evaluateMiniTotal('+ n +')" required /></td>' +
                '<td><h4><span id="minitotal'+ n +'" class="minitotal">'+ prix +'</span> @lang('main.devise')</h4></td>' +
                '<td> <span class="btn btn-xs btn-danger" onclick="removeProduct(\'row-produit'+ n +'\')"><span class="glyphicon glyphicon-trash"></span> </span>  </td>' +
                '</tr>');
            numEl.val(n+1);
            evaluateTotal();
            //hideAll();
        }
        function removeProduct(rowId) {
            $('#'+rowId).remove();
            evaluateTotal();

        }
        function evaluateMiniTotal(n) {
            var prixEl,quantiteEl,reductionEl,minitotalEl;
            prixEl=$('#prix'+n);
            quantiteEl=$('#quantite'+n);
            reductionEl=$('#reduction'+n);
            minitotalEl=$('#minitotal'+n);
            var p,q,r,m;
            p=parseFloat(prixEl.html());
            q=parseFloat(quantiteEl.val());
            r=parseFloat(reductionEl.val());
            if(!q)
                q=0;
            if(!r)
                r=0;
            m=(p-r)*q;

            minitotalEl.html(m.toFixed(2));
            evaluateTotal();



        }
        function evaluateTotal() {
            var total=0;
            $('.minitotal').each(function () {
                total+=parseFloat(this.innerHTML)
            });

            var r=parseFloat($('#reduction').val());
            var tva=parseFloat($('#tva').html());
            if(!tva)
                tva=0;
            if(!r)
                r=0;

            total=total-(total*r/100);
            var tvaVal=total*tva/100;

            total=total+(tvaVal);
            $('#tva-value').html(tvaVal.toFixed(2));


            $('#total').html(total.toFixed(2));
        }


        function kevaluateMiniTotal(e,n) {
            var keyCode = e.keyCode || e.which;
            //if (keyCode === 13) {
                var prixEl,quantiteEl,reductionEl,minitotalEl;
                prixEl=$('#prix'+n);
                quantiteEl=$('#quantite'+n);
                reductionEl=$('#reduction'+n);
                minitotalEl=$('#minitotal'+n);
                var p,q,r,m;
                p=parseFloat(prixEl.html());
                q=parseFloat(quantiteEl.val());
                r=parseFloat(reductionEl.val());

                if(!q)
                    q=0;
                if(!r)
                    r=0;
                m=(p-r)*q;

                minitotalEl.html(m.toFixed(2));
                evaluateTotal();
                return false;
            //}




        }



        $('#form-facture').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        $('.facture-input').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                evaluateTotal();
                return false;
            }
        });

        function sanitize(str) {
            str=str.replace(/\'/g,'\\\'');
            str=str.replace(/\n/g,'\\n');
            return str;
        }
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
