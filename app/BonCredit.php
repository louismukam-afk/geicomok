<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class BonCredit extends Model
{
    protected $table = 'bons_credit';

    const STATUT_ACTIF = 'actif';
    const STATUT_INACTIF = 'inactif';
    const STATUT_CLOTURE = 'cloture';

    public static function statuts()
    {
        return [
            self::STATUT_ACTIF => 'Actif',
            self::STATUT_INACTIF => 'Inactif',
            self::STATUT_CLOTURE => 'Cloture',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function factures()
    {
        return $this->hasMany(Facture::class, 'id_bon_credit');
    }

    public function echeances()
    {
        return $this->hasMany(BonCreditEcheance::class, 'id_bon_credit');
    }

    public function remboursements()
    {
        return $this->hasMany(BonCreditRemboursement::class, 'id_bon_credit');
    }

    public function montantConsomme()
    {
        return (float)$this->factures()->sum('total');
    }

    public function montantRembourse()
    {
        return (float)$this->remboursements()->sum('montant');
    }

    public function soldeDisponible()
    {
        return (float)$this->montant_credit - $this->montantConsomme();
    }

    public function resteARembourser()
    {
        return $this->montantConsomme() - $this->montantRembourse();
    }
}
