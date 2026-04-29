<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class decaissement extends Model
{
    protected  $table='decaissements';

    public $fillable=['id_personnel','motif','montant','date','annee_scolaire','id_ligne_budgetaire','id_categorie_budgetaire'];

    public function personnel() {
        return $this->belongsTo(personnel::class, 'id_personnel');
    }
    public function ligne_budgetaire()
    {
        return $this->belongsTo(ligne_budgetaires::class,'id_ligne_budgetaire');
    }
    public function categorie_budgetaire()
    {
        return $this->belongsTo(categorie_budgetaires::class,'id_categorie_budgetaire');
    }
}
