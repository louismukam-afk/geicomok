@extends('skeleton')
@section('content')

    <div class="row text-center pad-top">


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('new_vente')}}" >
                    <i class="fa fa-euro fa-5x"></i>
                    <h4>Nouvelle vente </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_ventes" data-toggle="modal" >
                    <i class="fa fa-list fa-5x"></i>
                    <h4>Récapitulatifs des ventes </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_ventes_connecte" data-toggle="modal" >
                    <i class="fa fa-user fa-5x"></i>
                    <h4>RÃ©capitulatif de mes ventes </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_ventes_p" data-toggle="modal" >
                    <i class="fa fa-list fa-5x"></i>
                    <h4>Recapitulatifs des produits avec les marges </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_ventes_u" data-toggle="modal" >
                    <i class="fa fa-list fa-5x"></i>
                    <h4>Recapitulatifs des ventes par utilisateurs </h4>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#mod_list_ventes_clt" data-toggle="modal" >
                    <i class="fa fa-list fa-5x"></i>
                    <h4>Recapitulatifs des ventes par ordre de Client </h4>
                </a>
            </div>

        </div>


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#select_produit_list" data-toggle="modal" >
                    <i class="fa fa-list fa-5x"></i>
                    <h4>Liste des produits </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('liste_clients')}}" >
                    <i class="fa fa-users fa-5x"></i>
                    <h4>Liste des clients </h4>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('bons_credit')}}" >
                    <i class="fa fa-credit-card fa-5x"></i>
                    <h4>Bons de credit </h4>
                </a>
            </div>

        </div>








    </div>



    <div class="row text-center pad-top">

    </div>

    <div class="modal fade" id="mod_list_ventes_p">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_ventes" method="get" action="{{route('liste_ventes_produit')}}">
                        <fieldset style="">
                            {{--  <div class="col-md-12">
                                  <div class="form-group">
                                      <label>caissière:  </label>
                                      <div class="input-group">
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                          <select form="form_list_ventes" class="form-control" name="client" >
                                              <option value="0">   @lang('main.tous')  </option>
                                              @foreach($utilisateur as $u)
                                                  <option value="{{$u->id}}">{{$u->name}}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                              </div>--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Client:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_ventes" class="form-control" name="client" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($Clients as $c)
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

    <div class="modal fade" id="mod_list_ventes_u">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_ventes_u" method="get" action="{{route('liste_ventes_user')}}">
                        <fieldset style="">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <label>caissière:  </label>
                                      <div class="input-group">
                                          <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                          <select form="form_list_ventes_u" class="form-control" name="user" >
                                              <option value="0">   @lang('main.tous')  </option>
                                              @foreach($utilisateur as $u)
                                                  <option value="{{$u->id}}">{{$u->name}}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                              </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Client:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_ventes_u" class="form-control" name="client" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($Clients as $c)
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

    <div class="modal fade" id="mod_list_ventes_clt">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_ventes_clt" method="get" action="{{route('liste_ventes_clients')}}">
                        <fieldset style="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>caissière:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_ventes_clt" class="form-control" name="user" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($utilisateur as $u)
                                                <option value="{{$u->id}}">{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Client:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_ventes_clt" class="form-control" name="client" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($Clients as $c)
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


    <div class="modal fade" id="mod_list_ventes">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_ventes1" method="get" action="{{route('liste_ventes')}}">
                        <fieldset style="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Client:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_ventes1" class="form-control" name="client" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($Clients as $c)
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


    <div class="modal fade" id="mod_list_ventes_connecte">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la pÃ©riode: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_list_ventes_connecte" method="get" action="{{route('liste_ventes_connecte')}}">
                        <fieldset style="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Client:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <select form="form_list_ventes_connecte" class="form-control" name="client" >
                                            <option value="0">   @lang('main.tous')  </option>
                                            @foreach($Clients as $c)
                                                <option value="{{$c->id}}">{{$c->nom}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de dÃ©but: </label>
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


    <div class="modal fade" id="select_produit_list">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">X</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="select_liste_produit"  method="get" action="{{route('liste_produits')}}">
                        <fieldset style="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fournisseur:  </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span>
                                        <select form="select_liste_produit" class="form-control" name="mode" >
                                            <option value="0">   Par nom  </option>
                                            <option value="1">   Par categorie  </option>

                                        </select>
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

@endsection



@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li class="active">Ventes</li>
    </ol>
@endsection
