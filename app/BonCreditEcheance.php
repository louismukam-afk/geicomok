<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class BonCreditEcheance extends Model
{
    protected $table = 'bon_credit_echeances';

    public function bon()
    {
        return $this->belongsTo(BonCredit::class, 'id_bon_credit');
    }
}
