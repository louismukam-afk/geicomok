<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Salaire
 *
 * @property int $id
 * @property int $id_personnel
 * @property float $montant
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Salaire extends Model
{
    //
}
