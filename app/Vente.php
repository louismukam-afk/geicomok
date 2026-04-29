<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Vente
 *
 * @property int $id
 * @property float $prix_unitaire
 * @property int $quantite
 * @property int $id_produit
 * @property int $id_facture
 * @property float $reduction
 * @property float $total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Facture $facture
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereIdFacture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Vente extends Model
{
    public function facture()
    {
        return  $this->belongsTo(Facture::class,'id_facture');
    }

    public function produit()
    {
        return  $this->belongsTo(Produit::class,'id_produit');
    }
}
