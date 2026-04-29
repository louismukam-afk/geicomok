<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class donnee_ligne_budgetaires extends Model
{
    public function categorieBudgetaire () {
        return $this->belongsTo(categorie_budgetaires::class, 'id_categorie_budgetaire');
    }
    public function ligneBudgetaire() {
        return $this->belongsTo(ligne_budgetaires::class, 'id_ligne_budgetaire');
    }
}
