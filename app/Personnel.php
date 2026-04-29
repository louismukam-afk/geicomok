<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Personnel
 *
 * @property int $id
 * @property string $nom
 * @property string|null $date_naiss
 * @property string|null $lieu_naiss
 * @property int $id_pays
 * @property string $sexe
 * @property string|null $date_entree
 * @property string $telephone
 * @property string $addresse
 * @property string|null $autres
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Pays $pays
 * @property-read \GEICOM\Salaire $salaire
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereAddresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereAutres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereDateEntree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereDateNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereIdPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereLieuNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereSexe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereEmail($value)
 */
class Personnel extends Model
{
    protected $table = 'Personnel';
    public function pays()
    {
        return  $this->belongsTo(Pays::class,'id_pays');
    }

    public function salaire()
    {
        return  $this->hasOne(Salaire::class,'id_personnel');
    }
}
