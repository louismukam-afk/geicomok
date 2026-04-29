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

        <h3>Rapport des stocks: Du {{(new DateTime($dd))->format('d/m/Y')}} au {{(new DateTime($df))->format('d/m/Y')}}</h3>

        <div class="body-receipt" >
            <table class="table table-striped ">
                <thead class="thead-inverse">
                <tr>
                    <th>Produit</th>
                    <th>Stock actuel</th>
                    <th>Vendu</th>
                    <th>Acheté</th>
                </tr>
                </thead>

                <tbody id="stock-container">


                </tbody>
            </table>
        </div>


    </div>
</div>


@endsection

@section('scripts')
    <script>

        $(function () {
            sCont=$('#stock-container');
            sCont.empty();
            $.ajax({
                url:'{{route('get_rapport_stocks')}}',
                type:'GET',
                data:{
                    date_debut:'{{$dd}}',
                    date_fin:'{{$df}}'
                },
                beforeSend:function () {
                    startLoading();
                },
                success:function (data) {
                    produits=data['produits'];
                    str='';
                    for (i=0;i<produits.length;i++){
                        sv=0;
                        sma=0;
                        if(produits[i].ventes){
                            for(j=0;j<produits[i].ventes.length;j++){
                                sv+=produits[i].ventes[j].quantite;
                            }
                        }
                        if(produits[i].achats){
                            for(j=0;j<produits[i].achats.length;j++){
                                sma+=produits[i].achats[j].quantite;
                            }
                        }
                        str='  <tr>'+
                            '<td><a href="#"> <strong>'+ produits[i].libelle +'</strong></a></td>'+
                            '<td><strong>'+ produits[i].stock.quantite +'</strong></td>'+

                            ' <td>'+
                            sv +
                            '</td>'+
                            '<td><strong>'+ sma +'</strong></td>'+
                            '</tr>';
                        sCont.append(str);

                    }

                },
                error:function () {
                    showAlert('Une erreur est survenue',1);
                },
                complete:function () {
                    stopLoading();
                }
            })


        });



        function sendByMail() {
            startLoading();
            $.ajax({
                url:'{{route('send_rapport_stocks')}}',
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

