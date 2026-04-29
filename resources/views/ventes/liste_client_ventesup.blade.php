@extends('skeleton')

@section('content')
    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />

    <div class="col-md-12">
        <a href="#" onclick="window.print()" class="btn btn-warning">
            <span class="glyphicon glyphicon-print"></span> Imprimer
        </a>

        @if(isset($client))
            <h3>Client: <strong>{{ $client->nom }}</strong></h3>
        @endif

        <br>
        <h3>Récapitulatif des ventes: Du {{ (new DateTime($dd))->format('d/m/Y') }} au {{ (new DateTime($df))->format('d/m/Y') }}</h3>

        {{-- Tableau des factures --}}
        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <tr>
                <th>#</th>
                <th class="numero">Numéro</th>
                <th>Client</th>
                <th>Utilisateur</th>
                <th>Montant</th>
                <th>@lang('main.m_verse')</th>
                <th>Date</th>
                <th class="etat">Etat</th>
                <th>Facture</th>
            </tr>
            </thead>
            <tbody>
            @php $i = 1; $total = 0; $verse = 0; @endphp
            @foreach($factures as $f)
                @php
                    $total += $f->total;
                    $verse += $f->verse;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td class="facture">
                        <a href="{{ route('details_ventes', $f->id) }}" class="facture">
                            <strong class="@if($f->paye == 0) text-danger @endif">
                                <span class="glyphicon glyphicon-arrow-up"></span> N° {{ $f->numero }}
                            </strong>
                        </a>
                    </td>
                    <td>{{ isset($f->client->nom) ? $f->client->nom : '' }}</td>
                    <td>{{ isset($f->user->name) ? $f->user->name : '' }}</td>
                    <td><strong>{{ number_format($f->total, 2) }} @lang('main.devise')</strong></td>
                    <td>
                        <a href="#" onclick="editPercu({{ $f->id }}, {{ $f->verse }})"
                           class="@if ($f->total > $f->verse) text-danger @else text-success @endif">
                            <span class="glyphicon glyphicon-edit"></span>
                            <strong>{{ number_format($f->verse, 2) }} @lang('main.devise')</strong>
                        </a>
                    </td>
                    <td>{{ (new DateTime($f->date_vente))->format('d-m-Y') }}</td>
                    <td>
                        @if ($f->total > $f->verse)
                            <div class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span> Non payé</div>
                        @else
                            <div class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok-circle"></span> Payé</div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('show_facture', $f->id) }}" class="text-warning">
                            <span class="glyphicon glyphicon-list-alt"></span> Facture
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h4 class="alert alert-info">
            Total: <strong>{{ number_format($total, 2) }} @lang('main.devise')</strong><br><br>
            @lang('main.m_verse'): <strong>{{ number_format($verse, 2) }} @lang('main.devise')</strong>
        </h4>

        <div class="container">
            <h3 class="mb-4 text-primary">Ventes par produit avec bénéfices (groupé par utilisateur et client)</h3>

            @php $beneficeGlobal = 0; @endphp

            @forelse($ventesParUtilisateur as $userId => $userData)
                <h4 class="mt-4 text-primary">Utilisateur : {{ $userData['user_nom'] }}</h4>

                @php
                    // Trier les clients par total de vente décroissant
                    uasort($userData['clients'], function($a, $b) {
                        $totalA = 0;
                        foreach ($a['produits'] as $p) {
                            $totalA += $p['total_vente'];
                        }
                        $totalB = 0;
                        foreach ($b['produits'] as $p) {
                            $totalB += $p['total_vente'];
                        }
                        if ($totalA == $totalB) return 0;
                        return ($totalA < $totalB) ? 1 : -1;
                    });
                @endphp

                @foreach($userData['clients'] as $clientId => $clientData)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Client : {{ $clientData['client_nom'] }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité Totale</th>
                                    <th>Total Achat</th>
                                    <th>Total Vente</th>
                                    <th>Bénéfice</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $beneficeClient = 0;
                                    $totalAchatClient = 0;
                                    $totalVenteClient = 0;
                                @endphp
                                @foreach($clientData['produits'] as $produit)
                                    <tr>
                                        <td>{{ $produit['produit'] }}</td>
                                        <td>{{ $produit['quantite_totale'] }}</td>
                                        <td>{{ number_format($produit['total_achat'], 0, ',', ' ') }} FCFA</td>
                                        <td>{{ number_format($produit['total_vente'], 0, ',', ' ') }} FCFA</td>
                                        <td class="{{ $produit['total_benefice'] >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ number_format($produit['total_benefice'], 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                    @php
                                        $beneficeClient += $produit['total_benefice'];
                                        $totalAchatClient += $produit['total_achat'];
                                        $totalVenteClient += $produit['total_vente'];
                                    @endphp
                                @endforeach
                                <tr class="table-secondary">
                                    <td class="text-end fw-bold">Total client</td>
                                    <td></td>
                                    <td>{{ number_format($totalAchatClient, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($totalVenteClient, 0, ',', ' ') }} FCFA</td>
                                    <td class="fw-bold">{{ number_format($beneficeClient, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                @php $beneficeGlobal += $beneficeClient; @endphp
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @empty
                <p class="text-center text-muted">Aucune vente trouvée pour cette période.</p>
            @endforelse

            <div class="alert alert-success mt-4">
                <h4 class="mb-0">Bénéfice Global : {{ number_format($beneficeGlobal, 0, ',', ' ') }} FCFA</h4>
            </div>
        </div>
    </div>

    {{-- Modal pour modifier versement --}}
    <div class="modal fade" id="mod_edit_verse">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">@lang('main.mod') @lang('main.m_verse')</h4>
                </div>
                <div class="modal-body">
                    <form accept-charset="UTF-8" role="form" id="form_edit_verse" method="get" action="{{ route('change_facture_state') }}">
                        <fieldset>
                            <input type="hidden" name="id" id="edit-id-fact" required>
                            <h3>@lang('main.m_verse') : <span id="s_percu"></span> @lang('main.devise')</h3>
                            <div class="form-group">
                                <label>@lang('main.montant')</label>
                                <input type="number" name="montant" class="form-control" id="required" autofocus>
                            </div>
                            <div class="form-group">
                                <label>@lang('main.op') : </label>
                                <select name="op" form="form_edit_verse" class="form-control">
                                    <option value="0">@lang('main.add')</option>
                                    <option value="1">@lang('main.minus')</option>
                                </select>
                            </div>
                            <input class="btn btn-success" type="submit" value="confirmer">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function editPercu(id, montant) {
            $('#edit-id-fact').val(id);
            $('#s_percu').html(montant);
            $('#mod_edit_verse').modal('show');
        }
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{ route('home') }}"><strong>Accueil</strong></a></li>
        <li><a href="{{ route('ventes') }}"><strong>Ventes</strong></a></li>
        <li class="active"><strong>{{ $title }}</strong></li>
    </ol>
@endsection
