<?php

use Illuminate\Database\Seeder;

class ProduitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array=[];
        $date=date('Y-m-d H:i:s');
        for ($i=0;$i<5000;$i++){
            $prix=random_int(100,3500)*10;
            $prix_a=$prix-random_int(10,200)*10;
            if($prix_a<=0)
                $prix_a=random_int(55,90)*10;
            $prix_min=$prix-random_int(10,20)*10;

            if($prix_min>$prix || $prix_min<=$prix_a)
                $prix_min=intval(($prix+$prix_a)/2);

            $array=['id'=>($i+5004),'libelle'=>str_random('10'),'id_categorie'=>1,'prix'=>$prix,'prix_achat'=>$prix_a,'prix_minimum'=>$prix_min,'created_at'=>$date,'updated_at'=>$date];
            \GEICOM\Produit::insert($array);
        }
    }

}
