<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class BonCreditRemboursement extends Model
{
    protected $table = 'bon_credit_remboursements';

    public function bon()
    {
        return $this->belongsTo(BonCredit::class, 'id_bon_credit');
    }

    public function echeance()
    {
        return $this->belongsTo(BonCreditEcheance::class, 'id_echeance');
    }

    public function caisse()
    {
        return $this->belongsTo(Caisse::class, 'id_caisse');
    }
}
