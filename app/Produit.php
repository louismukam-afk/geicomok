<?php

namespace GEICOM;

use GEICOM\Http\Controllers\admin\CategorieController;
use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Produit
 *
 * @property int $id
 * @property string $libelle
 * @property string|null $description
 * @property int $quantite_minimale
 * @property string|null $reference
 * @property int $id_categorie
 * @property float $prix
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Categorie $categorie
 * @property-read \GEICOM\Stock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereIdCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereQuantiteMinimale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $prix_achat
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Vente[] $ventes
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit wherePrixAchat($value)
 */
class Produit extends Model
{
    public function categorie()
    {
      return  $this->belongsTo(Categorie::class,'id_categorie');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class,'id_produit');
    }
    public function ventes()
    {
        return $this->hasMany(Vente::class,'id_produit');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class,'id_produit');
    }



    public function produit_lies()
    {
        return $this->hasMany(ProduitLies::class,'id_produit_parent');
    }

    public function produit_lie()
    {
        return $this->hasOne(ProduitLies::class,'id_produit_parent');
    }

    public function securite()
    {
        return  $this->hasMany(securite::class,'id_produit');
    }

    public function produit_parents()
    {
        return  $this->hasMany(ProduitLies::class,'id_produit');
    }
}
