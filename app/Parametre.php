<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Parametre
 *
 * @property int $id
 * @property string $nom
 * @property string $valeur
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereValeur($value)
 * @mixin \Eloquent
 */
class Parametre extends Model
{
    //
}
