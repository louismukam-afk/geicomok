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

                    </div>
                </div>
                <table class="table table-condensed table-striped" >
                    <thead class="thead-inverse">
                    <tr>
                        <th>Ref</th>
                        <th style="min-width: 250px">Produit</th>
                        <th>Prix</th>
                        <th style="width: 85px">Quantité</th>
                        <th style="width: 125px">Total</th>
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

                                str+= '<li class="product-li">'+
                                    '<h4  style="margin-top: 0;margin-bottom: 0"><strong class="product-name">'+ produits[i].libelle +' <small style="color: grey" class="product-reference">\ '+ (produits[i].reference?produits[i].reference:"") +'</small></strong></h4>'+
                                    '<hr style="margin-top: 10px;margin-bottom: 5px;">'+
                                    '<div class="product-detail row" style="padding-left: 20px">'+
                                    '<div class="col-md-6">'+
                                    ' <h4 class="product-quantite">Qté en stock : <strong> '+ produits[i].stock.quantite +'</strong></h4>'+

                                    '</div>'+
                                    '<div class="col-md-6">'+
                                    '<h4 class="product-prix">Prix : <strong> '+ produits[i].prix_achat +' @lang('main.devise')</strong></h4>'+

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
                '<td><h4><strong class="text-uppercase">'+ libelle +'</strong></h4><input type="hidden" name="id[]" value="'+ id +'" /></td>' +
                '<td><h4><span id="prix'+ n +'">'+ prix +'</span> @lang('main.devise')</h4></td>' +
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