@extends('skeleton')
@section('content')
<div class="col-md-8">
    @if(isset($success))

    <div class="alert alert-success">
        {{  trans('admin.succes')  }}
    </div>

    @endif

    <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
        <thead>
        <caption style="font-size: 22px">{{  trans('admin.liste_paiement_personnel').' : '.$personnel->nom  }} </caption>
        <th>#</th>
        <th>{{  trans('inscription.numero')  }}</th>
        <th>{{  trans('inscription.montant')  }}</th>
        <th>Date</th>
        </thead>
        <tbody>
        <?php $i=1; ?>
        @foreach($payements as $p)
        <tr>
            <td>{{$i++}}</td>
            <td><strong >{{$p->numero}}</strong></td>
            <td> {{$p->montant}}</td>
            <td>{{$p->date_p}}</td>
            <td>

                        <a  class="btn btn-warning"  href="{{url('/comptabilite/payement-salaire/print/'.$p->id)}}" ><span class="glyphicon glyphicon-print"  ></span> {{  trans('inscription.print_receipe')  }}</a>

        </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-success" href="{{URL::to('/comptabilite/payement-salaire/create/').'/'.$personnel->id}}"><span class="glyphicon glyphicon-plus"></span>{{  trans('inscription.add_payement')  }}</a>
</div>
    @endsection

@section('breadcrumb')
    <li><strong><a href="{{url('comptabilite')}}">@lang('admin.comptabilite')</a></strong></li>
    <li><strong><a href="{{url('comptabilite/payement-salaire')}}">@lang('admin.search_personnel')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection