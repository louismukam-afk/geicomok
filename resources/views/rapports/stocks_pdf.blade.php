
<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="{{asset('css/custom.css')}}">

<link href="{{ URL::asset('css/facture-pdf.css')  }}" rel="stylesheet" />


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

                <tbody>
                <?php $total=0 ;?>

                @foreach($produits as $p)
                    <tr>
                        <td><strong>{{$p->libelle}}</strong></td>
                        <td><strong>{{$p->stock->quantite}}</strong></td>
                        <td>{{$p->ventes?$p->ventes->sum('quantite'):0}}</td>
                        <td>{{$p->achats?$p->achats->sum('quantite'):0}}</td>
                    </tr>
                @endforeach


                </tbody>
            </table>
        </div>


    </div>
</div>

