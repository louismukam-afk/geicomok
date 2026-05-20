<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Facture
 *
 * @property int $id
 * @property string|null $numero
 * @property string $date_vente
 * @property int $id_client
 * @property float $total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Vente[] $ventes
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereDateVente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereIdClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $reduction
 * @property float $tva
 * @property int $paye
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture wherePaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereTva($value)
 */
class Facture extends Model
{
    public function ventes()
    {
        return $this->hasMany(Vente::class,'id_facture');
    }
    public function client()
    {
        return  $this->belongsTo(Client::class,'id_client');
    }
 public function vendeur()
    {
        return  $this->belongsTo(User::class,'id_user');
    }
    public function user()
    {
        return  $this->belongsTo(User::class,'id_user');
    }
    public function caisse()
    {
        return  $this->belongsTo(Caisse::class,'id_caisse');
    }
    public function bonCredit()
    {
        return  $this->belongsTo(BonCredit::class,'id_bon_credit');
    }


}
