@extends('skeleton')
@section('content')
    @include('perso.functions')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">
    <link rel="stylesheet" media="print" href="{{ URL::asset('css/facture.css') }}">

    <style media="screen">
        .hidden-row {
            display: none;
        }
    </style>
    <div class="col-md-12">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

            @endif



            <?php $i=1; $sumDepenses = 0; $sumSalaire = 0?>

            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="btn-group" >
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=year'}}" class="btn btn-success"> Toute l'année</a>
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=month'}}" class="btn btn-info"> Mois</a>
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=week'}}" class="btn btn-warning"> Semaine</a>
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=today'}}" class="btn btn-primary"> Aujourd'hui</a>

                    </div>
                    <h4 style="margin-top: 15px;">
                        @if(isset($date_debut))
                            <strong>{{trans('Achat').' : '}}</strong>{{$date_debut.' - '.$date_fin}}
                        @else
                            <strong>{{trans('Achat').' : ' }}</strong> {{$table_titre}}
                        @endif

                    </h4>

                    <table id="table-salaire" class="table table-bordered table-striped table-condensed table-inverse" >
                        <thead>
                        <th>#</th>
                        <th>Nom Produit</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Date</th>
                        <th>numero Livraison</th>
                        <th>Fournisseur</th>
                        <th>Montant</th>

                        </thead>
                        <tbody>
                        <?php $i=1; ?>
                        @foreach($payements as $p)
                            <?php $sumSalaire += $p->total?>


                            <tr>
                                <td>{{ $i++ }}</td>
                                <td><strong>{{ $p->produit ? $p->produit->libelle : '' }}</strong></td>
                                <td><strong>{{ $p->quantite }}</strong></td>
                                <td><strong>{{ $p->prix }}</strong></td>
                                <td>{{ (new DateTime($p->date_achat))->format('d-m-Y') }}</td>
                                <td>{{ $p->livraison ? $p->livraison->numero : '' }}</td>
                                <td>{{ $p->livraison && $p->livraison->fournisseur ? $p->livraison->fournisseur->nom : '' }}</td>
                                <td>{{ $p->total }}</td>

                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                            <th >Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{$sumSalaire}}</th>
                        </tfoot>
                    </table>


                    <h4 class="alert alert-warning">
                        Total : <strong>{{$total.' '.'FCFA'}} </strong>
                    </h4>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">Intervalle</div>

                        </div>
                        <div class="panel-body">
                            <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/comptabilite/bilan/sorties') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="date_range" value="1">

                                <fieldset>

                                    <div class="form-group ">
                                        <label for="date_debut">{{  trans('admin.date_debut')  }} : </label>
                                        <input placeholder="yyyy-mm-dd"  class="form-control datepicker" id="date_debut" name="date_debut" type="text"  value="{{ old('date_debut') }}" required>

                                    </div>
                                    <div class="form-group">
                                        <label for="date_fin">{{  trans('admin.date_fin')  }} : </label>
                                        <input placeholder="yyyy-mm-dd"      class="form-control datepicker" id="date_fin" name="date_fin" type="text"  value="{{ old('date_fin') }}" required >

                                    </div>
                                    <input class="btn  btn-primary pull-right " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    <h4 style="margin-top: 15px;">
                        @if(isset($date_debut))
                            <strong>{{trans('Listes des dépenses').' : '}}</strong>{{$date_debut.' - '.$date_fin}}
                        @else
                            <strong>{{trans('Listes des dépenses').' : ' }}</strong>{{$table_titre}}

                        @endif

                    </h4>
                    <table id="table-decaissement" class="table table-bordered table-striped table-condensed table-inverse" >
                        <thead>
                        <th>#</th>
                        <th>Personnel</th>
                        <th>Ligne Budgetaire</th>
                        <th>Categorie Budget</th>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Montant</th>
                        </thead>
                        <tbody>
                        <?php $i=1; ?>
                        @foreach($decaissements as $p)
                            <?php $sumDepenses += $p->montant?>

                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                    @foreach($personnels as $pers)
                                        @if($pers->id==$p->id_personnel)
                                            {{$pers->nom}}
                                            <?php break; ?>
                                        @endif
                                    @endforeach
                                </td>
                                <td><strong >{{$p->motif}}</strong></td>
                                <td><strong>{{ $p->ligne_budgetaire ? $p->ligne_budgetaire->libelle_ligne : '' }}</strong></td>
                                <td><strong>{{ $p->categorie_budgetaire ? $p->categorie_budgetaire->libelle : '' }}</strong></td>
                                <td>{{(new DateTime($p->date))->format('d-m-Y')}}</td>
                                <td>{{$p->montant}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <th >Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{$sumDepenses}}</th>
                        </tfoot>
                    </table>


                    <h4 class="alert alert-warning">
                        Total : <strong>{{$total_d.' '."FCFA"}} </strong>
                    </h4>


                </div>

            </div>


            <div class="col-md-8">
                <h4 style="margin-top: 15px;">
                    <strong>Remboursements des prets : </strong>{{$table_titre}}
                </h4>
                <table id="table-remboursements" class="table table-bordered table-striped table-condensed table-inverse">
                    <thead>
                    <th>#</th>
                    <th>Date</th>
                    <th>Numero remboursement</th>
                    <th>Entree speciale</th>
                    <th>Caisse</th>
                    <th>Description</th>
                    <th>Solde avant</th>
                    <th>Solde apres</th>
                    <th>Montant</th>
                    <th>Detail</th>
                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach($mouvements_remboursements as $m)
                        @php
                            $remboursement = isset($remboursements_speciaux[$m->source_id]) ? $remboursements_speciaux[$m->source_id] : null;
                            $entree = $remboursement && $remboursement->entreeSpeciale ? $remboursement->entreeSpeciale : null;
                        @endphp
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{(new DateTime($m->date_mouvement))->format('d-m-Y H:i')}}</td>
                            <td>{{$remboursement ? $remboursement->numero : ''}}</td>
                            <td>{{$entree ? $entree->numero : ''}}</td>
                            <td>{{$m->caisse ? $m->caisse->nom : ''}}</td>
                            <td>{{$m->description}}</td>
                            <td>{{number_format($m->solde_avant, 2)}}</td>
                            <td>{{number_format($m->solde_apres, 2)}}</td>
                            <td>{{number_format($m->montant, 2)}}</td>
                            <td>
                                @if($entree)
                                    <a href="{{route('entrees_speciales_show', $entree->id)}}" class="btn btn-xs btn-primary">Voir</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <th>Total</th>
                    <th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    <th>{{number_format($total_remboursements, 2)}}</th>
                    <th></th>
                    </tfoot>
                </table>

                <h4 class="alert alert-warning">
                    Total remboursements : <strong>{{number_format($total_remboursements, 2).' FCFA'}}</strong>
                </h4>
            </div>

            <h3 class="alert alert-info col-md-12">
                {{trans('Dépenses totales').' : '.($total+$total_d+$total_remboursements).' '."FCFA"}}
            </h3>


    </div>


    {{--!-- jQuery (UNE SEULE FOIS) -->--}}
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
   {{-- <script>
        $('#table-salaire').dataTable( {
            dom: 'Bfrtip',
            buttons: [
                //'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Export to Excel',
                    title: '{!! trans('admin.bilan_sorties') !!} - {!! sanitize($table_titre) !!}',

                }
                ,
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    titleAttr: 'PDF',
                    title: '{!! trans('admin.bilan_sorties') !!} - {!! sanitize($table_titre) !!}',
                    footer: true,
                }
                ,
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> @lang("inscription.imprimer")',
                    titleAttr: '@lang("inscription.imprimer")',
                    title: '{!! trans('admin.bilan_sorties') !!} - {!! sanitize($table_titre) !!}',
                    customize: (window, buttonO, dtAPI) => {dataTableButtonFunction(window, buttonO, dtAPI)},
                    footer: true,
                }
            ]
        } );

        $('#table-decaissement').dataTable( {
            dom: 'Bfrtip',
            pageLength:50,
            buttons: [
                //'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Export to Excel',
                    title: '{!! trans('admin.bilan_sorties') !!} - {!! sanitize($table_titre) !!}',

                }
                ,
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    titleAttr: 'PDF',
                    title: '{!! trans('admin.bilan_sorties') !!} - {!! sanitize($table_titre) !!}',
                    footer: true,
                }
                ,
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> @lang("inscription.imprimer")',
                    titleAttr: '@lang("inscription.imprimer")',
                    title: '{!! trans('admin.bilan_sorties') !!} - {!! sanitize($table_titre) !!}',
                    customize: (window, buttonO, dtAPI) => {dataTableButtonFunction(window, buttonO, dtAPI)},
                    footer: true,
                }
            ]
        } );

        var dataTableButtonFunction = function (window, buttonO, dtAPI) {
            window.document.body.innerHTML = '<style> @media print { @page  { size: A4 portrait } } </style>' + '@include('perso.header-trim')' + window.document.body.innerHTML

        }

    </script>--}}
    <script>
        $(document).ready(function () {
            // Table Salaire
            $('#table-salaire').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        titleAttr: 'Export to Excel',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        titleAttr: 'PDF',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> @lang("imprimer")',
                        titleAttr: '@lang("imprimer")',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                        customize: function (win) {
                            win.document.body.innerHTML =
                                '<style> @media print { @page  { size: A4 portrait } } </style>' +
                                `@include('perso.header-trim')` +
                                win.document.body.innerHTML;
                        },
                        footer: true,
                    }
                ]
            });

            // Table Décaissement
            $('#table-decaissement').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        titleAttr: 'Export to Excel',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        titleAttr: 'PDF',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> @lang("imprimer")',
                        titleAttr: '@lang("imprimer")',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                        customize: function (win) {
                            win.document.body.innerHTML =
                                '<style> @media print { @page  { size: A4 portrait } } </style>' +
                                `@include('perso.header-trim')` +
                                win.document.body.innerHTML;
                        },
                        footer: true,
                    }
                ]
            });
            $('#table-remboursements').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        titleAttr: 'Export to Excel',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        titleAttr: 'PDF',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                        footer: true,
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> @lang("imprimer")',
                        titleAttr: '@lang("imprimer")',
                        title: '{{ trans('admin.bilan_sorties') }} - {{ sanitize($table_titre) }}',
                        footer: true,
                    }
                ]
            });
        });
    </script>

@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent; padding: 4px 10px;">
        <li><a href="{{ route('dashboard') }}"><strong>Accueil</strong></a></li>
        <li class="active"><a href="{{ route('comptabilite') }}"><strong>Comptabilité</strong></a></li>
        <li class="active"><strong>{{ $titre }}</strong></li>
    </ol>
@endsection
