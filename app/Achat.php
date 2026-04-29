<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Achat
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property float $prix
 * @property int $id_livraison
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Livraison $livraison
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereIdLivraison($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Achat extends Model
{


    public function produit()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }

    public function livraison()
    {
        return  $this->belongsTo(Livraison::class,'id_livraison');
    }
}
