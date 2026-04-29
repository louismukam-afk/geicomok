<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\BonCommande
 *
 * @property int $id
 * @property int $id_fournisseur
 * @property string|null $numero
 * @property int $valide
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Fournisseur $fournisseur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereIdFournisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereValide($value)
 * @mixin \Eloquent
 */
class BonCommande extends Model
{
    public function fournisseur()
    {
        return  $this->belongsTo(Fournisseur::class,'id_fournisseur');
    }
}
