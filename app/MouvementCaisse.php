<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class MouvementCaisse extends Model
{
    protected $table = 'mouvements_caisse';

    protected $fillable = [
        'id_caisse',
        'id_user',
        'type',
        'source_type',
        'source_id',
        'montant',
        'solde_avant',
        'solde_apres',
        'date_mouvement',
        'description',
    ];

    public function caisse()
    {
        return $this->belongsTo(Caisse::class, 'id_caisse');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public static function enregistrer($idCaisse, $type, $montant, $sourceType = null, $sourceId = 0, $description = null, $date = null)
    {
        $last = self::where('id_caisse', $idCaisse)->orderBy('date_mouvement', 'desc')->orderBy('id', 'desc')->first();
        $soldeAvant = $last ? $last->solde_apres : 0;
        $montant = floatval($montant);
        $soldeApres = $type === 'entree' ? $soldeAvant + $montant : $soldeAvant - $montant;

        $mouvement = new self();
        $mouvement->id_caisse = $idCaisse;
        $mouvement->id_user = \Auth::check() ? \Auth::user()->id : 0;
        $mouvement->type = $type;
        $mouvement->source_type = $sourceType;
        $mouvement->source_id = $sourceId;
        $mouvement->montant = $montant;
        $mouvement->solde_avant = $soldeAvant;
        $mouvement->solde_apres = $soldeApres;
        $mouvement->date_mouvement = $date ?: date('Y-m-d H:i:s');
        $mouvement->description = $description;
        $mouvement->save();

        return $mouvement;
    }
}
