<div class="modal fade" id="mod_list_ventes">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">X</button>
                <h4 class="modal-title">Selectionner la période: </h4>
            </div>
            {{--  <div class="col-md-12">
                         <div class="form-group">
                             <label>caissière:  </label>
                             <div class="input-group">
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                 <select form="form_list_ventes" class="form-control" name="client" >
                                     <option value="0">   @lang('main.tous')  </option>
                                     @foreach($utilisateur as $u)
                                         <option value="{{$u->id}}">{{$u->name}}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                     </div>--}}
            <div class="modal-body">
                <form accept-charset="UTF-8" role="form" id="form_list_ventes" method="get" action="{{route('liste_ventes')}}">
                    <fieldset style="">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Clients:  </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <select form="form_list_ventes" class="form-control" name="client" >
                                        <option value="0">   @lang('main.tous')  </option>
                                        @foreach($Clients as $c)
                                            <option value="{{$c->id}}">{{$c->nom}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de début: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input type="date" name="dd" class="form-control">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date de fin: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <input type="date" name="df" class="form-control">

                                </div>
                            </div>
                        </div>


                        <input class="btn  btn-success " type="submit" value="confirmer">


                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>




{{-- Tableau des bénéfices par produit --}}
{{-- <h3>Ventes par produit avec bénéfices</h3>
 <table class="table table-bordered table-striped">
     <thead>
     <tr>
         <th>#</th>
         <th>Produit</th>
         <th>Quantité totale</th>
         <th>Total ventes</th>
         <th>Bénéfice réalisé</th>
     </tr>
     </thead>
     <tbody>
     <?php $j = 1; $benefTotal = 0; ?>
     @foreach($beneficesParProduit as $prod)
         <?php $benefTotal += $prod['total_benefice']; ?>
         <tr>
             <td>{{ $j++ }}</td>
             <td>{{ $prod['produit'] }}</td>
             <td>{{ $prod['quantite_totale'] }}</td>
             <td>{{ number_format($prod['total_vente'], 2) }} @lang('main.devise')</td>
             <td><strong>{{ number_format($prod['total_benefice'], 2) }} @lang('main.devise')</strong></td>
         </tr>
     @endforeach
     </tbody>
     <tfoot>
     <tr>
         <th colspan="4" class="text-right">Bénéfice total</th>
         <th>{{ number_format($benefTotal, 2) }} @lang('main.devise')</th>
     </tr>
     </tfoot>
 </table>
--}}