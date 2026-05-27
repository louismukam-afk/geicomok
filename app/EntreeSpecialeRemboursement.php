<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class EntreeSpecialeRemboursement extends Model
{
    protected $table = 'entree_speciale_remboursements';

    public function entreeSpeciale()
    {
        return $this->belongsTo(EntreeSpeciale::class, 'id_entree_speciale');
    }

    public function echeance()
    {
        return $this->belongsTo(EntreeSpecialeEcheance::class, 'id_echeance');
    }

    public function caisse()
    {
        return $this->belongsTo(Caisse::class, 'id_caisse');
    }
}
