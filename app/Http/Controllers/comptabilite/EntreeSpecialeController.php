<?php

namespace GEICOM\Http\Controllers\comptabilite;

use GEICOM\Caisse;
use GEICOM\EntreeSpeciale;
use GEICOM\EntreeSpecialeEcheance;
use GEICOM\EntreeSpecialeRemboursement;
use GEICOM\Http\Controllers\Controller;
use GEICOM\MouvementCaisse;
use Illuminate\Http\Request;

class EntreeSpecialeController extends Controller
{
    protected $values = [];

    public function __construct()
    {
        $this->values['big_title'] = 'Comptabilite';
        $this->values['title'] = 'Entrees speciales';
    }

    public function index()
    {
        $cb = session('current_boutique');
        $this->values['types'] = EntreeSpeciale::types();
        $this->values['caisses'] = Caisse::where('id_boutique', $cb->id)
            ->where('type', Caisse::TYPE_CENTRALE)
            ->where('active', 1)
            ->orderBy('nom')
            ->get();
        $this->values['entrees'] = EntreeSpeciale::with(['caisse', 'echeances'])
            ->where('id_boutique', $cb->id)
            ->orderBy('date_apport', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(30);

        return view('comptabilite.entrees_speciales.index', $this->values);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_caisse' => 'required|numeric',
            'type' => 'required',
            'montant' => 'required|numeric|min:1',
            'date_apport' => 'required|date',
        ]);

        $cb = session('current_boutique');
        $caisse = Caisse::where('id', $request->input('id_caisse'))
            ->where('id_boutique', $cb->id)
            ->where('type', Caisse::TYPE_CENTRALE)
            ->first();

        if (!$caisse) {
            return redirect()->to(\URL::previous())->withErrors(['id_caisse' => 'Veuillez choisir une caisse centrale valide'])->withInput();
        }

        if (!array_key_exists($request->input('type'), EntreeSpeciale::types())) {
            return redirect()->to(\URL::previous())->withErrors(['type' => 'Type d apport invalide'])->withInput();
        }

        if ($request->input('type') == EntreeSpeciale::TYPE_PRET) {
            $this->validate($request, [
                'source_telephone' => 'required',
                'date_debut_remboursement' => 'required|date',
                'date_fin_remboursement' => 'required|date',
                'nombre_echeances' => 'required|numeric|min:1',
                'echeance_dates' => 'required|array',
                'echeance_montants' => 'required|array',
            ]);

            if ($request->input('date_fin_remboursement') < $request->input('date_debut_remboursement')) {
                return redirect()->to(\URL::previous())->withErrors(['date_fin_remboursement' => 'La fin du remboursement doit etre superieure au debut'])->withInput();
            }

            $erreurEcheances = $this->validerEcheances($request);
            if ($erreurEcheances) {
                return redirect()->to(\URL::previous())->withErrors(['echeances' => $erreurEcheances])->withInput();
            }
        }

        $entree = new EntreeSpeciale();
        $entree->id_caisse = $caisse->id;
        $entree->id_boutique = $cb->id;
        $entree->id_user = \Auth::user()->id;
        $entree->type = $request->input('type');
        $entree->source_nom = $request->input('source_nom');
        $entree->source_telephone = $request->input('source_telephone');
        $entree->montant = $request->input('montant');
        $entree->date_apport = $request->input('date_apport');
        $entree->date_debut_remboursement = $request->input('date_debut_remboursement');
        $entree->date_fin_remboursement = $request->input('date_fin_remboursement');
        $entree->nombre_echeances = $request->input('nombre_echeances');
        $entree->observations = $request->input('observations');
        $entree->statut = EntreeSpeciale::STATUT_ACTIF;
        $entree->save();
        $entree->numero = 'ES'.sprintf('%08d', $entree->id);
        $entree->save();

        MouvementCaisse::enregistrer(
            $caisse->id,
            'entree',
            $entree->montant,
            'entree_speciale',
            $entree->id,
            'Entree speciale '.$entree->numero.' - '.EntreeSpeciale::types()[$entree->type],
            $entree->date_apport.' '.date('H:i:s')
        );

        if ($entree->type == EntreeSpeciale::TYPE_PRET) {
            $this->enregistrerEcheancesDepuisFormulaire($entree, $request);
        }

        return redirect()->route('entrees_speciales_show', $entree->id)->with('success', true);
    }

    public function show($id)
    {
        $entree = $this->findEntree($id);
        $cb = session('current_boutique');
        $this->values['title'] = 'Entree speciale '.$entree->numero;
        $this->values['types'] = EntreeSpeciale::types();
        $this->values['entree'] = $entree;
        $this->values['caisses'] = Caisse::where('id_boutique', $cb->id)
            ->where('type', Caisse::TYPE_CENTRALE)
            ->where('active', 1)
            ->orderBy('nom')
            ->get();

        return view('comptabilite.entrees_speciales.show', $this->values);
    }

    public function storeRemboursement(Request $request, $id)
    {
        $entree = $this->findEntree($id);
        $this->validate($request, [
            'date_remboursement' => 'required|date',
            'montant' => 'required|numeric|min:1',
            'id_caisse' => 'required|numeric',
        ]);

        if ($entree->type != EntreeSpeciale::TYPE_PRET) {
            return redirect()->to(\URL::previous())->withErrors(['type' => 'Seuls les prets peuvent etre rembourses']);
        }

        if ($request->input('date_remboursement') < $entree->date_debut_remboursement || $request->input('date_remboursement') > $entree->date_fin_remboursement) {
            return redirect()->to(\URL::previous())->withErrors(['date_remboursement' => 'La date est hors periode de remboursement'])->withInput();
        }

        if ($request->input('montant') > $entree->resteARembourser()) {
            return redirect()->to(\URL::previous())->withErrors(['montant' => 'Le montant depasse le reste a rembourser'])->withInput();
        }

        $cb = session('current_boutique');
        $caisse = Caisse::where('id', $request->input('id_caisse'))
            ->where('id_boutique', $cb->id)
            ->where('type', Caisse::TYPE_CENTRALE)
            ->first();

        if (!$caisse) {
            return redirect()->to(\URL::previous())->withErrors(['id_caisse' => 'Veuillez choisir une caisse centrale valide'])->withInput();
        }

        if ($caisse->solde() < $request->input('montant')) {
            return redirect()->to(\URL::previous())->withErrors(['montant' => 'Solde insuffisant dans la caisse centrale'])->withInput();
        }

        $echeance = null;
        if ($request->input('id_echeance')) {
            $echeance = EntreeSpecialeEcheance::where('id_entree_speciale', $entree->id)->find($request->input('id_echeance'));
        }

        $remboursement = new EntreeSpecialeRemboursement();
        $remboursement->id_entree_speciale = $entree->id;
        $remboursement->id_echeance = $echeance ? $echeance->id : null;
        $remboursement->id_caisse = $caisse->id;
        $remboursement->id_user = \Auth::user()->id;
        $remboursement->date_remboursement = $request->input('date_remboursement');
        $remboursement->montant = $request->input('montant');
        $remboursement->observations = $request->input('observations');
        $remboursement->save();
        $remboursement->numero = 'RES'.sprintf('%08d', $remboursement->id);
        $remboursement->save();

        if ($echeance) {
            $echeance->montant_paye += $remboursement->montant;
            $echeance->statut = $echeance->montant_paye >= $echeance->montant ? 'paye' : 'partiel';
            $echeance->save();
        }

        MouvementCaisse::enregistrer(
            $caisse->id,
            'sortie',
            $remboursement->montant,
            'remboursement_entree_speciale',
            $remboursement->id,
            'Remboursement pret '.$entree->numero,
            $remboursement->date_remboursement.' '.date('H:i:s')
        );

        if ($entree->resteARembourser() <= 0) {
            $entree->statut = EntreeSpeciale::STATUT_REMBOURSE;
            $entree->save();
        }

        return redirect()->route('entrees_speciales_show', $entree->id)->with('success', true);
    }

    public function rappels()
    {
        $cb = session('current_boutique');
        $today = date('Y-m-d');
        $limit = date('Y-m-d', strtotime('+30 days'));

        $this->values['title'] = 'Rappels echeances de remboursement';
        $this->values['echeances'] = EntreeSpecialeEcheance::with('entreeSpeciale.caisse')
            ->whereHas('entreeSpeciale', function ($query) use ($cb) {
                $query->where('id_boutique', $cb->id)->where('type', EntreeSpeciale::TYPE_PRET);
            })
            ->where('statut', '<>', 'paye')
            ->whereBetween('date_echeance', [$today, $limit])
            ->orderBy('date_echeance')
            ->get();

        return view('comptabilite.entrees_speciales.rappels', $this->values);
    }

    private function findEntree($id)
    {
        $cb = session('current_boutique');
        return EntreeSpeciale::with(['caisse', 'echeances', 'remboursements.caisse'])
            ->where('id_boutique', $cb->id)
            ->findOrFail($id);
    }

    private function validerEcheances(Request $request)
    {
        $nombre = (int)$request->input('nombre_echeances');
        $dates = $request->input('echeance_dates', []);
        $montants = $request->input('echeance_montants', []);

        if (count($dates) != $nombre || count($montants) != $nombre) {
            return 'Veuillez renseigner exactement '.$nombre.' echeance(s).';
        }

        $total = 0;
        for ($i = 0; $i < $nombre; $i++) {
            if (!$dates[$i] || !$montants[$i]) {
                return 'Toutes les dates et tous les montants des echeances sont obligatoires.';
            }

            if ($dates[$i] < $request->input('date_debut_remboursement') || $dates[$i] > $request->input('date_fin_remboursement')) {
                return 'Chaque date echeance doit etre dans la periode de remboursement.';
            }

            if ((float)$montants[$i] <= 0) {
                return 'Chaque montant echeance doit etre superieur a zero.';
            }

            $total += (float)$montants[$i];
        }

        if (round($total, 2) != round((float)$request->input('montant'), 2)) {
            return 'La somme des echeances doit etre egale au montant du pret.';
        }

        return null;
    }

    private function enregistrerEcheancesDepuisFormulaire(EntreeSpeciale $entree, Request $request)
    {
        $dates = $request->input('echeance_dates', []);
        $montants = $request->input('echeance_montants', []);

        for ($i = 0; $i < count($dates); $i++) {
            $echeance = new EntreeSpecialeEcheance();
            $echeance->id_entree_speciale = $entree->id;
            $echeance->date_echeance = $dates[$i];
            $echeance->montant = $montants[$i];
            $echeance->montant_paye = 0;
            $echeance->statut = 'en_attente';
            $echeance->save();
        }
    }
}
