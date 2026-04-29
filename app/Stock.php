<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Stock
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property string $dernier_achat
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereDernierAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model
{
    public function produit()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }
    public function securite()
    {
        return  $this->hasMany(securite::class,'id_stock');
    }


}
