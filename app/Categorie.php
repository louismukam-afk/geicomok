<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Categorie
 *
 * @property int $id
 * @property string $libelle
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Produit[] $produits
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Categorie extends Model
{

    public function produits()
    {
       return $this->hasMany(Produit::class,'id_categorie');
    }
}
