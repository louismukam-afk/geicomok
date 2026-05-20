<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    const TYPE_ENTREE = 'entree';
    const TYPE_SORTIE = 'sortie';
    const TYPE_CENTRALE = 'centrale';

    protected $fillable = ['nom', 'type', 'id_boutique', 'active'];

    public static function types()
    {
        return [
            self::TYPE_ENTREE => 'Caisse d entree',
            self::TYPE_SORTIE => 'Caisse de sortie',
            self::TYPE_CENTRALE => 'Caisse centrale',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'caisse_user', 'id_caisse', 'id_user');
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementCaisse::class, 'id_caisse');
    }

    public function solde()
    {
        $last = $this->mouvements()->orderBy('date_mouvement', 'desc')->orderBy('id', 'desc')->first();
        return $last ? $last->solde_apres : 0;
    }
}
