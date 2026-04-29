@extends('skeleton')
@section('sub-content')

    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
    </div>
    @if($salaire!=null)
    <div class="col-md-8">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="panel-title">
                   <h3>{{trans('admin.resume')}}</h3>
                </div>
            </div>
            <div class="panel-body">
                <h4>Nom :<strong>{{$personnel->nom}}</strong> </h4>

                <legend style="margin-top: 15px">@lang('admin.mensuel')</legend>
                @if(isset($dernier_payement))
                    <h4 class="alert alert-info"><strong> {{trans('admin.date_dernier_payement').': '.$dernier_payement->date_p}}</strong> </h4>
                @endif


                <div class="alert alert-warning"><strong>{{trans('admin.salaire_base')}}</strong>
                    <p><strong>{{$salaire->montant.' '.trans('inscription.devise')}}</strong></p>

                </div>
                <div class="alert alert-info"><strong>{{trans('admin.compte_heure_')}}</strong>
                    <p><strong>{{$heure_realisee.' '.trans('admin.heure')}}</strong></p>

                </div>

                <div class="alert alert-warning"><strong>{{trans('admin.taux')}}</strong>
                    <p><strong>{{$taux.' '.trans('inscription.devise').' / '.trans('admin.heure')}}</strong></p>

                </div>

                <div class="alert alert-success"><strong>Total</strong>
                    <p><strong>{{$salaire->montant+$somme_heure.' '.trans('inscription.devise')}}</strong></p>

                </div>
                <div class="col-md-12">
                    <form accept-charset="UTF-8" role="form" method="POST" id="pay_staff" action="{{ url('/comptabilite/payement-salaire/store/') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{$personnel->id }}">
                        <input type="hidden" name="heure_realisee" value="{{$heure_realisee}}">
                        <input type="hidden" name="taux" value="{{$taux}}">
                        <input type="hidden" name="total" value="{{$salaire->montant+$somme_heure}}">

                        <fieldset>
                            <div class="form-group" style="display: none">
                                <label>{{  trans('admin.mois')  }} : </label>
                                <select form="pay_staff" name="mois" class="form-control">
                                    <option value="1" @if(date('m')==1) selected @endif>@lang('admin.mois1')</option>
                                    <option value="2" @if(date('m')==2) selected @endif>@lang('admin.mois2')</option>
                                    <option value="3" @if(date('m')==3) selected @endif>@lang('admin.mois3')</option>
                                    <option value="4" @if(date('m')==4) selected @endif>@lang('admin.mois4')</option>
                                    <option value="5" @if(date('m')==5) selected @endif>@lang('admin.mois5')</option>
                                    <option value="6" @if(date('m')==6) selected @endif>@lang('admin.mois6')</option>
                                    <option value="7" @if(date('m')==7) selected @endif>@lang('admin.mois7')</option>
                                    <option value="8" @if(date('m')==8) selected @endif>@lang('admin.mois8')</option>
                                    <option value="9" @if(date('m')==9) selected @endif>@lang('admin.mois9')</option>
                                    <option value="10" @if(date('m')==10) selected @endif>@lang('admin.mois10')</option>
                                    <option value="11" @if(date('m')==11) selected @endif>@lang('admin.mois11')</option>
                                    <option value="12" @if(date('m')==12) selected @endif>@lang('admin.mois12')</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <h4>{{  trans('admin.periode')  }}  </h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label >@lang('admin.du') :</label>
                                        <input class="form-control datepicker" name="du" type="text" readonly placeholder="yyyy/mm/dd" value="{{$dd}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label >@lang('admin.au') :</label>

                                        <input readonly class="form-control datepicker" name="au" type="text"   value="{{$df}}" placeholder="yyyy/mm/dd">
                                    </div>

                                </div>

                            </div>
                            <div class="form-group">
                                <label>{{  trans('admin.primes')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control" name="primes" type="number" step="0.01"  value="{{ old('primes') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                        </fieldset>

                        <fieldset style="margin-top: 15px">
                            <legend>@lang('admin.impots')</legend>

                            <div class="col-md-6">
                                <label>IRPP : </label>
                                <div class="input-group">
                                    <input class="form-control" name="IRPP" type="number" step="0.01"  value="{{ old('IRPP') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>CAC IRPP : </label>
                                <div class="input-group">
                                    <input class="form-control" name="CAC" type="number" step="0.01"  value="{{ old('CAC') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('admin.CFC') : </label>
                                <div class="input-group">
                                    <input class="form-control" name="CFC" type="number" step="0.01"  value="{{ old('CFC') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>CRTV : </label>
                                <div class="input-group">
                                    <input class="form-control" name="CRTV" type="number" step="0.01"  value="{{ old('CRTV') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>@lang('admin.taxe_communale') : </label>
                                <div class="input-group">
                                    <input class="form-control" name="taxe_communale" type="number" step="0.01"  value="{{ old('taxe_communale') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>
                            </div>
                        </fieldset>

                            <fieldset style="margin-top: 15px">
                                <legend>@lang('admin.autre_retenues')</legend>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{  trans('admin.acomptes')  }} : </label>
                                        <div class="input-group">
                                            <input class="form-control" name="acomptes" type="number" step="0.01"  value="{{ old('acomptes') }}">
                                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6">  <div class="form-group">
                                        <label>CNPS : </label>
                                        <div class="input-group">
                                            <input class="form-control" name="CNPS" type="number" step="0.01"  value="{{ old('CNPS') }}">
                                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                        </div>

                                    </div></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{  trans('admin.cas_sociaux')  }} : </label>
                                        <div class="input-group">
                                            <input class="form-control" name="cas_sociaux" type="number" step="0.01"  value="{{ old('cas_sociaux') }}">
                                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{  trans('admin.assurances')  }} : </label>
                                        <div class="input-group">
                                            <input class="form-control" name="assurances" type="number" step="0.01"  value="{{ old('assurances') }}">
                                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{  trans('admin.autre_retenues')  }} : </label>
                                        <div class="input-group">
                                            <input class="form-control" name="autre_retenues" type="number" step="0.01"  value="{{ old('autre_retenues') }}">
                                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                        </div>

                                    </div>
                                </div>











                        </fieldset>
                        <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                    </form>
                </div>

            </div>

        </div>
    </div>
    @else
        <div class="alert alert-danger">{{trans('admin.salaire_non_defini')}}</div>
    @endif


@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('comptabilite')}}">@lang('admin.comptabilite')</a></strong></li>
    <li><strong><a href="{{url('comptabilite/payement-salaire')}}">@lang('admin.search_personnel')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection