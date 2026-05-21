@extends('skeleton')
@section('content')
    @include('perso.functions')


    <div class="vente-container">

        <div class="container-fluid">
            <div id="demo"></div>
            <input type="hidden" value="1" id="numero-produit">
            <form action="{{route('store_vente')}}" id="form-facture" method="post">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-6 col-sm-10 col-xs-10">
                        <h4>Ajouter un produit :</h4>
                        <input type="text" id="myInput" onkeyup="findProduct()" placeholder="Tapez un nom"  onfocus="" >

                        <ul id="myUL" style="max-height: 400px;overflow-x: hidden;overflow-y: auto">


                        </ul>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                        <div class="btn btn-default btn-lg" style="margin-top: 40px" onclick="hideAll()">X</div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12" style="padding-top: 15px;margin-bottom: 10px">
                        <div class="form-group">
                            <label for="client">Client: </label>
                            <select name="client" id="client" form="form-facture" class="form-control" style="padding: 8px 20px 12px 20px;height: 40px">
                                @foreach($clients as $c )
                                    <option value="{{$c->id}}">{{$c->nom}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_caisse">Caisse d'entree: </label>
                            <select name="id_caisse" id="id_caisse" form="form-facture" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($caisses_entree as $caisse)
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
                        <th>Prix</th>
                        <th style="width: 85px">Quantité</th>
                        <th style="width: 125px">Réduction</th>
                        <th style="width: 125px">Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="body-facture">

                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="5"><h4><strong>Réduction générale</strong></h4></td>
                        <td><div class="input-group">
                                <input type="number" step="0.05" value="0" min="0" id="reduction" name="reduction_generale" class="facture-input form-control" onchange="evaluateTotal()"/>
                                {{--<div class="input-group-addon">%</div>--}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><h4><strong>TVA</strong></h4></td>
                        <td colspan="2"><h4 class="pull-right"><strong><span id="tva">{{$tva}}</span> %</strong> ( <span id="tva-value">0</span> @lang('main.devise') )</h4></td>

                    </tr>
                    <tr>
                        <td colspan="5"><h4><strong>TOTAL</strong></h4></td>
                        <td ><h4><strong><span id="total">0</span> @lang('main.devise')</strong></h4></td>
                    </tr>
                    </tbody>
                </table>
                <div class="row-fluid">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date: </label>
                                <input type="date"  name="date" class="form-control" value="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Montant verse: </label>
                                <input id="mPaye" type="number"  step="0.001" name="montant_verse" class="form-control"  onkeyup="manageReste()" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numero facture manuel: </label>
                                <input id="numfacture_manuel" type="text"  name="numfacture_manuel" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 id="mReste"><span id="mReste-text">{{trans('main.reste')}} :</span> <span id="mReste-val"></span></h4>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success pull-right">
                        <span class="glyphicon glyphicon-ok-sign"></span> Valider la facture
                    </button>


                    {{--
                                        <button class="btn btn-success pull-right" onclick="validateFacture();"><span class="glyphicon glyphicon-ok-sign"></span> Valider la facture</button>
                    --}}
                </div>
            </form>

        </div>


    </div>
    {{--//code pour ajouter un nouveau client--}}

    <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_client"
    ><span class="glyphicon glyphicon-plus"></span> Nouveau client</button>
    {{--code pour ajouter un nouveau client--}}

    <div class="modal fade" id="add_client">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Please fill the fields</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" id="personnel_form" role="form" method="POST" action="{{route('store_client1')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>

                            <div class="form-group">
                                <label for="nom">Noms : </label>
                                <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required AUTOFOCUS>

                            </div>
                            <div class="form-group">
                                <label for="telephone">Numéro(s) de téléphone(s) : </label>
                                <input class="form-control" id="telephone" name="telephone" type="text"  value="{{ old('telephone') }}"  >

                            </div>
                            <div class="form-group">
                                <label for="ville">Ville : </label>
                                <input class="form-control" id="ville" name="ville" type="text"  value="{{ old('ville') }}"  >

                            </div>

                            <div class="form-group">
                                <label for="adresse">Adresse : </label>
                                <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ old('adresse') }}"  >

                            </div>
                            <div class="form-group">
                                <label for="email">Email : </label>
                                <input class="form-control" id="email" name="email" type="email"  value="{{ old('email') }}"  >

                            </div>
                            <div class="form-group">
                                <label for="boite_postale">Boite postale : </label>
                                <input class="form-control" id="boite_postale" name="boite_postale" type="text"  value="{{ old('boite_postale') }}"  >

                            </div>

                            <div class="form-group">
                                <label for="id_pays">Pays d'origine : <span class="required">*</span></label>
                                <select id="id_pays" name="id_pays" form="personnel_form" class="form-control">

                                    @foreach($pays as $c)
                                        <option value="0"> --- Choisir un pays --- </option>
                                        <option value="{{  $c->id  }}" @if($c->id == old('id_pays')) selected @endif>{{$c->nom}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Ajouter</button>

                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
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



        #myUL {
            /* Remove default list styling */
            list-style-type: none;
            padding: 0;
            margin: 0;
            width: 450px;
            position: absolute;
            z-index: 2000;

        }

        @media(max-width: 505px){
            #myUL {

                width: 350px;


            }
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
            /*display: none; /* Make it into a block element to fill the whole list */
        }

        #myUL li a:hover:not(.header) {
            background-color: #eee; /* Add a hover effect to all links, except for headers */
        }
    </style>
@endsection
@section('scripts')
    <script>
        var lockPattern;

        function manageReste(){
            total=$('#total');
            paye=$('#mPaye');
            mResteT=$('#mReste-text');
            mResteV=$('#mReste-val');
            vTotal=parseFloat(total.html());
            vPaye=parseFloat(paye.val());

            if(!vPaye)
                vPaye=0;
            if(!vTotal)
                vTotal=0;

            vReste=vPaye-vTotal;

            if(vReste>=0){
                mResteT.html('{{trans('main.reste')}} :');
                mResteV.html(vReste+' {{trans('main.devise')}}');
            }
            else {
                mResteT.html('{{trans('main.reste_ap')}} :');
                mResteV.html((vReste* -1)+' {{trans('main.devise')}}');
            }

        }

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

                                // On ajoute les prix multiples dans le JSON produit (prix_gros, prix_semi_gros, prix_comptoir)
                                str+= '<li class="product-li">'+
                                    '<h4  style="margin-top: 0;margin-bottom: 0"><strong class="product-name">'+ produits[i].libelle +' <small style="color: grey" class="product-reference">\\ '+ (produits[i].reference?produits[i].reference:"") +'</small></strong></h4>'+
                                    '<hr style="margin-top: 10px;margin-bottom: 5px;">'+
                                    '<div class="product-detail row" style="padding-left: 20px">'+
                                    '<div class="col-md-6">'+
                                    ' <h4 class="product-quantite">Qté en stock : <strong> '+ produits[i].stock.quantite +'</strong></h4>'+

                                    '</div>'+
                                    '<div class="col-md-6">'+
                                    '<h4 class="product-prix">Prix detail : <strong> '+ produits[i].prix +' @lang('main.devise')</strong></h4>'+
                                    '<h4 class="product-prix">Prix de gros : <strong> '+ produits[i].prix_gros +' @lang('main.devise')</strong></h4>'+
                                    '<h4 class="product-prix">Prix semi-gros : <strong> '+ produits[i].prix_semi_gros +' @lang('main.devise')</strong></h4>'+
                                    '<h4 class="product-prix">Prix comptoir : <strong> '+ produits[i].prix_comptoir +' @lang('main.devise')</strong></h4>'+

                                    '</div>'+
                                    '<small style="color: grey"><strong class="category-name">' + produits[i].categorie.libelle + '</strong></small>'+
                                    '<div class="btn btn-success pull-right" style="margin-right: 5px" onclick="addProduct('+ produits[i].id +',\'' + sanitize(produits[i].libelle) + '\',\''+ sanitize(produits[i].reference) +'\','+ produits[i].prix +','+ produits[i].stock.quantite +','+ produits[i].prix_minimum +','+ produits[i].prix_gros +','+ produits[i].prix_semi_gros +','+ produits[i].prix_comptoir +');hideAll()"><span class="glyphicon glyphicon-plus"></span> Ajouter</div>'+
                                    '</div>'+
                                    '</li>';

                            }

                            contain.html(str);


                        }


                    },
                    error:function () {
                        console.log('Une erreur est survenue');
                    }

                })
            }

        }
        function addProduct(id, libelle, reference, prix, stock, prix_min, prix_gros, prix_semi_gros, prix_comptoir) {
            var n, numEl;
            numEl = $('#numero-produit');
            n = parseInt(numEl.val());

            // Select + data-tarif sur chaque option
            let prixSelect =
                '<select id="prixSelect'+ n +'" class="form-control" onchange="onChangePrix('+ n +')">' +
                '<option value="'+prix+'" data-tarif="detail">Prix détail: '+prix+' @lang("main.devise")</option>' +
                '<option value="'+prix_comptoir+'" data-tarif="comptoir">Comptoir: '+prix_comptoir+' @lang("main.devise")</option>' +
                '<option value="'+prix_semi_gros+'" data-tarif="semi_gros">Semi-gros: '+prix_semi_gros+' @lang("main.devise")</option>' +
                '<option value="'+prix_gros+'" data-tarif="gros">Gros: '+prix_gros+' @lang("main.devise")</option>' +
                '</select>';

            $('#body-facture').append(
                '<tr id="row-produit'+ n +'">' +
                '<td><h4>'+ reference +'</h4></td>' +
                '<td><h4><strong class="text-uppercase">'+ libelle +'</strong></h4>' +
                '<input type="hidden" name="id[]" value="'+ id +'" />' +
                '</td>' +
                '<td>'+ prixSelect +
                // Champs cachés qui seront soumis AU SERVEUR
                '<input type="hidden" id="prixUnitaire'+ n +'" name="prix_unitaire[]" value="'+ prix +'">' +
                '<input type="hidden" id="tarif'+ n +'" name="tarif[]" value="detail">' +
                '</td>' +
                '<td><input type="number" step="1" value="1" id="quantite'+ n +'" min="0" max="'+ stock +'" name="quantite[]" class="form-control" onkeyup="kevaluateMiniTotal(event,'+ n +')" onchange="evaluateMiniTotal('+ n +')" required /></td>' +
                '<td><div class=""><input type="number" step="0.01" value="0" min="0" max="'+ (prix - prix_min) +'" id="reduction'+ n +'" name="reduction[]" class="form-control" onkeyup="kevaluateMiniTotal(event,'+ n +')" onchange="evaluateMiniTotal('+ n +')" /></div></td>' +
                '<td><h4><span id="minitotal'+ n +'" class="minitotal">'+ prix +'</span> @lang("main.devise")</h4></td>' +
                '<td><span class="btn btn-xs btn-danger" onclick="removeProduct(\'row-produit'+ n +'\')"><span class="glyphicon glyphicon-trash"></span></span></td>' +
                '</tr>'
            );
            numEl.val(n+1);

            // initialise minitotal
            evaluateMiniTotal(n);
        }
        function onChangePrix(n){
            // Met à jour le hidden prix_unitaire + tarif en fonction de l’option sélectionnée
            var sel = $('#prixSelect' + n + ' option:selected');
            var p   = parseFloat(sel.val()) || 0;
            var t   = sel.data('tarif') || 'detail';

            $('#prixUnitaire' + n).val(p.toFixed(2));
            $('#tarif' + n).val(t);

            evaluateMiniTotal(n);
        }

        function normalizeNumber(x){
            // sécurité si certaines valeurs arrivent "1 500,25" etc.
            if (typeof x === 'string') x = x.replace(/\s/g,'').replace(',','.');
            var v = parseFloat(x);
            return isNaN(v) ? 0 : v;
        }



        /* function addProduct(id,libelle,reference,prix,stock,prix_min, prix_gros, prix_semi_gros, prix_comptoir) {
             var n,numEl;
             numEl=$('#numero-produit');
             n=parseInt(numEl.val());

             // Construire le select des prix
             let prixSelect = '<select id="prixSelect'+ n +'" name="prix[]" class="form-control" onchange="evaluateMiniTotal('+ n +')">'+
                 '<option value="'+prix+'">prix detail: '+prix+' @lang("main.devise")</option>'+
                '<option value="'+prix_comptoir+'">Comptoir: '+prix_comptoir+' @lang("main.devise")</option>'+
                '<option value="'+prix_semi_gros+'">Semi-gros: '+prix_semi_gros+' @lang("main.devise")</option>'+
                '<option value="'+prix_gros+'">Gros: '+prix_gros+' @lang("main.devise")</option>'+
                '</select>';

            $('#body-facture').append('<tr id="row-produit'+ n +'">' +
                '<td><h4>'+ reference +'</h4></td>' +
                '<td><h4><strong class="text-uppercase">'+ libelle +'</strong></h4><input type="hidden" name="id[]" value="'+ id +'" /></td>' +
                '<td>'+ prixSelect +'</td>' +
                '<td><input type="number" step="1" value="1" id="quantite'+ n +'" min="0" max="'+ stock +'" name="quantite[]" class="form-control" onkeyup="kevaluateMiniTotal(event,'+ n +')"  onchange="evaluateMiniTotal('+ n +')" required /></td>' +
                '<td><div class=""><input type="number" step="0.05" value="0" min="0"  max="'+ (prix-prix_min) +'" id="reduction'+ n +'" tabindex="-100" name="reduction[]" class="form-control" onkeyup="kevaluateMiniTotal(event,'+ n +')"  onchange="evaluateMiniTotal('+ n +')"/></div></td>' +
                '<td><h4><span id="minitotal'+ n +'" class="minitotal">'+ prix +'</span> @lang('main.devise')</h4></td>' +
                '<td> <span class="btn btn-xs btn-danger" onclick="removeProduct(\'row-produit'+ n +'\')"><span class="glyphicon glyphicon-trash"></span> </span>  </td>' +
                '</tr>');
            numEl.val(n+1);
            evaluateTotal();
            //hideAll();
        }*/
        function removeProduct(rowId) {
            $('#'+rowId).remove();
            evaluateTotal();

        }
/*        function evaluateMiniTotal1(n) {
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



        }*/
    /*    function evaluateMiniTotal(n) {
            var prixEl = $('#prixSelect' + n); // On récupère le select
            var quantiteEl = $('#quantite' + n);
            var reductionEl = $('#reduction' + n);
            var minitotalEl = $('#minitotal' + n);

            var p = parseFloat(prixEl.val()); // Prix sélectionné
            var q = parseFloat(quantiteEl.val()) || 0;
            var r = parseFloat(reductionEl.val()) || 0;

            var m = (p - r) * q;
            minitotalEl.html(m.toFixed(2));

            evaluateTotal();
        }*/

        function evaluateTotal() {
            var total=0;
            $('.minitotal').each(function () {
                total+=parseFloat(this.innerHTML)
            });

            var r=parseFloat($('#reduction').val());
            var tva=parseFloat($('#tva').html());
            if(!r)
                r=0;

//            total=total-(total*r/100);
            total=total-r;
            var tvaVal=total*tva/100;

            total=total+(tvaVal);
            $('#tva-value').html(tvaVal.toFixed(2));


            $('#total').html(total.toFixed(2));
            manageReste();
        }
       /* function kevaluateMiniTotal(e, n) {
            var prixEl = $('#prixSelect' + n);
            var quantiteEl = $('#quantite' + n);
            var reductionEl = $('#reduction' + n);
            var minitotalEl = $('#minitotal' + n);

            var p = parseFloat(prixEl.val());
            var q = parseFloat(quantiteEl.val()) || 0;
            var r = parseFloat(reductionEl.val()) || 0;

            var m = (p - r) * q;
            minitotalEl.html(m.toFixed(2));

            evaluateTotal();
            return false;
        }*/
        function evaluateMiniTotal(n) {
            var sel = $('#prixSelect' + n + ' option:selected');
            var p   = normalizeNumber(sel.val());
            var q   = normalizeNumber($('#quantite' + n).val());
            var r   = normalizeNumber($('#reduction' + n).val());

            var m = (p - r) * q;
            $('#minitotal' + n).html(m.toFixed(2));

            // synchronise le hidden (critique !)
            $('#prixUnitaire' + n).val(p.toFixed(2));

            evaluateTotal();
        }

        function kevaluateMiniTotal(e, n) {
            evaluateMiniTotal(n);
            return false;
        }
        $('#form-facture').on('submit', function(){
            // force 2 décimales vers le backend
            $('input[name="prix_unitaire[]"]').each(function(){ this.value = normalizeNumber(this.value).toFixed(2); });
            $('input[name="reduction[]"]').each(function(){ this.value = normalizeNumber(this.value).toFixed(2); });
        });



        /*  function kevaluateMiniTotal(e,n) {
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
  */


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



        /*function sanitize(str) {
            str=str.replace(/\'/g,'\\\'');
            str=str.replace(/\n/g,'\\n');
            return str;
        }*/
        function sanitize(str) {
    str = String(str || ""); // évite null/undefined
    str = str.replace(/\'/g, "\\'");
    str = str.replace(/\n/g, "\\n");
    return str;
}

    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('ventes')}}"><strong>Ventes</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
