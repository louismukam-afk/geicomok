<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    const STATUT_BROUILLON = 'brouillon';
    const STATUT_CONSOLIDE = 'consolide';

    public function lignes()
    {
        return $this->hasMany(InventaireLigne::class, 'id_inventaire');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
