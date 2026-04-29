<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    public function securite()
    {
        return  $this->hasMany(securite::class,'id_boutique');
    }
}
