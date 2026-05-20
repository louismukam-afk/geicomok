@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

        <div class="panel panel-default no-print">
            <div class="panel-heading"><strong>Recherche</strong></div>
            <div class="panel-body">
                <form action="{{route('historique_stocks_general')}}" method="get" class="row">
                    <div class="col-md-4">
                        <label>Produit</label>
                        <select name="produit" class="form-control">
                            <option value="0">Tous les produits</option>
                            @foreach($produits as $produit)
                                <option value="{{$produit->id}}" @if($produit_id == $produit->id) selected @endif>
                                    {{$produit->libelle}} @if($produit->reference) ({{$produit->reference}}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Date de debut</label>
                        <input type="date" name="date_debut" value="{{$date_debut}}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Date de fin</label>
                        <input type="date" name="date_fin" value="{{$date_fin}}" class="form-control">
                    </div>
                    <div class="col-md-2" style="padding-top: 25px;">
                        <button class="btn btn-primary" type="submit">Afficher</button>
                        <button class="btn btn-warning" type="button" onclick="window.print()">Imprimer</button>
                    </div>
                </form>
            </div>
        </div>

        <h3 class="text-center">
            {{$title}}<br>
            <small>Du {{(new DateTime($date_debut))->format('d-m-Y')}} au {{(new DateTime($date_fin))->format('d-m-Y')}}</small>
        </h3>

        <table class="table table-bordered table-condensed table-striped">
            <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Produit</th>
                <th>Categorie</th>
                <th>Details</th>
                <th class="text-right">Mouvement</th>
                <th class="text-right">Stock avant</th>
                <th class="text-right">Stock restant</th>
            </tr>
            </thead>
            <tbody>
            @forelse($usages as $usage)
                <?php $produit=$usage->produit_info; ?>
                <tr @if($usage->is_inventaire) class="warning" @endif>
                    <td>{{(new DateTime($usage->date_utilisation))->format('d-m-Y')}}</td>
                    <td>{{(new DateTime($usage->date_utilisation))->format('H:i')}}</td>
                    <td>
                        @if($produit)
                            <strong>{{$produit->libelle}}</strong>
                            @if($produit->reference)<br><small>{{$produit->reference}}</small>@endif
                        @else
                            Produit #{{$usage->id_produit}}
                        @endif
                    </td>
                    <td>{{($produit && $produit->categorie) ? $produit->categorie->libelle : 'Sans categorie'}}</td>
                    <td>
                        @if($usage->is_inventaire)
                            <span class="label label-warning">Consolidation inventaire</span><br>
                        @endif
                        {{$usage->details}}
                        @if($usage->stock_ecart)
                            <br><small class="text-warning">Stock enregistre: {{number_format($usage->stock_enregistre, 0, ',', ' ')}}</small>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($usage->sens == 'sortie')
                            <strong class="text-danger">-{{$usage->quantite}}</strong>
                        @else
                            <strong class="text-success">+{{$usage->quantite}}</strong>
                        @endif
                    </td>
                    <td class="text-right">{{number_format($usage->stock_avant, 0, ',', ' ')}}</td>
                    <td class="text-right">{{number_format($usage->stock_restant, 0, ',', ' ')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Aucun mouvement trouve pour cette periode.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="text-center no-print">{{$usages->render()}}</div>
    </div>
@endsection

@section('styles')
    <style>
        @media print {
            @page { size: A4 landscape; margin: 8mm; }
            .nav-side-menu, .upper-band, .band-gray, .body-head, .back-button, .no-print {
                display: none !important;
            }
            .page, .big-container, .container-fluid, .col-md-12 {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
                float: none !important;
            }
            table { width: 100% !important; border-collapse: collapse !important; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
            th, td {
                border: 1px solid #555 !important;
                padding: 3px 4px !important;
                font-size: 9px;
                color: #000 !important;
            }
            th { background: #eee !important; }
            .label {
                border: 1px solid #000;
                color: #000 !important;
                background: #fff !important;
            }
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
