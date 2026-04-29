@extends('skeleton')
@section('content')
    <?php $cb=session('current_boutique') ?>


    <div class="row text-center pad-top">

        @if($cb!=null)

        @if($cb->type==1)

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('ventes')}}" >
                    <i class="fa fa-shopping-cart fa-5x"></i>
                    <h4>Gestion des ventes </h4>
                </a>
            </div>

        </div>
        @endif
        @endif

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('stocks')}}" >
                    <i class="fa fa-square fa-5x"></i>
                    <h4>Gestion des stocks </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('index_rapport')}}" >
                    <i class="fa fa-book fa-5x"></i>
                    <h4>Rapports </h4>
                </a>
            </div>

        </div>


        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('dashboard')}}" >
                    <i class="fa fa-cog fa-5x"></i>
                    <h4>Administration </h4>
                </a>
            </div>

        </div>


    </div>
    <li>
    </li>

    <div class="row text-center pad-top">

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('comptabilite')}}">
                <i class="fa fa-maxcdn fa-5x"></i>
                <h4>Comptabilite </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
            <div class="div-square">
                <a href="{{route('management')}}" >
                    <i class="fa fa-bar-chart-o fa-5x"></i>
                    <h4>Statistiques  </h4>
                </a>
            </div>

        </div>


    </div>
@endsection