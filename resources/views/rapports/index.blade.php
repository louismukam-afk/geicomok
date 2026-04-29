@extends('skeleton')
@section('content')

    <div class="row text-center pad-top">


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#rapport_ventes" data-toggle="modal" >
                    <i class="fa fa-book fa-5x"></i>
                    <h4>Rapport des ventes </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="#rapport_stocks" data-toggle="modal">
                    <i class="fa fa-book fa-5x"></i>
                    <h4>Rapport des stocks </h4>
                </a>
            </div>

        </div>






    </div>


    <div class="modal fade" id="rapport_ventes">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Selectionner la période: </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_ventes" method="get" action="{{route('rapport_ventes')}}">
                        <fieldset style="">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de début: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input type="date" name="date_debut" class="form-control">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de fin: </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        <input type="date" name="date_fin" class="form-control">

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

<div class="modal fade" id="rapport_stocks">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">Selectionner la période: </h4>
            </div>
            <div class="modal-body">

                <form accept-charset="UTF-8" role="form" id="form_ventes" method="get" action="{{route('rapport_stocks')}}">
                    <fieldset style="">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de début: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input type="date" name="date_debut" class="form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de fin: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input type="date" name="date_fin" class="form-control">

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
        <li class="active">Rapports</li>
    </ol>
@endsection
