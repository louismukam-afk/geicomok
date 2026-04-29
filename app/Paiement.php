<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Paiement
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $id_user
 * @property float $montant
 * @property string $date_p
 * @property string|null $numero
 * @property string $mois
 * @property float $primes
 * @property float $acomptes
 * @property float $cnps
 * @property float $cas_social
 * @property float $assurance
 * @property float $autre_retenues
 * @property float $total
 * @property float $total_retenu
 * @property float $net_a_payer
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Personnel $personnel
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereAcomptes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereAssurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereAutreRetenues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereCasSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereCnps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereDateP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereNetAPayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement wherePrimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereTotalRetenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Paiement extends Model
{
    public function personnel()
    {
        return  $this->belongsTo(Personnel::class,'id_personnel');
    }
}
