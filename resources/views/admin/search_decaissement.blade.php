@extends('skeleton')
@section('content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>


    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif


    <div class="col-md-8">
        <form class="form-horizontal" id="search_form" method="get" action="{{URL::to('/admin/retrait/')}}">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="search">Rechercher par nom: </label>
                    <div class="input-group">
                        <input type="search" name="search" placeholder="nom du personnel" class="form-control" autofocus autocomplete="true">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>Rechercher</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="col-md-8">
        <a data-toggle="modal" data-backdrop="false" href="#add_retrait" class="btn btn-warning"><span class="fa fa-money"></span> Nouvelle depense</a>
    </div>

    <div class="col-md-8">
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <th>#</th>
                <th>Nom du personnel</th>
                <th>Motif</th>
                <th>Montant</th>
                <th>Ligne Budgetaire</th>
                <th>Categorie de budget</th>
                <th>Date</th>

                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($decaissement as $d)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>
                            {{$d->nom_personnel}}
                        <td>{{$d->motif}}</td>
                        <td><a href="#edit_payement" data-toggle="modal" onclick="editer_payement({{$d->id}},{{$d->montant}})"><strong><span class="glyphicon glyphicon-edit"></span> {{$d->montant}}</strong></a> </td>
                        <td>{{$d->ligne_budgetaire ? $d->ligne_budgetaire->libelle_ligne : '' }}</td>
                        <td>{{$d->categorie_budgetaire ? $d->categorie_budgetaire->libelle : '' }}</td>
                        <td>{{$d->date}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $decaissement->render()) ); ?></div>

    </div>

    <div class="modal fade" id="add_retrait">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="add_retrait_form" action="{{ url('/admin/retrait/store/') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <div class="form-group">
                                <label >Personnels : </label>
                                <select name="personnel" form="add_retrait_form" class="form-control autocomplete" autocomplete="true" >
                                    @foreach($personnel as $p)
                                        <option value="{{  $p->id  }}" @if($p->id == old('personnel')) selected @endif>{{  $p->nom  }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Date depense : </label>
                                <input class="form-control" id="date" name="date" type="date"  value="{{ old('date') }}" required AUTOFOCUS>

                            </div>
                            <div class="form-group">
                                <label>Caisse de sortie : </label>
                                <select name="id_caisse" form="add_retrait_form" class="form-control" required>
                                    <option value="">-- Choisir --</option>
                                    @foreach($caisses_sortie as $caisse)
                                        <option value="{{$caisse->id}}">{{$caisse->nom}} ({{number_format($caisse->solde(), 2)}})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label >Ligne budgetaire : </label>
                                <select name="ligne_budgetaire" id="ligne_budgetaire" form="add_retrait_form" class="form-control autocomplete" autocomplete="true" onchange="onBudgetLineChange('ligne_budgetaire', 'categories_options')" required>
                                <option value="">{{  trans('admin.select_item') }}</option>

                                    @foreach($ligne as $li)
                                        <option value="{{  $li->id  }}" @if($li->id == old('ligne_budgetaire')) selected @endif >{{  $li->libelle_ligne  }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label >categorie de budget : </label>
                                <select name="categorie_budgetaire" id="categorie_budgetaire" form="add_retrait_form" class="form-control autocomplete" autocomplete="true" required>
                                <option value="">{{  trans('admin.select_item') }}</option>
                                <optgroup id="categories_options">

                                </optgroup>

                                </select>
                            </div>

                                    <div class="form-group" >
                                        <label >Montant depense : <span class="required">*</span></label>
                                        <input class="form-control" name="montant" type="number"  value="{{ old('montant') }}" min="0" required>

                                    </div>
                                    <div class="form-group">
                                        <label >Motif : <span class="required">*</span></label>
                                        <textarea name="motif" form="add_retrait_form" class="form-control" required>{{old('motif')}}</textarea>
                                    </div>

                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                    </form>


                        </div>





                    </div>


                </div>
            </div>

            <div class="modal fade" id="edit_payement">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">Remplir les champs</h4>
                        </div>
                        <div class="modal-body">
                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_payement_form" action="{{ route('update_retrait_amount') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" id="edit_id" value=""/>
                                <fieldset>
                                    <div class="form-group">
                                        <label >Montant depense : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_montant" name="montant" type="number"  value="{{ old('montant') }}" required min="0">
                                    </div>
                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function editer_payement(id,montant)
                {

                    document.getElementById("edit_id").value = id;
                    document.getElementById("edit_montant").value = montant;

                }

                function editer_retrait(id,id_personnel,motif,id_ligne_budgetaire,id_categorie_budgetaire)
                    {
                        document.getElementById("id_retrait").value = id;
                        document.getElementById("edit_personnel").value = id_personnel;
                        document.getElementById("edit_motif").value = motif;
                        document.getElementById("edit_ligne_budgetaire").value = id_ligne_budgetaire;
                        onBudgetLineChange()
                    }

                function onBudgetLineChange(elementId, containerId) {
                    const elId = elementId ? elementId : 'edit_ligne_budgetaire'
                    const lineId = document.getElementById(elId).value;
                    $.ajax({
                        url: '{{url("/comptabilite/ligne/get-data")}}/'+ lineId,
                        method: 'GET',
                        success: function (data) {
                            fillCategoriesOptions(data, containerId)
                        },
                        error: function() {
                            document.getElementById("categories_options").innerHTML = ''
                            printErrorMsg(`@lang("admin.error_occured")`);
                        }
                    })
                }

                function fillCategoriesOptions(data, containerId) {
                    const contId = containerId ? containerId : 'edit_categories_options'
                    const categoryContainer = document.getElementById(contId);
                    categoryContainer.innerHTML = '';
                    for (var i = 0; i < data.length; i++) {
                        categoryContainer.innerHTML += `<option value="${data[i].categoryId}">${data[i].categoryName}</option>`
                    }
                }
                
                
            </script>
        @endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('dashboard')}}"><strong>Administration</strong></a></li>
        <li><a href="{{route('comptabilite')}}"><strong>Comptabilite</strong></a></li>
        {{--<li><a href="{{route('index_ligne')}}"><strong>Lignes Budgétaires</strong></a></li>--}}
        <li class="active">Dépenses</li>
    </ol>
@endsection

