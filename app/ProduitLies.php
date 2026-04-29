<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class ProduitLies extends Model
{
    public function produit_p()
    {
        return  $this->belongsTo(Produit::class,'id_produit_parent');
    }

    public function produit_c()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }
}
