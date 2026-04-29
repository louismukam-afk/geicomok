@extends('skeleton')
@section('content')
<div class="alert alert-warning">
    <h4>@lang('admin.total_entrees') : <strong>{{number_format($entrees).' '.trans('inscription.devise')}}</strong></h4>
    <h4>@lang('admin.total_sorties') : <strong>{{number_format($sorties).' '.trans('inscription.devise')}}</strong></h4>
    <h4>@lang('admin.solde_caisse') : <strong>{{number_format($entrees-$sorties).' '.trans('inscription.devise')}}</strong></h4>
</div>
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('admin/retrait/')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>Effectuer une depense </h4>
                </a>
            </div>

        </div>
       {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('comptabilite/payement-salaire/')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>{{trans('admin.payer_personnel')}} </h4>
                </a>
            </div>

        </div>--}}

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/comptabilite/bilan/entrees')}}" >
                    <i class="fa fa-download fa-5x"></i>
                    <h4>Bilan des entrées </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/comptabilite/bilan/sorties')}}" >
                    <i class="fa fa-upload fa-5x"></i>
                    <h4>Bilan des sorties </h4>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/comptabilite/categorie/')}}" >
                    <i class="fa fa-bank fa-5x"></i>
                    <h4>Catégories Budgétaires </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <div class="div-square">
                    <a href="{{route('index_ligne')}}" >
                        <i class="fa fa-behance-square fa-5x"></i>
                        <h4>Lignes Budgétaires </h4>
                    </a>
                </div>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <div class="div-square">
                    <a href="{{route('personnel_management')}}" >
                        <i class="fa fa-behance-square fa-5x"></i>
                        <h4>Personnels </h4>
                    </a>
                </div>
            </div>

        </div>

    </div>
    @endsection
@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('dashboard')}}"><strong>Accueil</strong></a></li>
        <li class="active">Comptabilite</li>
    </ol>
@endsection
