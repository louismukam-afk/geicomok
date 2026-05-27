<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class EntreeSpecialeEcheance extends Model
{
    protected $table = 'entree_speciale_echeances';

    public function entreeSpeciale()
    {
        return $this->belongsTo(EntreeSpeciale::class, 'id_entree_speciale');
    }

    public function reste()
    {
        return max(0, (float)$this->montant - (float)$this->montant_paye);
    }
}
