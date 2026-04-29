<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class categorie_budgetaires extends Model
{
    protected $guarded = [];

    public function donneesLigneBudgetaire() {
        return $this->hasMany(donnee_ligne_budgetaires::class, 'id_categorie_budgetaire');
    }

    public function decaissement() {
        return $this->hasMany(decaissement::class, 'id_categorie_budgetaire');
    }
}
