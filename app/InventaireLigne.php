<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class InventaireLigne extends Model
{
    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class, 'id_inventaire');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie');
    }
}
