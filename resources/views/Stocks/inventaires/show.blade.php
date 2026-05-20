@extends('skeleton')
@section('content')
    <?php
        $totalPerte = 0;
        $totalSurplus = 0;
    ?>
    <div class="row">
        <div class="col-md-8">
            <h3>Inventaire #{{$inventaire->id}}</h3>
            <p>
                Periode: <strong>{{(new DateTime($inventaire->date_debut))->format('d-m-Y')}}</strong>
                au <strong>{{(new DateTime($inventaire->date_fin))->format('d-m-Y')}}</strong>
                &nbsp; Statut:
                @if($inventaire->statut == \GEICOM\Inventaire::STATUT_CONSOLIDE)
                    <span class="label label-success">Consolide</span>
                @else
                    <span class="label label-warning">Brouillon</span>
                @endif
            </p>
        </div>
        <div class="col-md-4 text-right" style="padding-top: 20px;">
            <a href="{{route('inventaires')}}" class="btn btn-default">Retour</a>
            <a href="{{route('inventaires_rapport', ['date_debut'=>$inventaire->date_debut, 'date_fin'=>$inventaire->date_fin])}}" class="btn btn-info">Rapport</a>
        </div>
    </div>

    <form action="{{route('inventaires_update', $inventaire->id)}}" method="post">
        {{csrf_field()}}
        @if($inventaire->statut != \GEICOM\Inventaire::STATUT_CONSOLIDE)
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Modifier l'inventaire brouillon</strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Date de debut</label>
                            <input type="date" name="date_debut" value="{{$inventaire->date_debut}}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Date de fin</label>
                            <input type="date" name="date_fin" value="{{$inventaire->date_fin}}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Observation</label>
                            <input type="text" name="observations" value="{{$inventaire->observations}}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="date_debut" value="{{$inventaire->date_debut}}">
            <input type="hidden" name="date_fin" value="{{$inventaire->date_fin}}">
            <input type="hidden" name="observations" value="{{$inventaire->observations}}">
        @endif

        @foreach($groupes as $categorieId => $lignes)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>{{($lignes->first()->categorie) ? $lignes->first()->categorie->libelle : 'Sans categorie'}}</strong>
                </div>
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="text-right">Prix achat</th>
                        <th class="text-right">Quantite theorique</th>
                        <th class="text-right">Quantite reelle trouvee</th>
                        <th class="text-right">Ecart</th>
                        <th class="text-right">Valeur ecart</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lignes as $ligne)
                        <?php
                            $ecart = $ligne->quantite_reelle === null ? 0 : $ligne->ecart;
                            $valeur = $ligne->quantite_reelle === null ? 0 : $ligne->valeur_ecart;
                            if ($valeur < 0) { $totalPerte += abs($valeur); }
                            if ($valeur > 0) { $totalSurplus += $valeur; }
                        ?>
                        <tr>
                            <td>{{$ligne->produit->libelle}}</td>
                            <td class="text-right">{{number_format($ligne->prix_achat, 0, ',', ' ')}}</td>
                            <td class="text-right">{{number_format($ligne->quantite_theorique, 2, ',', ' ')}}</td>
                            <td style="width: 180px;">
                                @if($inventaire->statut == \GEICOM\Inventaire::STATUT_CONSOLIDE)
                                    <div class="text-right">{{number_format($ligne->quantite_reelle, 2, ',', ' ')}}</div>
                                @else
                                    <input type="number" step="0.01" min="0" name="quantite_reelle[{{$ligne->id}}]" value="{{$ligne->quantite_reelle}}" class="form-control text-right">
                                @endif
                            </td>
                            <td class="text-right @if($ecart < 0) text-danger @elseif($ecart > 0) text-success @endif">{{number_format($ecart, 2, ',', ' ')}}</td>
                            <td class="text-right @if($valeur < 0) text-danger @elseif($valeur > 0) text-success @endif">{{number_format($valeur, 0, ',', ' ')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        <div class="panel panel-default">
            <div class="panel-body text-right">
                <strong>Perte:</strong> <span class="text-danger">{{number_format($totalPerte, 0, ',', ' ')}}</span>
                &nbsp;&nbsp;
                <strong>Surplus:</strong> <span class="text-success">{{number_format($totalSurplus, 0, ',', ' ')}}</span>
            </div>
        </div>

        @if($inventaire->statut != \GEICOM\Inventaire::STATUT_CONSOLIDE)
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-floppy-disk"></span> Enregistrer les quantites reelles
            </button>
        @endif
    </form>

    @if($inventaire->statut != \GEICOM\Inventaire::STATUT_CONSOLIDE)
        <form action="{{route('inventaires_consolider', $inventaire->id)}}" method="post" style="display: inline-block;margin-top: 10px;">
            {{csrf_field()}}
            <button type="submit" class="btn btn-warning" onclick="return confirm('Consolider cet inventaire et ajuster les stocks ?')">
                <span class="glyphicon glyphicon-ok"></span> Consolider les stocks
            </button>
        </form>
    @endif
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('stocks')}}"><strong>Stocks</strong></a></li>
        <li><a href="{{route('inventaires')}}"><strong>Inventaires</strong></a></li>
        <li class="active"><strong>#{{$inventaire->id}}</strong></li>
    </ol>
@endsection
