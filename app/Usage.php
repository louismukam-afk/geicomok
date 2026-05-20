<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Usage
 *
 * @mixin \Eloquent
 */
class Usage extends Model
{
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }
}
