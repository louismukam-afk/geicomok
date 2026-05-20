<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

/**
 * GEICOM\Livraison
 *
 * @property int $id
 * @property int $id_fournisseur
 * @property string|null $numero
 * @property string $date_approv
 * @property float $total
 * @property int $id_commande
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Achat[] $achats
 * @property-read \GEICOM\Commande $commande
 * @property-read \GEICOM\Fournisseur $fournisseur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereDateApprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereIdCommande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereIdFournisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Livraison extends Model
{
    public function fournisseur()
    {
        return  $this->belongsTo(Fournisseur::class,'id_fournisseur');
    }
    public function commande()
    {
        return  $this->belongsTo(Commande::class,'id_commande');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class,'id_livraison');
    }

    public function utilisateur()
    {
        return  $this->belongsTo(User::class,'id_user');
    }
    public function caisse()
    {
        return  $this->belongsTo(Caisse::class,'id_caisse');
    }
}
