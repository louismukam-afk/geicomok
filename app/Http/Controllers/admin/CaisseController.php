<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Caisse;
use GEICOM\EntreeSpeciale;
use GEICOM\EntreeSpecialeRemboursement;
use GEICOM\Facture;
use GEICOM\Livraison;
use GEICOM\MouvementCaisse;
use GEICOM\User;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class CaisseController extends Controller
{
    protected $values = [];

    public function __construct()
    {
        $this->values['big_title'] = 'Administration';
        $this->values['title'] = 'Gestion des caisses';
    }

    public function index()
    {
        $cb = session('current_boutique');
        $this->values['caisses'] = Caisse::with('users')->where('id_boutique', $cb->id)->orderBy('nom')->get();
        $this->values['types'] = Caisse::types();
        return view('admin.caisses', $this->values);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required',
            'type' => 'required',
        ]);

        $cb = session('current_boutique');
        Caisse::create([
            'nom' => $request->input('nom'),
            'type' => $request->input('type'),
            'id_boutique' => $cb->id,
            'active' => 1,
        ]);

        return redirect()->route('caisses_management')->with('success', true);
    }

    public function affectations()
    {
        $cb = session('current_boutique');
        $this->values['caisses'] = Caisse::where('id_boutique', $cb->id)->where('active', 1)->orderBy('nom')->get();
        $this->values['users'] = User::orderBy('name')->get();
        $this->values['title'] = 'Affectation des caisses';
        return view('admin.caisses_affectations', $this->values);
    }

    public function storeAffectation(Request $request)
    {
        $this->validate($request, [
            'id_caisse' => 'required|numeric',
            'id_user' => 'required|numeric',
        ]);

        $caisse = Caisse::find($request->input('id_caisse'));
        if ($caisse) {
            $caisse->users()->syncWithoutDetaching([$request->input('id_user')]);
        }

        return redirect()->route('caisses_affectations')->with('success', true);
    }

    public function etat(Request $request)
    {
        $user = \Auth::user();
        $dd = $request->input('dd') ?: date('Y-m-01');
        $df = $request->input('df') ?: date('Y-m-d');
        $ddFull = $dd . ' 00:00:00';
        $dfFull = $df . ' 23:59:59';

        $caisses = Caisse::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->orderBy('nom')->get();

        $caisseIds = $caisses->pluck('id')->toArray();
        $mouvements = MouvementCaisse::with('caisse')
            ->whereIn('id_caisse', $caisseIds)
            ->whereBetween('date_mouvement', [$ddFull, $dfFull])
            ->orderBy('date_mouvement', 'desc')
            ->get();
        $entreesSpeciales = EntreeSpeciale::whereIn('id', $mouvements->where('source_type', 'entree_speciale')->pluck('source_id')->toArray())->get()->keyBy('id');
        $remboursementsSpeciaux = EntreeSpecialeRemboursement::with('entreeSpeciale')
            ->whereIn('id', $mouvements->where('source_type', 'remboursement_entree_speciale')->pluck('source_id')->toArray())
            ->get()
            ->keyBy('id');

        $this->values['title'] = 'Etat de ma caisse';
        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['caisses'] = $caisses;
        $this->values['mouvements'] = $mouvements;
        $this->values['entrees_speciales'] = $entreesSpeciales;
        $this->values['remboursements_speciaux'] = $remboursementsSpeciaux;
        $this->values['total_ventes'] = $mouvements->where('source_type', 'vente')->sum('montant');
        $this->values['total_achats'] = $mouvements->where('source_type', 'achat')->sum('montant');
        $this->values['total_entrees_speciales'] = $mouvements->where('source_type', 'entree_speciale')->sum('montant');
        $this->values['total_remboursements_speciaux'] = $mouvements->where('source_type', 'remboursement_entree_speciale')->sum('montant');
        $this->values['total_entrees'] = $mouvements->where('type', 'entree')->sum('montant');
        $this->values['total_sorties'] = $mouvements->where('type', 'sortie')->sum('montant');

        return view('admin.caisse_etat', $this->values);
    }

    public function transfert()
    {
        $cb = session('current_boutique');
        $this->values['caisses'] = Caisse::where('id_boutique', $cb->id)->where('active', 1)->orderBy('nom')->get();
        $this->values['title'] = 'Transfert de caisse';
        return view('admin.caisse_transfert', $this->values);
    }

    public function storeTransfert(Request $request)
    {
        $this->validate($request, [
            'caisse_source' => 'required|numeric',
            'caisse_destination' => 'required|numeric|different:caisse_source',
            'montant' => 'required|numeric|min:1',
            'date' => 'required|date',
        ]);

        $source = Caisse::find($request->input('caisse_source'));
        $destination = Caisse::find($request->input('caisse_destination'));
        $montant = floatval($request->input('montant'));
        $date = $request->input('date') . ' ' . date('H:i:s');

        if (!$source || !$destination) {
            return redirect()->to(\URL::previous())->withErrors(['caisse' => 'Caisse introuvable']);
        }

        if ($source->solde() < $montant) {
            return redirect()->to(\URL::previous())->withErrors(['solde' => 'Solde insuffisant dans la caisse source']);
        }

        MouvementCaisse::enregistrer($source->id, 'sortie', $montant, 'transfert', $destination->id, 'Transfert vers '.$destination->nom, $date);
        MouvementCaisse::enregistrer($destination->id, 'entree', $montant, 'transfert', $source->id, 'Transfert depuis '.$source->nom, $date);

        return redirect()->route('caisses_transfert')->with('success', true);
    }
}
