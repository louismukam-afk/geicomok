<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class EntreeSpeciale extends Model
{
    protected $table = 'entrees_speciales';

    const TYPE_PRET = 'pret';
    const TYPE_DON = 'don';
    const TYPE_APPORT_PDG = 'apport_pdg';
    const TYPE_CAPITAL = 'capital';

    const STATUT_ACTIF = 'actif';
    const STATUT_REMBOURSE = 'rembourse';

    public static function types()
    {
        return [
            self::TYPE_PRET => 'Pret',
            self::TYPE_DON => 'Don',
            self::TYPE_APPORT_PDG => 'Apport PDG',
            self::TYPE_CAPITAL => 'Augmentation capital',
        ];
    }

    public function caisse()
    {
        return $this->belongsTo(Caisse::class, 'id_caisse');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function echeances()
    {
        return $this->hasMany(EntreeSpecialeEcheance::class, 'id_entree_speciale');
    }

    public function remboursements()
    {
        return $this->hasMany(EntreeSpecialeRemboursement::class, 'id_entree_speciale');
    }

    public function montantRembourse()
    {
        return (float)$this->remboursements()->sum('montant');
    }

    public function resteARembourser()
    {
        return max(0, (float)$this->montant - $this->montantRembourse());
    }
}
