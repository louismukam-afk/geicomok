<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class securite extends Model
{
    public function produit()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }
    public function boutique()
    {
        return  $this->belongsTo(Boutique::class,'id_boutique');
    }
    public function user()
    {
        return  $this->belongsTo(User::class,'id_user');
    }
    public function stock()
    {
        return  $this->belongsTo(Stock::class,'id_stock');
    }
}
