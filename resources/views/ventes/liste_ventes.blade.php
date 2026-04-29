@extends('skeleton')
@section('content')
    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

    <div class="col-md-12">

        <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

        @if(isset($client))
            <h3>Client: <strong>{{$client->nom}}</strong></h3>
        @endif
            <br>
            <h3>Récapitulatif des ventes: Du {{(new DateTime($dd))->format('d/m/Y')}} au {{(new DateTime($df))->format('d/m/Y')}}</h3>


            <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th class="numero">Numéro</th>
                <th>Client</th>
                <th>Montant</th>
                <th>@lang('main.m_verse')</th>
                <th>Date</th>
                <th class="etat">Etat</th>
                <th class=""></th>
                <th class="">Action</th>

            </tr>

            </thead>
            <tbody>
            <?php $i=1;$total=0;$verse=0; ?>
            @foreach($factures as $f)
                <?php $total+=$f->total;$verse+=$f->verse ?>
                <tr>

                    <td>{{$i++}}</td>
                    <td class="facture"><a  href="{{route('details_ventes',$f->id)}}" class="facture"><strong class="@if($f->paye==0) text-danger @endif" ><span class="glyphicon glyphicon-arrow-up"></span> N° {{$f->numero}}</strong></a></td>
                    <td>@if($f->client){{$f->client->nom}} @endif</td>
                    <td><strong>{{number_format($f->total,2)}} @lang('main.devise')</strong></td>

                    <td><a href="#" onclick="editPercu({{$f->id}},{{$f->verse}})" class="@if ($f->total>$f->verse) text-danger @else text-success @endif"><span class="glyphicon glyphicon-edit"></span> <strong>{{number_format($f->verse,2)}} @lang('main.devise')</strong></a></td>
                    <td>{{(new DateTime($f->date_vente))->format('d-m-Y')}}</td>
                    <td>
                        @if ($f->total>$f->verse)
                            <div class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Non payé </div>
                        @else <div class="btn btn-xs btn-success"> <span class="glyphicon glyphicon-ok-circle"></span> Payé </div>
                        @endif
                    </td>
                    <td><a href="{{route('show_facture',$f->id)}}" class="text-warning"> <span class="glyphicon glyphicon-list-alt"></span> Facture</a></td>
                    <td><a href="{{route('delete_ventes',$f->id)}}" class="text-warning"> <span class="glyphicon glyphicon-list-alt"></span> supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

<h4 class="alert alert-info">
    Total: <strong>{{number_format($total,2)}} @lang('main.devise')</strong><br><br>
    @lang('main.m_verse'): <strong>{{number_format($verse ,2)}} @lang('main.devise')</strong>
</h4>

    </div>


    <div class="modal fade" id="mod_edit_verse">
        <div class="modal-dialog" >
            <div class="modal-content" >
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title"> @lang('main.mod') @lang('main.m_verse') </h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" role="form" id="form_edit_verse" method="get" action="{{route('change_facture_state')}}">
                        <fieldset>
                            <input type="hidden" name="id" id="edit-id-fact" required>
                            <h3>@lang('main.m_verse') : <span id="s_percu"></span> @lang('main.devise')</h3>

                            <div class="form-group">
                                <label> @lang('main.montant')</label>
                                <input type="number" name="montant"  class="form-control"    id="required" autofocus>
                            </div>

                            <div class="form-group">
                                <label >@lang('main.op') : </label>
                                <select name="op"  form="form_edit_verse" class="form-control" >
                                    <option value="0">@lang('main.add')</option>
                                    <option value="1">@lang('main.minus')</option>
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
<script src="{{ asset('js/jquery.min.js') }}"></script>

<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<!-- DataTables JS -->
<script src="{{ asset('js/datatables/datatables.min.js') }}"></script>

<!-- JSZip (pour export Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- pdfMake (pour export PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- Autres scripts personnalisés -->
<script src="{{ URL::asset('js/jquery-ui-1.12.1/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('js/chosen_v1.8.7/chosen.jquery.min.js') }}"></script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
<script src="{{asset('js/Chart.min.js')}}"></script>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/Chart.min.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



@section('scripts')
    <script>
    $(document).ready(function(){
    window.editPercu = function(id, montant) {
    $('#edit-id-fact').val(id);
    $('#s_percu').html(montant);
    $('#mod_edit_verse').modal('show');
    };
    });
      /*  function editPercu(id,montant) {
            $('#edit-id-fact').val(id);
            $('#s_percu').html(montant);
            $('#mod_edit_verse').modal('show');
        }*/
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('ventes')}}"><strong>Ventes</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
