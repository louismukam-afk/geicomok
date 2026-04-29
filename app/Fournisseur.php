<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Fournisseur
 *
 * @property int $id
 * @property string $nom
 * @property string|null $adresse
 * @property string|null $ville
 * @property int $id_pays
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Livraison[] $livraisons
 * @property-read \GEICOM\Pays $pays
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereIdPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereVille($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @property string|null $telephone
 * @property string|null $boite_postale
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereBoitePostale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereTelephone($value)
 */
class Fournisseur extends Model
{
    public function pays()
    {
        return $this->belongsTo(Pays::class,'id_pays');
    }

    public function livraisons()
    {
        return $this->hasMany(Livraison::class,'id_fournisseur');
    }
}
