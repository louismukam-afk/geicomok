<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
    public function magasin()
    {
        return  $this->belongsTo(Boutique::class,'id_magasin');
    }


    public function items()
    {
        return $this->hasMany(ProduitApprov::class,'id_approvisionnement');
    }

    public function utilisateur()
    {
        return  $this->belongsTo(User::class,'id_user');
    }
}
