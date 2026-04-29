<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Commande
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property int $id_bon_commande
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\BonCommande $BonCommande
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereIdBonCommande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Commande extends Model
{
    public function BonCommande()
    {
        return  $this->belongsTo(BonCommande::class,'id_bon_commande');
    }

    public function produit()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }

}
