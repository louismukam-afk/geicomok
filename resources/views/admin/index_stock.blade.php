@extends('skeleton')
@section('content')

<div class="row text-center pad-top">


    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
        <div class="div-square">
            <a href="{{route('securite_management')}}" >
                <i class="fa fa-vimeo-square fa-5x"></i>
                <h4>ENREGISTRER UN STOCK MINIMUM </h4>
            </a>
        </div>

    </div>

    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
        <div class="div-square">
            <a href="{{route('stock_minimum_management')}}" >
                <i class="fa fa-table fa-5x"></i>
                <h4>Gestion des Stocks Minimums </h4>
            </a>
        </div>

    </div>
</div>

@endsection