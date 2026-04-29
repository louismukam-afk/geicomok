@extends('skeleton')
@section('content')

    <div class="col-md-12">

        <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th>Message</th>
                <th>Date</th>
            </tr>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($logs as $l)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$l->message}}</td>
                    <td>{{(new DateTime($l->created_at))->format('d-m-Y H:i:s')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $logs->render()) ); ?></div>




    </div>

@endsection



@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">

        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
