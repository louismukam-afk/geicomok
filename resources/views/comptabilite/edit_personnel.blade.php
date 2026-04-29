@extends('skeleton')
@section('sub-content')
    <div class="col-md-8">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
    </div>

<div class="page-header">
    <span><h4>{{$personnel->nom}}</h4></span>
</div>
<div class="col-md-8">


    <form accept-charset="UTF-8" role="form" id="select_sexe" method="POST" action="{{ url('/personnel/update/'.$personnel->id) }}" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <fieldset>

            <div class="form-group">
                <label for="nom" >{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                <input class="form-control" id="nom" name="nom" type="text"  value="{{ $personnel->nom }}" required disabled >
                <div class="row">
                    <div class="col-md-6">
                        <label for="date">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                        <input readonly class="form-control" id="date_naiss" name="date_naiss" type="text"  value="{{ $personnel->date_naiss }}" placeholder="yyyy/mm/dd" required disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ $personnel->lieu_naiss }}" required disabled>
                    </div>
                </div>
              <div class="row">
                    <div class="col-md-6">
                        <label for="sexe">{{  trans('inscription.sexe')  }} : <span class="required">*</span></label>
                        <select name="sexe" form="select_sexe" class="form-control" id="sexe" disabled>
                            <option value="H" @if($personnel->sexe=='H') selected @endif>{{  trans('inscription.homme')  }}</option>
                            <option value="F" @if($personnel->sexe=='F') selected @endif>{{  trans('inscription.femme')  }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="nationalite">{{  trans('inscription.nationalite')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="nationalite" name="nationalite" type="text"  value="{{ $personnel->nationalite }}" required disabled>

                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <label for="diplome">{{  trans('admin.diplome')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="diplome" name="diplome" type="text"  value="{{ $personnel->diplome }}" required disabled >
                    </div>
                    <div class="col-md-6">
                        <label for="num_contribuable">{{  trans('admin.num_contribuable')  }} :</label>
                        <input class="form-control" id="num_contribuable" name="num_contribuable" type="text"  value="{{ $personnel->num_contribuable }}" disabled >

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="num_ss">{{  trans('admin.num_ss')  }} :</label>
                        <input class="form-control" id="num_ss" name="num_ss" type="text"  value="{{$personnel->num_ss }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="categorie">{{  trans('admin.categorie')  }} :</label>
                        <input class="form-control" id="categorie" name="categorie" type="text"  value="{{ $personnel->categorie }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="echelon">{{  trans('admin.echelon')  }} :</label>
                        <input class="form-control" id="echelon" name="echelon" type="text"  value="{{ $personnel->echelon }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="date_rec">{{  trans('admin.date_rec')  }} :</label>
                        <input class="form-control" id="date_rec" name="date_rec" type="date"  value="{{ $personnel->date_entree }}" disabled>
                    </div>
                </div>
                <label >Type :</label>
                <select name="type" form="select_sexe" class="form-control" id="type" disabled>
                    <option value="0" @if($personnel->type==0) selected @endif>@lang('admin.enseignant')</option>
                    <option value="1" @if($personnel->type==1) selected @endif>@lang('admin.autre')</option>
                </select>

                <div class="row">
                    <div class="col-md-6">
                        <label for="adresse">{{  trans('admin.adresse')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ $personnel->adresse }}" required disabled></div>
                    <div class="col-md-6">
                        <label for="email">{{  trans('admin.email')  }} :</label>
                        <input class="form-control" id="email" name="email" type="text"  value="{{ $personnel->email }}" disabled>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6">
                        <label for="tel1">{{  trans('admin.tel1')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="tel1" name="tel1" type="text"  value="{{ $personnel->tel1 }}" required disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="tel2">{{  trans('admin.tel2')  }} :</label>
                        <input class="form-control" id="tel2" name="tel2" type="text"  value="{{ $personnel->tel2 }}" disabled>
                    </div>
                </div>









                <label for="matieres" >{{  trans('admin.titre_matiere')  }} :</label>
                <textarea  class="form-control" id="matieres" name="matieres"  disabled>{{ $personnel->matieres }}</textarea>

                <label for="autre_information">{{  trans('admin.autre')  }} informations:</label>
                <textarea  class="form-control" id="autre_information" name="autre_information" disabled>{{ $personnel->autre_information }}</textarea>


            </div>
            <button style="display:none" class="btn  btn-success pull-right" type="submit"  id="submit" ><span class="glyphicon glyphicon-ok"></span> {{  trans('admin.confirmer')  }}</button>


        </fieldset>
    </form>


    <div class="page-header">
        <h3>{{trans('admin.fonctions')}}</h3>
    </div>
    <ul>
        @if(count($fonctions)==0)
            <span class="alert alert-info">
                {{trans('admin.aucun')}}
            </span>
        @endif
        @foreach($fonctions as $f)
            <li><strong>
                    @foreach($fonction as $f2)
                        @if($f->id_fonction==$f2->id)
                            {{$f2->nom}}
                            <?php break; ?>
                        @endif
                    @endforeach
                </strong></li>
        @endforeach
    </ul>

    @if($titulaire!=null)
        <span class="alert alert-info col-md-6">{{trans('admin.titulaire_de').' '.\gesecole\classe::find($titulaire->id_classe)->nom}}</span>
    @endif

</div>
    <div class="col-md-2">
        <div  href="#" id="change_editable" class="btn btn-xs btn-primary"><span class="glyphicon-edit glyphicon" ></span> @lang('admin.modifier')</div>
    </div>


    <script>
        $(function () {
            $('#change_editable').click(function () {
                $('#nom').removeAttr('disabled');
                $('#date_naiss').removeAttr('disabled');
                $('#lieu_naiss').removeAttr('disabled');
                $('#sexe').removeAttr('disabled');
                $('#diplome').removeAttr('disabled');
                $('#nationalite').removeAttr('disabled');
                $('#tel1').removeAttr('disabled');
                $('#tel2').removeAttr('disabled');
                $('#email').removeAttr('disabled');
                $('#adresse').removeAttr('disabled');
                $('#type').removeAttr('disabled');
                $('#matieres').removeAttr('disabled');
                $('#autre_information').removeAttr('disabled');
                $('#num_contribuable').removeAttr('disabled');
                $('#num_ss').removeAttr('disabled');
                $('#categorie').removeAttr('disabled');
                $('#echelon').removeAttr('disabled');
                $('#date_rec').removeAttr('disabled');

                $('#submit').css('display','block');



            });
        });
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('personnel')}}">@lang('menu.gestion_personnel')</a></strong></li>
    <li><strong><a href="{{url('personnel/search')}}">@lang('admin.search_personnel')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection