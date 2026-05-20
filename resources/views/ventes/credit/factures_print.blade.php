@extends('skeleton')
@section('content')
    <link href="{{ URL::asset('css/facture.css') }}" rel="stylesheet" />
    <style>
        .credit-print-actions {
            margin: 10px 0 15px;
        }
        .credit-print-header {
            border-bottom: 2px solid #333;
            margin-bottom: 18px;
            padding-bottom: 10px;
        }
        .credit-facture {
            border: 1px solid #ddd;
            margin-bottom: 18px;
            padding: 12px;
            page-break-inside: avoid;
        }
        .credit-facture h4 {
            margin-top: 0;
        }
        .credit-summary {
            margin-top: 12px;
            width: 100%;
        }
        .credit-summary td {
            padding: 4px 6px;
        }
        @media print {
            .credit-print-actions,
            .main-sidebar,
            .navbar,
            .breadcrumb,
            .btn,
            footer {
                display: none !important;
            }
            .content-wrapper,
            .right-side,
            .main-footer {
                margin-left: 0 !important;
            }
            .credit-facture {
                border: 1px solid #999;
                page-break-inside: avoid;
            }
            body {
                background: #fff !important;
            }
        }
    </style>

    <div class="col-md-12">
        <div class="credit-print-actions">
            <a href="{{route('bons_credit_show', $bon->id)}}" class="btn btn-default">Retour</a>
            <a href="#" onclick="window.print()" class="btn btn-warning">
                <span class="glyphicon glyphicon-print"></span> Imprimer
            </a>
        </div>

        <div class="credit-print-header">
            <h3 style="margin-top: 0;text-align: center;">FACTURES LIEES AU BON DE CREDIT</h3>
            <div class="row">
                <div class="col-md-6">
                    <strong>Boutique:</strong> {{$boutique ? $boutique->nom : ''}}<br>
                    <strong>Bon:</strong> {{$bon->numero}}<br>
                    <strong>Client:</strong> {{$bon->client ? $bon->client->nom : ''}}<br>
                    <strong>Periode de prise:</strong> {{(new DateTime($bon->date_debut))->format('d-m-Y')}} au {{(new DateTime($bon->date_fin))->format('d-m-Y')}}
                </div>
                <div class="col-md-6 text-right">
                    <strong>Montant credit:</strong> {{number_format($bon->montant_credit, 2)}} @lang('main.devise')<br>
                    <strong>Consomme:</strong> {{number_format($bon->montantConsomme(), 2)}} @lang('main.devise')<br>
                    <strong>Disponible:</strong> {{number_format($bon->soldeDisponible(), 2)}} @lang('main.devise')<br>
                    <strong>Reste a rembourser:</strong> {{number_format($bon->resteARembourser(), 2)}} @lang('main.devise')
                </div>
            </div>
        </div>

        @if($bon->factures->count() == 0)
            <div class="alert alert-info">Aucune facture liee a ce bon de credit.</div>
        @endif

        <?php $totalGeneral=0; ?>
        @foreach($bon->factures as $facture)
            <?php $totalGeneral += $facture->total; $sousTotal=0; ?>
            <div class="credit-facture">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Facture N° {{$facture->numero}}</h4>
                        <strong>Date:</strong> {{(new DateTime($facture->date_vente))->format('d-m-Y')}}<br>
                        <strong>Vendeur:</strong> {{$facture->vendeur ? $facture->vendeur->name : ''}}
                    </div>
                    <div class="col-md-6 text-right">
                        <strong>Client:</strong> {{$facture->client ? $facture->client->nom : ''}}<br>
                        <strong>Mode:</strong> Vente a credit<br>
                        <strong>Bon:</strong> {{$bon->numero}}
                    </div>
                </div>

                <table class="table table-bordered table-condensed" style="margin-top: 12px;">
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="text-right">Prix</th>
                        <th class="text-right">Quantite</th>
                        <th class="text-right">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($facture->ventes as $vente)
                        <?php $sousTotal += $vente->total; ?>
                        <tr>
                            <td>{{$vente->produit ? $vente->produit->libelle : ''}}</td>
                            <td class="text-right">{{number_format($vente->prix_unitaire - $vente->reduction, 2)}} @lang('main.devise')</td>
                            <td class="text-right">{{$vente->quantite}}</td>
                            <td class="text-right">{{number_format($vente->total, 2)}} @lang('main.devise')</td>
                        </tr>
                    @endforeach
                    @if($facture->tva > 0)
                        <tr>
                            <td colspan="3"><strong>TVA {{$facture->tva}}%</strong></td>
                            <td class="text-right"><strong>{{number_format($sousTotal * $facture->tva / 100, 2)}} @lang('main.devise')</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3"><strong>Total facture</strong></td>
                        <td class="text-right"><strong>{{number_format($facture->total, 2)}} @lang('main.devise')</strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endforeach

        <table class="credit-summary">
            <tr>
                <td class="text-right"><h4>Total general des factures: <strong>{{number_format($totalGeneral, 2)}} @lang('main.devise')</strong></h4></td>
            </tr>
        </table>
    </div>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('ventes')}}"><strong>Ventes</strong></a></li>
        <li><a href="{{route('bons_credit')}}"><strong>Bons de credit</strong></a></li>
        <li><a href="{{route('bons_credit_show', $bon->id)}}"><strong>{{$bon->numero}}</strong></a></li>
        <li class="active"><strong>Impression factures</strong></li>
    </ol>
@endsection
