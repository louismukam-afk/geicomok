<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Client
 *
 * @property int $id
 * @property string|null $nom
 * @property string|null $telephone
 * @property string|null $ville
 * @property int $id_pays
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Facture[] $factures
 * @property-read \GEICOM\Pays $pays
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereIdPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereVille($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @property string|null $adresse
 * @property string|null $boite_postale
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereBoitePostale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereEmail($value)
 */
class Client extends Model
{
    public function pays()
    {
        return $this->belongsTo(Pays::class,'id_pays');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class,'id_client');
    }
}

