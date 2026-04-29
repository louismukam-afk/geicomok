@extends('skeleton')
@section('content')

    <?php   $cb=session('current_boutique'); ?>
    <div class="row text-center pad-top">


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#select_produit_hist" data-toggle="modal" >
                    <i class="fa fa-history fa-5x"></i>
                    <h4>Historique de Stocks </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('view_stock')}}" data-toggle="modal" >
                    <i class="fa fa-search fa-5x"></i>
                    <h4>Vérifier les stocks </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('nouvel_achat')}}" >
                    <i class="fa fa-euro fa-5x"></i>
                    <h4>Nouvel achat </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_achats" data-toggle="modal" >
                    <i class="fa fa-list-alt fa-5x"></i>
                    <h4>Récapitulatifs des achats </h4>
                </a>
            </div>

        </div>






    </div>

    <div class="row text-center pad-top">


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#select_produit_lie" data-toggle="modal" >
                    <i class="fa fa-table fa-5x"></i>
                    <h4>Détailler un produit </h4>
                </a>
            </div>

        </div>

        @if($cb->type==1)
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#select_magasin" data-toggle="modal" >
                    <i class="fa fa-download fa-5x"></i>
                    <h4>Approvisionnement </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_approvs" data-toggle="modal" >
                    <i class="fa fa-list-alt fa-5x"></i>
                    <h4>Récapitulatifs des approvisionnements </h4>
                </a>
            </div>

        </div>
        @endif
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('liste_fournisseurs')}}" >
                    <i class="fa fa-stack-overflow fa-5x"></i>
                    <h4>Liste des fournisseurs </h4>
                </a>
            </div>

        </div>
    </div>




    <div class="modal fade" id="select_produit_hist">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Selectionner un produit: </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" id="myInput" onkeyup="findProduct()" placeholder="Tapez un nom"  onfocus="" >

                            <ul id="myUL" style="max-height: 450px;overflow-x: hidden;overflow-y: auto">


                            </ul>
                        </div>
                    </div>


                </div>





            </div>


        </div>
    </div>



    <div class="modal fade" id="select_produit_lie">
        <div class="modal-dialog" style="width: 70%">
            <div class="modal-content" >
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Selectionner un produit: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_produit_lie" method="get" action="{{route('detailler_produit')}}">
                        <fieldset>

                            <div class="form-group">
                                <label >Produit : </label>
                                <select name="produit"  form="form_produit_lie" class="form-control" id="sel_produit_lie" required onchange="calcLiaison()">
                                    <option pl_libelle="------" pl_quantite="0" value="" stock="0" pl_stock="0">---- Sélectionner un produit ----</option>

                                    @foreach($produits as $p)
                                        @if($p->produit_lie)
                                            <option pl_libelle="{{$p->produit_lie->produit_c->libelle.' ( Stock: '.$p->produit_lie->produit_c->stock->quantite.' )'}}" pl_quantite="{{$p->produit_lie->quantite}}" stock="{{$p->stock->quantite}}" pl_stock="{{$p->produit_lie->produit_c->stock->quantite}}" value="{{$p->id}}" >{{$p->libelle}} ( Stock: {{$p->stock->quantite}} )</option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>
                            <h3>Changer: </h3>
                            <div class="col-md-12">
                                <h4><span id="multiplicateur-lie">1</span> x <span id="pp_lie"> ----- </span> <span class="fa fa-arrow-right"></span> <span id="stock-lie">0</span> x <span id="pc_lie">-----</span> </h4>
                            </div>
                            <div class="form-group">
                                <label>Quantité</label>
                                <input type="number" name="quantite" step="1" class="form-control" value="1" onkeyup="cMul()" onchange="cMul()"  id="multiplicat">
                            </div>

                            <input class="btn  btn-success " type="submit" value="confirmer">


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>


    <div class="modal fade" id="mod_list_achats">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_achats" method="get" action="{{route('liste_achats')}}">
                        <fieldset style="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fournisseur:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_achats" class="form-control" name="fournisseur" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($Fournisseurs as $c)
                                                <option value="{{$c->id}}">{{$c->nom}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de début: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input type="date" name="dd" class="form-control">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de fin: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input type="date" name="df" class="form-control">

                                    </div>
                                </div>
                            </div>


                            <input class="btn  btn-success " type="submit" value="confirmer">


                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="mod_list_approvs">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_approvs" method="get" action="{{route('liste_approvs')}}">
                        <fieldset style="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Magasin:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_approvs" class="form-control" name="magasin" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($magasins as $c)
                                                <option value="{{$c->id}}">{{$c->nom}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de début: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input type="date" name="dd" class="form-control">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de fin: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input type="date" name="df" class="form-control">

                                    </div>
                                </div>
                            </div>


                            <input class="btn  btn-success " type="submit" value="confirmer">


                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="select_magasin">
        <div class="modal-dialog" >
            <div class="modal-content" >
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Selectionner un magasin: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_sel_magasin" method="get" action="{{route('new_approv')}}">
                        <fieldset>

                            <div class="form-group">
                                <label >Magasin : </label>
                                <select name="magasin"  form="form_sel_magasin" class="form-control"  required >
                                    <option >---- Sélectionner un magasin ----</option>

                                    @foreach($magasins as $m)
                                            <option  value="{{$m->id}}" >{{$m->nom}} </option>
                                    @endforeach

                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="confirmer">


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
            width: 450px;
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


    function cMul() {
        var mul=$('#multiplicat');
        var m=parseInt(mul.val());
        $('#multiplicateur-lie').html(m);

        calcLiaison();
    }
    function calcLiaison() {
        var sel_item=$('#sel_produit_lie')[0];
        var opt_item=sel_item.options[sel_item.selectedIndex];

        $('#pp_lie').html(opt_item.innerHTML);
        $('#pc_lie').html(opt_item.getAttribute('pl_libelle'));


        var mul=$('#multiplicat').val();

        var m=parseInt(mul);
        var s=parseInt(opt_item.getAttribute('pl_quantite'));
        var t=m*s;
        $('#stock-lie').html(t);

    }


    var lockPattern;

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
                                '<div class="product-detail row" style="padding-left: 20px;padding-top:10px">'+


                                '<div class="btn btn-xs btn-success pull-right" style="margin-right: 5px" onclick="document.location.href=\'{{route('view_historique')}}?produit='+ produits[i].id +'\'"><span class="glyphicon glyphicon-plus"></span> Sélectionner</div>'+
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
</script>
@endsection


@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li class="active">Stocks</li>
    </ol>
@endsection
