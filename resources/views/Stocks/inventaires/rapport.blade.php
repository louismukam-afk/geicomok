@extends('skeleton')
@section('styles')
    <style>
        .inventaire-print-title {
            display: none;
        }

        .rapport-inventaire .panel-heading {
            font-size: 14px;
        }

        .rapport-inventaire table {
            table-layout: fixed;
            width: 100%;
        }

        .rapport-inventaire th,
        .rapport-inventaire td {
            vertical-align: middle !important;
        }

        .rapport-inventaire .col-categorie {
            width: 18%;
        }

        .rapport-inventaire .col-produit {
            width: 24%;
        }

        .rapport-inventaire .col-number {
            width: 9%;
        }

        .rapport-inventaire .col-money {
            width: 11%;
        }

        .rapport-inventaire.total-panel {
            border: 2px solid #777;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            html,
            body {
                width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                font-size: 10px;
                line-height: 1.25;
            }

            .nav-side-menu,
            .upper-band,
            .band-gray,
            .body-head,
            .back-button,
            .loading_icon,
            .loading-back,
            .no-print {
                display: none !important;
            }

            .page,
            .big-container,
            .container-fluid,
            .col-md-12 {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                float: none !important;
            }

            .inventaire-print-title {
                display: block !important;
                text-align: center;
                margin: 0 0 8px 0;
                padding-bottom: 6px;
                border-bottom: 1px solid #333;
            }

            .inventaire-print-title h3 {
                margin: 0;
                font-size: 17px;
                font-weight: bold;
            }

            .inventaire-print-title p {
                margin: 3px 0 0;
                font-size: 11px;
            }

            .screen-title {
                display: none !important;
            }

            .rapport-inventaire.panel {
                border: 0 !important;
                box-shadow: none !important;
                margin-bottom: 8px !important;
                page-break-inside: auto;
            }

            .rapport-inventaire .panel-heading {
                border: 1px solid #555 !important;
                background: #eee !important;
                color: #000 !important;
                padding: 4px 6px !important;
                font-size: 11px;
                font-weight: bold;
                page-break-after: avoid;
            }

            .rapport-inventaire table {
                table-layout: fixed;
                width: 100% !important;
                margin-bottom: 0 !important;
                page-break-inside: auto;
                border-collapse: collapse !important;
            }

            .rapport-inventaire thead {
                display: table-header-group;
            }

            .rapport-inventaire tfoot {
                display: table-footer-group;
            }

            .rapport-inventaire tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .rapport-inventaire th,
            .rapport-inventaire td {
                border: 1px solid #666 !important;
                padding: 3px 4px !important;
                font-size: 9px;
                color: #000 !important;
                overflow-wrap: break-word;
                word-wrap: break-word;
            }

            .rapport-inventaire th {
                background: #f0f0f0 !important;
                font-weight: bold;
            }

            .rapport-inventaire .label {
                border: 1px solid #000;
                color: #000 !important;
                background: #fff !important;
                font-size: 9px;
                padding: 1px 3px;
            }

            .rapport-inventaire.total-panel {
                border: 1px solid #333 !important;
                margin-top: 8px !important;
                page-break-inside: avoid;
            }

            .rapport-inventaire.total-panel .panel-body {
                padding: 6px !important;
            }

            .rapport-inventaire.total-panel h4 {
                font-size: 13px;
                margin: 0;
            }
        }
    </style>
@endsection
@section('content')
    <div class="inventaire-print-title">
        <h3>Rapport d'inventaire</h3>
        <p>Periode du {{(new DateTime($date_debut))->format('d-m-Y')}} au {{(new DateTime($date_fin))->format('d-m-Y')}}</p>
    </div>

    <div class="row screen-title">
        <div class="col-md-12">
            <h3>Rapport d'inventaire</h3>
        </div>
    </div>

    <form action="{{route('inventaires_rapport')}}" method="get" class="panel panel-default no-print">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Date de debut</label>
                    <input type="date" name="date_debut" value="{{$date_debut}}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Date de fin</label>
                    <input type="date" name="date_fin" value="{{$date_fin}}" class="form-control">
                </div>
                <div class="col-md-4" style="padding-top: 25px;">
                    <button class="btn btn-primary" type="submit">Afficher</button>
                    <button class="btn btn-default" type="button" onclick="window.print()">Imprimer</button>
                </div>
            </div>
        </div>
    </form>

    <?php
        $totalPerte = 0;
        $totalSurplus = 0;
    ?>
    @foreach($inventaires as $inventaire)
        <div class="panel panel-default rapport-inventaire">
            <div class="panel-heading">
                <strong>Inventaire #{{$inventaire->id}}</strong>
                du {{(new DateTime($inventaire->date_debut))->format('d-m-Y')}}
                au {{(new DateTime($inventaire->date_fin))->format('d-m-Y')}}
                @if($inventaire->statut == \GEICOM\Inventaire::STATUT_CONSOLIDE)
                    <span class="label label-success">Consolide</span>
                @else
                    <span class="label label-warning">Brouillon</span>
                @endif
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th class="col-categorie">Categorie</th>
                    <th class="col-produit">Produit</th>
                    <th class="text-right col-number">Theorique</th>
                    <th class="text-right col-number">Reel</th>
                    <th class="text-right col-number">Ecart</th>
                    <th class="text-right col-money">Prix achat</th>
                    <th class="text-right col-money">Valeur ecart</th>
                </tr>
                </thead>
                <tbody>
                @foreach($inventaire->lignes as $ligne)
                    <?php
                        $valeur = $ligne->quantite_reelle === null ? 0 : $ligne->valeur_ecart;
                        if ($valeur < 0) { $totalPerte += abs($valeur); }
                        if ($valeur > 0) { $totalSurplus += $valeur; }
                    ?>
                    <tr>
                        <td>{{($ligne->categorie) ? $ligne->categorie->libelle : 'Sans categorie'}}</td>
                        <td>{{$ligne->produit->libelle}}</td>
                        <td class="text-right">{{number_format($ligne->quantite_theorique, 2, ',', ' ')}}</td>
                        <td class="text-right">{{number_format($ligne->quantite_reelle, 2, ',', ' ')}}</td>
                        <td class="text-right @if($ligne->ecart < 0) text-danger @elseif($ligne->ecart > 0) text-success @endif">{{number_format($ligne->ecart, 2, ',', ' ')}}</td>
                        <td class="text-right">{{number_format($ligne->prix_achat, 0, ',', ' ')}}</td>
                        <td class="text-right @if($valeur < 0) text-danger @elseif($valeur > 0) text-success @endif">{{number_format($valeur, 0, ',', ' ')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="panel panel-default rapport-inventaire total-panel">
        <div class="panel-body text-right">
            <h4>
                Perte: <span class="text-danger">{{number_format($totalPerte, 0, ',', ' ')}}</span>
                &nbsp;&nbsp;
                Surplus: <span class="text-success">{{number_format($totalSurplus, 0, ',', ' ')}}</span>
                &nbsp;&nbsp;
                Solde: <strong>{{number_format($totalSurplus - $totalPerte, 0, ',', ' ')}}</strong>
            </h4>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li><a href="{{route('inventaires')}}"><strong>Inventaires</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
