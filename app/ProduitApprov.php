<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class ProduitApprov extends Model
{
    public function produit()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }

    public function approvisionnememt()
    {
        return  $this->belongsTo(Approvisionnement::class,'id_approvisionnement');
    }
}
