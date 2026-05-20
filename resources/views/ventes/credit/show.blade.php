@extends('skeleton')
@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <h3>{{$bon->numero}} - {{$bon->client ? $bon->client->nom : ''}}</h3>
                <p>
                    Periode de prise: <strong>{{(new DateTime($bon->date_debut))->format('d-m-Y')}}</strong>
                    au <strong>{{(new DateTime($bon->date_fin))->format('d-m-Y')}}</strong>
                    | Statut:
                    @if($bon->statut == \GEICOM\BonCredit::STATUT_ACTIF)
                        <span class="label label-success">Actif</span>
                    @elseif($bon->statut == \GEICOM\BonCredit::STATUT_INACTIF)
                        <span class="label label-default">Inactif</span>
                    @else
                        <span class="label label-danger">Cloture</span>
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-right" style="padding-top: 20px;">
                <a href="{{route('bons_credit_factures_print', $bon->id)}}" class="btn btn-warning">
                    <span class="glyphicon glyphicon-print"></span> Imprimer les factures
                </a>
                <a href="{{route('bons_credit')}}" class="btn btn-default">Retour</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"><div class="alert alert-info">Credit: <strong>{{number_format($bon->montant_credit, 0, ',', ' ')}}</strong></div></div>
            <div class="col-md-3"><div class="alert alert-warning">Consomme: <strong>{{number_format($bon->montantConsomme(), 0, ',', ' ')}}</strong></div></div>
            <div class="col-md-3"><div class="alert alert-success">Disponible: <strong>{{number_format($bon->soldeDisponible(), 0, ',', ' ')}}</strong></div></div>
            <div class="col-md-3"><div class="alert alert-danger">Reste a rembourser: <strong>{{number_format($bon->resteARembourser(), 0, ',', ' ')}}</strong></div></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><strong>Modifier le bon de credit</strong></div>
            <div class="panel-body">
                <form action="{{route('bons_credit_update', $bon->id)}}" method="post">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-3">
                            <label>Client</label>
                            <select name="client" class="form-control" required>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}" {{$bon->id_client == $client->id ? 'selected' : ''}}>{{$client->nom}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Montant credit</label>
                            <input type="number" step="0.01" min="{{$bon->montantConsomme()}}" name="montant_credit" value="{{$bon->montant_credit}}" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Date debut</label>
                            <input type="date" name="date_debut" value="{{$bon->date_debut}}" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label>Date fin</label>
                            <input type="date" name="date_fin" value="{{$bon->date_fin}}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Statut</label>
                            <select name="statut" class="form-control" required>
                                @foreach($statuts as $value=>$label)
                                    <option value="{{$value}}" {{$bon->statut == $value ? 'selected' : ''}}>{{$label}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3" style="margin-top: 10px;">
                            <label>Debut remboursement</label>
                            <input type="date" name="date_debut_remboursement" value="{{$bon->date_debut_remboursement}}" class="form-control">
                        </div>
                        <div class="col-md-3" style="margin-top: 10px;">
                            <label>Fin remboursement</label>
                            <input type="date" name="date_fin_remboursement" value="{{$bon->date_fin_remboursement}}" class="form-control">
                        </div>
                        <div class="col-md-4" style="margin-top: 10px;">
                            <label>Observation</label>
                            <input type="text" name="observations" value="{{$bon->observations}}" class="form-control">
                        </div>
                        <div class="col-md-2" style="margin-top: 34px;">
                            <button class="btn btn-primary" type="submit">Modifier</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($bon->statut == \GEICOM\BonCredit::STATUT_ACTIF)
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Sortie de marchandises sur le bon</strong></div>
                <div class="panel-body">
                    <input type="text" id="creditSearch" class="form-control" placeholder="Rechercher un produit" onkeyup="findCreditProduct()">
                    <ul id="creditResults" style="max-height: 220px;overflow-y: auto;margin-top: 8px;"></ul>
                    <form action="{{route('bons_credit_vente', $bon->id)}}" method="post" id="creditSaleForm">
                        {{csrf_field()}}
                        <input type="hidden" id="creditTva" value="{{$tva}}">
                        <table class="table table-condensed table-striped">
                            <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Quantite</th>
                                <th>Reduction</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="creditSaleBody"></tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date</label>
                                <input type="date" name="date" value="{{date('Y-m-d')}}" class="form-control" required>
                            </div>
                            <div class="col-md-9 text-right">
                                <h4>Total: <strong><span id="creditSaleTotal">0</span></strong></h4>
                                <button class="btn btn-success" type="submit">Valider la sortie sur credit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                Ce bon est inactif ou cloture. Les sorties de marchandises sont bloquees tant que le statut n'est pas actif.
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Ajouter une echeance</strong></div>
                    <div class="panel-body">
                        <form action="{{route('bons_credit_echeance', $bon->id)}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label>Date echeance</label>
                                <input type="date" name="date_echeance" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Montant</label>
                                <input type="number" step="0.01" min="1" name="montant" class="form-control" required>
                            </div>
                            <button class="btn btn-primary" type="submit">Ajouter echeance</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Remboursement</strong></div>
                    <div class="panel-body">
                        <form action="{{route('bons_credit_remboursement', $bon->id)}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label>Echeance</label>
                                <select name="id_echeance" class="form-control">
                                    <option value="">Sans echeance precise</option>
                                    @foreach($bon->echeances as $e)
                                        <option value="{{$e->id}}">{{(new DateTime($e->date_echeance))->format('d-m-Y')}} - {{number_format($e->montant - $e->montant_paye, 0, ',', ' ')}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Caisse d'entree</label>
                                <select name="id_caisse" class="form-control" required>
                                    @foreach($caisses_entree as $caisse)
                                        <option value="{{$caisse->id}}">{{$caisse->nom}} ({{number_format($caisse->solde(), 0, ',', ' ')}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date paiement</label>
                                <input type="date" name="date_paiement" value="{{date('Y-m-d')}}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Montant</label>
                                <input type="number" step="0.01" min="1" name="montant" class="form-control" required>
                            </div>
                            <button class="btn btn-success" type="submit">Enregistrer remboursement</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h4>Sorties sur le bon</h4>
        <table class="table table-bordered table-condensed">
            <thead><tr><th>Numero</th><th>Date</th><th class="text-right">Total</th><th>Produits</th><th></th></tr></thead>
            <tbody>
            @foreach($bon->factures as $facture)
                <tr>
                    <td>{{$facture->numero}}</td>
                    <td>{{(new DateTime($facture->date_vente))->format('d-m-Y')}}</td>
                    <td class="text-right">{{number_format($facture->total, 0, ',', ' ')}}</td>
                    <td>
                        @foreach($facture->ventes as $vente)
                            {{$vente->produit->libelle}} x {{$vente->quantite}}<br>
                        @endforeach
                    </td>
                    <td><a href="{{route('show_facture', $facture->id)}}" class="btn btn-xs btn-warning">Facture</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h4>Echeances</h4>
        <table class="table table-bordered table-condensed">
            <thead><tr><th>Date</th><th class="text-right">Montant</th><th class="text-right">Paye</th><th>Statut</th></tr></thead>
            <tbody>
            @foreach($bon->echeances as $echeance)
                <tr>
                    <td>{{(new DateTime($echeance->date_echeance))->format('d-m-Y')}}</td>
                    <td class="text-right">{{number_format($echeance->montant, 0, ',', ' ')}}</td>
                    <td class="text-right">{{number_format($echeance->montant_paye, 0, ',', ' ')}}</td>
                    <td>{{$echeance->statut}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h4>Remboursements</h4>
        <table class="table table-bordered table-condensed">
            <thead><tr><th>Numero</th><th>Date</th><th>Caisse</th><th class="text-right">Montant</th></tr></thead>
            <tbody>
            @foreach($bon->remboursements as $r)
                <tr>
                    <td>{{$r->numero}}</td>
                    <td>{{(new DateTime($r->date_paiement))->format('d-m-Y')}}</td>
                    <td>{{$r->caisse ? $r->caisse->nom : ''}}</td>
                    <td class="text-right">{{number_format($r->montant, 0, ',', ' ')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        var creditRow = 0;
        function escCredit(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }
        function findCreditProduct(){
            var pattern = $('#creditSearch').val();
            var contain = $('#creditResults');
            contain.empty();
            if(!pattern){ return; }
            $.ajax({
                url:'{{route('find_produit')}}',
                type:'GET',
                data:{pattern: pattern},
                success:function(data){
                    var html = '';
                    for(var i=0; i<data.produits.length; i++){
                        var p = data.produits[i];
                        p.stock = p.stock || {quantite: 0};
                        html += '<li class="product-li">' +
                            '<strong>'+p.libelle+'</strong> <small>'+ (p.reference || '') +'</small>' +
                            '<span class="pull-right">Stock: '+p.stock.quantite+' ' +
                            '<button type="button" class="btn btn-xs btn-success add-credit-product" data-id="'+p.id+'" data-libelle="'+escCredit(p.libelle)+'" data-prix="'+p.prix+'" data-stock="'+p.stock.quantite+'">Ajouter</button></span>' +
                            '</li>';
                    }
                    contain.html(html);
                }
            });
        }
        function addCreditProduct(id, libelle, prix, stock){
            creditRow++;
            var rowId = creditRow;
            $('#creditSaleBody').append(
                '<tr id="credit-row-'+rowId+'">' +
                '<td><input type="hidden" name="id[]" value="'+id+'">'+libelle+'</td>' +
                '<td><input type="number" step="0.01" name="prix_unitaire[]" id="credit-prix-'+rowId+'" value="'+prix+'" class="form-control" onchange="evalCreditTotal()"></td>' +
                '<td><input type="number" step="1" min="1" max="'+stock+'" name="quantite[]" id="credit-qte-'+rowId+'" value="1" class="form-control" onchange="evalCreditTotal()"></td>' +
                '<td><input type="number" step="0.01" min="0" name="reduction[]" id="credit-red-'+rowId+'" value="0" class="form-control" onchange="evalCreditTotal()"></td>' +
                '<td class="text-right"><span id="credit-total-'+rowId+'">0</span></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger remove-credit-row" data-row="'+rowId+'">X</button></td>' +
                '</tr>'
            );
            evalCreditTotal();
        }
        function evalCreditTotal(){
            var total = 0;
            for(var i=1;i<=creditRow;i++){
                if(!$('#credit-row-'+i).length){ continue; }
                var prix = parseFloat($('#credit-prix-'+i).val()) || 0;
                var qte = parseFloat($('#credit-qte-'+i).val()) || 0;
                var red = parseFloat($('#credit-red-'+i).val()) || 0;
                var line = (prix - red) * qte;
                $('#credit-total-'+i).text(line.toFixed(0));
                total += line;
            }
            var tva = parseFloat($('#creditTva').val()) || 0;
            total = total + (total * tva / 100);
            $('#creditSaleTotal').text(total.toFixed(0));
        }
        $(document).on('click', '.add-credit-product', function () {
            addCreditProduct($(this).data('id'), $(this).data('libelle'), $(this).data('prix'), $(this).data('stock'));
        });
        $(document).on('click', '.remove-credit-row', function () {
            $('#credit-row-'+$(this).data('row')).remove();
            evalCreditTotal();
        });
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('ventes')}}"><strong>Ventes</strong></a></li>
        <li><a href="{{route('bons_credit')}}"><strong>Bons de credit</strong></a></li>
        <li class="active"><strong>{{$bon->numero}}</strong></li>
    </ol>
@endsection
