@extends('skeleton')
@section('content')

    <link href="{{ URL::asset('css/facture.css')  }}" rel="stylesheet" />
    <div class="btn-group">
        <a href="#" onclick="window.print()"  class="btn btn-warning"><span class="glyphicon glyphicon-print"></span> Imprimer</a>
        <div class="btn btn-info" onclick="sendByMail()"><span class="glyphicon glyphicon-envelope"></span> Envoyer par mail</div>

    </div>


    <div class="col-md-12">
    <div class="recu">
        <div class="header-receipt">
            <div class="left">
                <div><strong class="text-uppercase">{{$param->where('nom','=','nom_e')->first()->valeur}}</strong></div>
                <div class="text-compress-1">
                    <?php
                    $adresse=$param->where('nom','=','adresse')->first();
                    $bp=$param->where('nom','=','boite_postale')->first();
                    $tel=$param->where('nom','=','telephone')->first();
                    $web=$param->where('nom','=','web')->first();
                    $email=$param->where('nom','=','email')->first();

                    ?>
                        <div><strong class="text-uppercase traduction">{{$boutique->nom}}</strong></div>
                        <div><strong class="text-uppercase traduction">{{$boutique->adresse?$boutique->adresse:($adresse?$adresse->valeur:'')}}</strong></div>
                        <div><strong class="text-uppercase traduction">{{$bp?$bp->valeur:''}}</strong></div>
                        <div><strong class="text-uppercase traduction">{{$boutique->telephone?$boutique->telephone:($tel?$tel->valeur:'')}}</strong></div>
                        <div><strong class="traduction">{{$web?$web->valeur:''}}</strong></div>
                        <div><strong class="traduction">{{$boutique->email?$boutique->email:($email?$email->valeur:'')}}</strong></div>

                </div>

            </div>
        </div>

        <h3>Rapport des ventes: Du {{(new DateTime($dd))->format('d/m/Y')}} au {{(new DateTime($df))->format('d/m/Y')}}</h3>

        <div class="body-receipt" >
            <table class="table table-striped ">
                <thead class="thead-inverse">
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
                </thead>

                <tbody>
                <?php $total=0 ;?>

                @foreach($factures as $f)
                    <?php $minTotal=0 ;?>
                    <tr class="bg-primary">
                        <td class="bg-primary" colspan="4" style="padding: 4px 4px!important;"><h4 class="pull-right"><strong>{{$f->numero}} ( {{(new DateTime($f->date_vente))->format('d/m/Y')}} )</strong></h4></td>
                    </tr>
                    @foreach($f->ventes as $v)

                        <tr>
                            <td><strong>{{$v->produit->libelle}}</strong></td>
                            <td>{{$v->prix_unitaire}}</td>
                            <td>{{$v->quantite}}</td>
                            <td>{{$v->total}}</td>
                        </tr>
                        <?php $minTotal+=$v->total ?>

                    @endforeach
                    @if($f->tva>0)
                        <tr>
                            <td colspan="2"><strong>TVA</strong></td>
                            <td colspan="2"><strong class="pull-right">{{$f->tva}} % ( {{round($minTotal*$f->tva/100,2)}} @lang('main.devise') ) </strong></td>
                        </tr>

                    @endif
                    <tr>
                        <td colspan="2"><strong>TOTAL</strong></td>
                        <td colspan="2"><strong class="pull-right">{{round($f->total,2)}} @lang('main.devise')</strong></td>
                    </tr>
                    <?php $total+=$f->total ?>

                @endforeach


                <tr>
                    <td colspan="2" style="padding: 6px 4px!important;"><h4><strong>TOTAL VENTES</strong></h4></td>
                    <td colspan="2" style="padding: 6px 4px!important;"><h4 class="pull-right"><strong >{{number_format($total,2)}} @lang('main.devise')</strong></h4></td>
                </tr>


                </tbody>
            </table>
        </div>


    </div>
</div>


@endsection

@section('scripts')
    <script>
        function sendByMail() {
            startLoading();
            $.ajax({
                url:'{{route('send_rapport_ventes')}}',
                type:'GET',
                data:'date_debut={{$dd}}&date_fin={{$df}}',
                success:function(data) {
                    stopLoading();
                    if(data=='ok')
                        showAlert('Rapport envoyé',0);

                }
                ,
                error:function(data) {
                    stopLoading();
                    console.log(data);
                    oe=data.statusText;
                    json=data.responseJSON;
                    if(json)
                        showAlert('Une erreur est survenue: '+json.error,1);
                    else
                        showAlert('Une erreur est survenue: '+oe,1);

                }
            })

        }
    </script>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb" style="background-color: transparent;padding: 4px 10px">
        <li><a href="{{route('home')}}"><strong>Accueil</strong></a></li>
        <li><a href="{{route('index_rapport')}}"><strong>Rapports</strong></a></li>
        <li class="active"><strong>{{$title}}</strong></li>
    </ol>
@endsection

