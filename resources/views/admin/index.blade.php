@extends('skeleton')
@section('content')

    <div class="row text-center pad-top">


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('categorie_management')}}" >
                    <i class="fa fa-vimeo-square fa-5x"></i>
                    <h4>Categories </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('produit_management')}}" >
                    <i class="fa fa-table fa-5x"></i>
                    <h4>Produits </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('fournisseur_management')}}" >
                    <i class="fa fa-phone fa-5x"></i>
                    <h4>Fournisseurs </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('stock_management')}}" >
                    <i class="fa fa-folder-open fa-5x"></i>
                    <h4>Stocks </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('securite_stock_management')}}" >
                    <i class="fa fa-folder-open fa-5x"></i>
                    <h4>Stocks Minimum </h4>
                </a>
            </div>

        </div>


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('personnel_management')}}" >
                    <i class="fa fa-folder-open fa-5x"></i>
                    <h4>personnels </h4>
                </a>
            </div>

        </div>

    </div>

    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('pays_management')}}" >
                    <i class="fa fa-dot-circle-o fa-5x"></i>
                    <h4>Pays </h4>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6" style="display: none">
            <div class="div-square">
                <a href="{{route('personnel_management')}}" >
                    <i class="fa fa-file-powerpoint-o fa-5x"></i>
                    <h4>Personnels </h4>
                </a>
            </div>

        </div>


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('client_management')}}" >
                    <i class="fa fa-mobile fa-5x"></i>
                    <h4>Clients </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6" style="display: none">
            <div class="div-square">
                <a href="{{route('salaire_management')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>Salaires </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('email_management')}}" >
                    <i class="fa fa-envelope fa-5x"></i>
                    <h4>Emails de contact </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('user_management')}}" >
                    <i class="fa fa-users fa-5x"></i>
                    <h4>Utilisateurs </h4>
                </a>
            </div>

        </div>









    </div>

    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('boutique_management')}}" >
                    <i class="fa fa-university fa-5x"></i>
                    <h4>@lang('main.boutique') / @lang('main.magasin') </h4>
                </a>
            </div>

        </div>


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('show_log')}}" >
                    <i class="fa fa-archive fa-5x"></i>
                    <h4>Voir les logs </h4>
                </a>
            </div>

        </div>
    </div>


@endsection