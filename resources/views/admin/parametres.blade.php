@extends('skeleton')
@section('content')

    <div class="col-md-10">
        <legend> Paramètres</legend>
        <form  method="post" id="parametres_form" action="{{route('store_parametres')}}">
            {{csrf_field()}}
            <fieldset>
                <?php
                $nom_e=$p->where('nom','=','nom_e')->first();
                $tva=$p->where('nom','=','tva')->first();
                $tva_achat=$p->where('nom','=','tva_achat')->first();
                $adresse=$p->where('nom','=','adresse')->first();
                $boite_postale=$p->where('nom','=','boite_postale')->first();
                $telephone=$p->where('nom','=','telephone')->first();
                $web=$p->where('nom','=','web')->first();
                $email=$p->where('nom','=','email',false)->first(); ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nom entreprise</label>
                        <input type="text" class="form-control" name="nom_e" value="{{old('nom_e')}}" placeholder="{{$nom_e?$nom_e->valeur:''}}">

                    </div>
                    <div class="form-group">
                        <label>TVA</label>
                        <input type="text" class="form-control" name="tva" value="{{old('tva')}}" placeholder="{{$tva?$tva->valeur:''}}">
                    </div>
                    <div class="form-group">
                        <label>Adresse</label>
                        <input type="text" class="form-control" name="adresse" value="{{old('adresse')}}" placeholder="{{$adresse?$adresse->valeur:''}}">
                    </div>
                    <div class="form-group">
                        <label>Boite_postale</label>
                        <input type="text" class="form-control" name="boite_postale" value="{{old('boite_postale')}}" placeholder="{{$boite_postale?$boite_postale->valeur:''}}">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>TVA pour les achats</label>
                        <select name="tva_achat" form="parametres_form" class="form-control">
                            <option value="0" @if($tva_achat) @if($tva_achat->valeur==0) selected @endif @endif>Non</option>
                            <option value="1" @if($tva_achat) @if($tva_achat->valeur==1) selected @endif @endif>Oui</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" class="form-control" name="telephone" value="{{old('telephone')}}" placeholder="{{$telephone?$telephone->valeur:''}}">
                    </div>
                    <div class="form-group">
                        <label>Web</label>
                        <input type="text" class="form-control" name="web" value="{{old('web')}}" placeholder="{{$web?$web->valeur:''}}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" name="email" value="{{old('email')}}" placeholder="{{$email?$email->valeur:''}}">
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="form-control btn btn-success" value="Enregistrer">
                </div>
            </fieldset>
        </form>
    </div>
@endsection


@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">

        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection
<!--<div class="paginate" style="text-align: center;"> <?php //echo(str_replace('/?', '?', $category->render()) ); ?></div>-->
