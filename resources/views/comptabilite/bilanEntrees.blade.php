@extends('skeleton')

@section('content')
    <div class="col-md-12">
        @include('perso.functions')
        <link rel="stylesheet" media="print" href="{{ URL::asset('css/print_table.css') }}">
        <link rel="stylesheet" media="print" href="{{ URL::asset('css/facture.css') }}">

        <style media="screen">
            .hidden-row {
                display: none;
            }
        </style>

        <div class="row">
            <div class="col-md-12">
                <a href="#" class="btn btn-warning btn-lg" onclick="window.print()">
                    <span class="glyphicon glyphicon-print"></span> Imprimer
                </a>
            </div>
        </div>

        @if(isset($success))
            <div class="alert alert-success">
                {{ trans('admin.succes') }}
            </div>
        @endif

        <div class="col-md-12">
            <div class="btn-group">
                <a href="{{ url('/comptabilite/bilan/entrees') . '?option=year' }}" class="btn btn-success">Toute l'année</a>
                <a href="{{ url('/comptabilite/bilan/entrees') . '?option=month' }}" class="btn btn-info">Mois</a>
                <a href="{{ url('/comptabilite/bilan/entrees') . '?option=week' }}" class="btn btn-warning">Semaine</a>
                <a href="{{ url('/comptabilite/bilan/entrees') . '?option=today' }}" class="btn btn-primary">Aujourd'hui</a>
                <a href="#date_range_modal" data-toggle="modal" class="btn btn-default">Intervalle</a>
            </div>

            <h4 style="margin-top: 15px;">
                @if(isset($date_debut))
                    Liste des entrées du {{ $date_debut . ' - ' . $date_fin }}
                @else
                    Liste des entrées du {{ $table_titre }}
                @endif
            </h4>

            <table id="table-entrees" class="table table-bordered table-striped table-condensed table-inverse">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom Produit</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Réduction</th>
                    <th>Date</th>
                    <th>Numero facture</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Montant versé</th>
                </tr>
                </thead>
                <tbody>
              {{--  @php
                    $i = 1;
                    $sumPayments = 0;
                    $sumverse = 0;
                @endphp
                @foreach($payements as $p)
                    @php
                        $sumPayments += $p->total;
                        $sumverse +=  $p->facture->verse;
                    // Détermine la classe de la ligne

                    @endphp
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td><strong>{{ $p->produit ? $p->produit->libelle : '' }}</strong></td>
                        <td><strong>{{ $p->quantite }}</strong></td>
                        <td><strong>{{ $p->prix_unitaire }}</strong></td>
                        <td><strong>{{ $p->reduction }}</strong></td>
                        <td>{{ (new DateTime($p->date_vente))->format('d-m-Y') }}</td>
                        <td>{{ $p->facture ? $p->facture->numero : '' }}</td>
                        <td>{{ $p->facture && $p->facture->client ? $p->facture->client->nom : '' }}</td>
                        <td>{{ $p->total }}</td>
                        <td>{{ $p->facture ? $p->facture->verse : '' }}</td>
                    </tr>
                @endforeach--}}
              @php
                  $i = 1;
                  $sumPayments = 0;
                  $sumverse = 0;
              @endphp

              @foreach($payements as $p)
                  @php
                      $total = isset($p->total) ? $p->total : 0;
                      $verse = isset($p->facture->verse) ? $p->facture->verse : 0;
                      $sumPayments += $total;
                      $sumverse += $verse;

                      // Détermine la classe de la cellule versement
                      $verseClass = ($verse < $total) ? '#f8d7da' : '#d4edda';
                  @endphp
                  <tr>
                      <td>{{ $i++ }}</td>
                      <td><strong>{{ isset($p->produit->libelle) ? $p->produit->libelle : '' }}</strong></td>
                      <td><strong>{{ $p->quantite }}</strong></td>
                      <td><strong>{{ number_format($p->prix_unitaire, 2, '.', ' ') }}</strong></td>
                      <td><strong>{{ isset($p->reduction) ? number_format($p->reduction, 2, '.', ' ') : '0.00' }}</strong></td>
                      <td>{{ (new DateTime($p->date_vente))->format('d-m-Y') }}</td>
                      <td>{{ isset($p->facture->numero) ? $p->facture->numero : '' }}</td>
                      <td>{{ isset($p->facture->client->nom) ? $p->facture->client->nom : '' }}</td>
                      <td>{{ number_format($total, 2, '.', ' ') }}</td>
                      <td style="background-color: {{ $verseClass }};">
                      {{ number_format($verse, 2, '.', ' ') }}
                  </tr>
              @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Total</th>
                    <th></th><th></th><th></th><th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ $sumPayments }}</th>
                    <th>{{ $sumverse }}</th>
                </tr>
                </tfoot>
            </table>

            <h4 class="alert alert-warning">
                Total : <strong>{{ $total . ' ' . trans('FCFA') }}</strong>
            </h4>

            <h4 style="margin-top: 20px;">
                Entrees speciales : {{$table_titre}}
            </h4>
            <table id="table-entrees-speciales" class="table table-bordered table-striped table-condensed table-inverse">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Numero</th>
                    <th>Caisse</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Telephone</th>
                    <th>Description</th>
                    <th>Solde avant</th>
                    <th>Solde apres</th>
                    <th>Montant</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                @php $j = 1; @endphp
                @foreach($mouvements_entrees_speciales as $m)
                    @php
                        $entree = isset($entrees_speciales[$m->source_id]) ? $entrees_speciales[$m->source_id] : null;
                    @endphp
                    <tr>
                        <td>{{$j++}}</td>
                        <td>{{(new DateTime($m->date_mouvement))->format('d-m-Y H:i')}}</td>
                        <td>{{$entree ? $entree->numero : ''}}</td>
                        <td>{{$m->caisse ? $m->caisse->nom : ''}}</td>
                        <td>{{$entree ? $entree->type : $m->source_type}}</td>
                        <td>{{$entree ? $entree->source_nom : ''}}</td>
                        <td>{{$entree ? $entree->source_telephone : ''}}</td>
                        <td>{{$m->description}}</td>
                        <td>{{number_format($m->solde_avant, 2, '.', ' ')}}</td>
                        <td>{{number_format($m->solde_apres, 2, '.', ' ')}}</td>
                        <td>{{number_format($m->montant, 2, '.', ' ')}}</td>
                        <td>
                            @if($entree)
                                <a href="{{route('entrees_speciales_show', $entree->id)}}" class="btn btn-xs btn-primary">Voir</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Total</th>
                    <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    <th>{{number_format($total_entrees_speciales, 2, '.', ' ')}}</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

            <h4 class="alert alert-info">
                Total global entrees : <strong>{{number_format($sumverse + $total_entrees_speciales, 2, '.', ' ')}} FCFA</strong>
            </h4>
        </div>
    </div>

    <div id="date_range_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content" style="width: 300px; box-shadow: none; background-color: unset;">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">@lang('admin.intervalle')
                            <a href="#" class="pull-right" data-dismiss="modal">X</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="{{ url('/comptabilite/bilan/entrees') }}" accept-charset="UTF-8" role="form">
                            @csrf
                            <input type="hidden" name="date_range" value="1">
                            <fieldset>
                                <div class="form-group">
                                    <label for="date_debut">{{ trans('admin.date_debut') }} :</label>
                                    <input type="date" id="date_debut" name="date_debut" class="form-control datepicker" placeholder="yyyy/mm/dd" value="{{ old('date_debut') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="date_fin">{{ trans('admin.date_fin') }} :</label>
                                    <input type="date" id="date_fin" name="date_fin" class="form-control datepicker" placeholder="yyyy/mm/dd" value="{{ old('date_fin') }}" required>
                                </div>
                                <input type="submit" class="btn btn-primary pull-right" value="{{ trans('admin.confirmer') }}">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $headerTrim = view('perso.header-trim')->render();
    @endphp
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
    <script>
        $(document).ready(function () {
            const exportTitle = "{{ trans('admin.bilan_entrees') }} - {{ isset($table_titre) ? addslashes($table_titre) : '' }}";
            const headerTrim = `{!! addslashes(view('perso.header-trim')->render()) !!}`;

            $('#table-entrees').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        titleAttr: 'Exporter en Excel',
                        title: exportTitle,
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        titleAttr: 'Exporter en PDF',
                        title: exportTitle,
                        footer: true,
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Imprimer',
                        titleAttr: 'Imprimer',
                        title: exportTitle,
                        orientation: 'landscape',
                        pageSize: 'A4',
                        customize: function (win) {
                            win.document.body.innerHTML =
                                '<style>@media print {@page { size: A4 landscape; }}</style>' +
                                headerTrim +
                                win.document.body.innerHTML;
                        },
                        footer: true,
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            });
            $('#table-entrees-speciales').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i> Excel', title: exportTitle },
                    { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf-o"></i> PDF', title: exportTitle, footer: true },
                    { extend: 'print', text: '<i class="fa fa-print"></i> Imprimer', title: exportTitle, footer: true }
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
