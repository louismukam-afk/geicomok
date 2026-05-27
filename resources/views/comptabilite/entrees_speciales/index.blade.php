@extends('skeleton')
@section('content')
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">Operation enregistree.</div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Nouvelle entree speciale</strong></div>
            <div class="panel-body">
                <form method="post" action="{{route('entrees_speciales_store')}}">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-3">
                            <label>Caisse centrale</label>
                            <select name="id_caisse" class="form-control" required>
                                @foreach($caisses as $caisse)
                                    <option value="{{$caisse->id}}">{{$caisse->nom}} - solde {{number_format($caisse->solde(), 2)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Type apport</label>
                            <select name="type" id="type_apport" class="form-control" onchange="togglePretFields()" required>
                                @foreach($types as $value=>$label)
                                    <option value="{{$value}}">{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Montant</label>
                            <input type="number" step="0.01" min="1" name="montant" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Date apport</label>
                            <input type="date" name="date_apport" class="form-control" value="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Source / auteur</label>
                            <input type="text" name="source_nom" class="form-control" placeholder="Banque, PDG, donateur...">
                        </div>
                        <div class="col-md-3 pret-fields" style="margin-top: 10px;">
                            <label>Telephone source pret</label>
                            <input type="text" name="source_telephone" class="form-control" placeholder="Numero de telephone">
                        </div>
                    </div>

                    <div class="row pret-fields" style="margin-top: 10px;">
                        <div class="col-md-3">
                            <label>Debut remboursement</label>
                            <input type="date" name="date_debut_remboursement" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Fin remboursement</label>
                            <input type="date" name="date_fin_remboursement" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Nombre echeances</label>
                            <input type="number" min="1" name="nombre_echeances" id="nombre_echeances" class="form-control" onchange="buildEcheances()" onkeyup="buildEcheances()">
                        </div>
                    </div>

                    <div class="pret-fields" style="margin-top: 10px;">
                        <label>Echeancier de remboursement</label>
                        <table class="table table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date echeance</th>
                                <th>Montant a verser</th>
                            </tr>
                            </thead>
                            <tbody id="echeances_body"></tbody>
                        </table>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label>Observations</label>
                        <textarea name="observations" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Enregistrer l entree</button>
                    <a href="{{route('entrees_speciales_rappels')}}" class="btn btn-warning">Rappels echeances</a>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>Numero</th>
                <th>Date</th>
                <th>Type</th>
                <th>Caisse</th>
                <th>Source</th>
                <th>Telephone</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Rembourse</th>
                <th class="text-right">Reste</th>
                <th>Statut</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($entrees as $entree)
                <tr>
                    <td>{{$entree->numero}}</td>
                    <td>{{(new DateTime($entree->date_apport))->format('d-m-Y')}}</td>
                    <td>{{isset($types[$entree->type]) ? $types[$entree->type] : $entree->type}}</td>
                    <td>{{$entree->caisse ? $entree->caisse->nom : ''}}</td>
                    <td>{{$entree->source_nom}}</td>
                    <td>{{$entree->source_telephone}}</td>
                    <td class="text-right">{{number_format($entree->montant, 2)}}</td>
                    <td class="text-right">{{number_format($entree->montantRembourse(), 2)}}</td>
                    <td class="text-right">{{number_format($entree->resteARembourser(), 2)}}</td>
                    <td>{{$entree->statut}}</td>
                    <td><a href="{{route('entrees_speciales_show', $entree->id)}}" class="btn btn-xs btn-primary">Ouvrir</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">{{$entrees->render()}}</div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePretFields() {
            if ($('#type_apport').val() === '{{\GEICOM\EntreeSpeciale::TYPE_PRET}}') {
                $('.pret-fields').show();
                buildEcheances();
            } else {
                $('.pret-fields').hide();
                $('#echeances_body').empty();
            }
        }
        function buildEcheances() {
            var count = parseInt($('#nombre_echeances').val()) || 0;
            var body = $('#echeances_body');
            var existingDates = [];
            var existingMontants = [];

            body.find('input[name="echeance_dates[]"]').each(function () {
                existingDates.push($(this).val());
            });
            body.find('input[name="echeance_montants[]"]').each(function () {
                existingMontants.push($(this).val());
            });

            body.empty();
            for (var i = 0; i < count; i++) {
                body.append(
                    '<tr>' +
                    '<td>'+(i + 1)+'</td>' +
                    '<td><input type="date" name="echeance_dates[]" class="form-control" value="'+(existingDates[i] || '')+'" required></td>' +
                    '<td><input type="number" step="0.01" min="1" name="echeance_montants[]" class="form-control" value="'+(existingMontants[i] || '')+'" required></td>' +
                    '</tr>'
                );
            }
        }
        togglePretFields();
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('comptabilite')}}"><strong>Comptabilite</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
