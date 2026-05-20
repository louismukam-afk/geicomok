<?php

namespace GEICOM\Http\Controllers\ventes;

use GEICOM\BonCredit;
use GEICOM\BonCreditEcheance;
use GEICOM\BonCreditRemboursement;
use GEICOM\Caisse;
use GEICOM\Client;
use GEICOM\Facture;
use GEICOM\MouvementCaisse;
use GEICOM\Parametre;
use GEICOM\Produit;
use GEICOM\Usage;
use GEICOM\Vente;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class BonCreditController extends Controller
{
    protected $values=[];

    public function __construct()
    {
        $this->middleware('boutique');
        $this->values['big_title']='Gestion des ventes';
    }

    public function index()
    {
        $cb=session('current_boutique');
        $this->values['title']='Bons de credit';
        $this->values['clients']=Client::orderBy('nom')->get();
        $this->values['bons']=BonCredit::with('client')
            ->where('id_boutique', $cb->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('ventes.credit.index', $this->values);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'client'=>'required|numeric',
            'montant_credit'=>'required|numeric|min:1',
            'date_debut'=>'required|date',
            'date_fin'=>'required|date',
        ]);

        if ($request->input('date_fin') < $request->input('date_debut')) {
            return redirect()->to(\URL::previous())->withErrors(['date_fin'=>'La date de fin doit etre superieure ou egale a la date de debut'])->withInput();
        }

        if ($request->input('date_debut_remboursement') && $request->input('date_fin_remboursement') && $request->input('date_fin_remboursement') < $request->input('date_debut_remboursement')) {
            return redirect()->to(\URL::previous())->withErrors(['date_fin_remboursement'=>'La fin du remboursement doit etre superieure ou egale au debut'])->withInput();
        }

        $cb=session('current_boutique');
        $bon=new BonCredit();
        $bon->id_client=$request->input('client');
        $bon->id_boutique=$cb->id;
        $bon->id_user=\Auth::user()->id;
        $bon->montant_credit=$request->input('montant_credit');
        $bon->date_debut=$request->input('date_debut');
        $bon->date_fin=$request->input('date_fin');
        $bon->date_debut_remboursement=$request->input('date_debut_remboursement');
        $bon->date_fin_remboursement=$request->input('date_fin_remboursement');
        $bon->observations=$request->input('observations');
        $bon->statut=BonCredit::STATUT_ACTIF;
        $bon->save();
        $bon->numero='BC'.sprintf('%08d', $bon->id);
        $bon->save();

        return redirect()->route('bons_credit_show', $bon->id);
    }

    public function show($id)
    {
        $bon=$this->findBon($id);
        $this->values['title']='Bon de credit '.$bon->numero;
        $this->values['bon']=$bon;
        $this->values['clients']=Client::orderBy('nom')->get();
        $this->values['statuts']=BonCredit::statuts();
        $this->values['tva']=Parametre::where('nom','=','tva')->first()->valeur;
        $this->values['caisses_entree']=Caisse::where('type', Caisse::TYPE_ENTREE)
            ->where('active', 1)
            ->whereHas('users', function ($query) {
                $query->where('users.id', \Auth::user()->id);
            })
            ->orderBy('nom')
            ->get();

        return view('ventes.credit.show', $this->values);
    }

    public function printFactures($id)
    {
        $bon=$this->findBon($id);
        $this->values['title']='Factures du bon de credit '.$bon->numero;
        $this->values['bon']=$bon;
        $this->values['boutique']=session('current_boutique');
        $this->values['param']=Parametre::all();

        return view('ventes.credit.factures_print', $this->values);
    }

    public function update(Request $request, $id)
    {
        $bon=$this->findBon($id);
        $this->validate($request,[
            'client'=>'required|numeric',
            'montant_credit'=>'required|numeric|min:1',
            'date_debut'=>'required|date',
            'date_fin'=>'required|date',
        ]);

        if ($request->input('date_fin') < $request->input('date_debut')) {
            return redirect()->to(\URL::previous())->withErrors(['date_fin'=>'La date de fin doit etre superieure ou egale a la date de debut'])->withInput();
        }

        if ($request->input('date_debut_remboursement') && $request->input('date_fin_remboursement') && $request->input('date_fin_remboursement') < $request->input('date_debut_remboursement')) {
            return redirect()->to(\URL::previous())->withErrors(['date_fin_remboursement'=>'La fin du remboursement doit etre superieure ou egale au debut'])->withInput();
        }

        $montantConsomme=$bon->montantConsomme();
        if ((float)$request->input('montant_credit') < $montantConsomme) {
            return redirect()->to(\URL::previous())->withErrors(['montant_credit'=>'Le montant credit ne peut pas etre inferieur au montant deja consomme'])->withInput();
        }

        foreach ($bon->factures as $facture) {
            if ($facture->date_vente < $request->input('date_debut') || $facture->date_vente > $request->input('date_fin')) {
                return redirect()->to(\URL::previous())->withErrors(['date_debut'=>'La nouvelle periode doit contenir les ventes deja effectuees sur ce bon'])->withInput();
            }
        }

        $statuts=array_keys(BonCredit::statuts());
        if (!in_array($request->input('statut'), $statuts)) {
            return redirect()->to(\URL::previous())->withErrors(['statut'=>'Statut invalide'])->withInput();
        }

        $bon->id_client=$request->input('client');
        $bon->montant_credit=$request->input('montant_credit');
        $bon->date_debut=$request->input('date_debut');
        $bon->date_fin=$request->input('date_fin');
        $bon->date_debut_remboursement=$request->input('date_debut_remboursement');
        $bon->date_fin_remboursement=$request->input('date_fin_remboursement');
        $bon->observations=$request->input('observations');
        $bon->statut=$request->input('statut');
        $bon->save();

        return redirect()->route('bons_credit_show', $bon->id);
    }

    public function destroy($id)
    {
        $bon=$this->findBon($id);

        if ($bon->factures->count() > 0 || $bon->remboursements->count() > 0) {
            return redirect()->to(\URL::previous())->withErrors(['delete'=>'Impossible de supprimer un bon deja utilise. Passez-le en inactif pour bloquer les ventes.']);
        }

        BonCreditEcheance::where('id_bon_credit', $bon->id)->delete();
        $bon->delete();

        return redirect()->route('bons_credit');
    }

    public function storeEcheance(Request $request, $id)
    {
        $bon=$this->findBon($id);
        $this->validate($request,[
            'date_echeance'=>'required|date',
            'montant'=>'required|numeric|min:1',
        ]);

        if (($bon->date_debut_remboursement && $request->input('date_echeance') < $bon->date_debut_remboursement)
            || ($bon->date_fin_remboursement && $request->input('date_echeance') > $bon->date_fin_remboursement)) {
            return redirect()->to(\URL::previous())->withErrors(['date_echeance'=>'La date echeance est hors periode de remboursement'])->withInput();
        }

        $echeance=new BonCreditEcheance();
        $echeance->id_bon_credit=$bon->id;
        $echeance->date_echeance=$request->input('date_echeance');
        $echeance->montant=$request->input('montant');
        $echeance->montant_paye=0;
        $echeance->statut='en_attente';
        $echeance->save();

        return redirect()->route('bons_credit_show', $bon->id);
    }

    public function storeRemboursement(Request $request, $id)
    {
        $bon=$this->findBon($id);
        $this->validate($request,[
            'date_paiement'=>'required|date',
            'montant'=>'required|numeric|min:1',
            'id_caisse'=>'required|numeric',
        ]);

        if (($bon->date_debut_remboursement && $request->input('date_paiement') < $bon->date_debut_remboursement)
            || ($bon->date_fin_remboursement && $request->input('date_paiement') > $bon->date_fin_remboursement)) {
            return redirect()->to(\URL::previous())->withErrors(['date_paiement'=>'La date paiement est hors periode de remboursement'])->withInput();
        }

        if ($request->input('montant') > $bon->resteARembourser()) {
            return redirect()->to(\URL::previous())->withErrors(['montant'=>'Le montant depasse le reste a rembourser'])->withInput();
        }

        $echeance=null;
        if ($request->input('id_echeance')) {
            $echeance=BonCreditEcheance::where('id_bon_credit', $bon->id)->find($request->input('id_echeance'));
        }

        $remboursement=new BonCreditRemboursement();
        $remboursement->id_bon_credit=$bon->id;
        $remboursement->id_echeance=$echeance ? $echeance->id : null;
        $remboursement->id_caisse=$request->input('id_caisse');
        $remboursement->id_user=\Auth::user()->id;
        $remboursement->date_paiement=$request->input('date_paiement');
        $remboursement->montant=$request->input('montant');
        $remboursement->observations=$request->input('observations');
        $remboursement->save();
        $remboursement->numero='RBC'.sprintf('%08d', $remboursement->id);
        $remboursement->save();

        if ($echeance) {
            $echeance->montant_paye += $remboursement->montant;
            $echeance->statut=$echeance->montant_paye >= $echeance->montant ? 'paye' : 'partiel';
            $echeance->save();
        }

        MouvementCaisse::enregistrer($remboursement->id_caisse, 'entree', $remboursement->montant, 'remboursement_credit', $remboursement->id, 'Remboursement '.$bon->numero, $remboursement->date_paiement.' '.date('H:i:s'));

        if ($bon->resteARembourser() <= 0) {
            $bon->statut=BonCredit::STATUT_CLOTURE;
            $bon->save();
        }

        return redirect()->route('bons_credit_show', $bon->id);
    }

    public function storeVente(Request $request, $id)
    {
        $bon=$this->findBon($id);
        $this->validate($request,[
            'id'=>'required|array',
            'date'=>'required|date',
        ]);

        if ($bon->statut != BonCredit::STATUT_ACTIF) {
            return redirect()->to(\URL::previous())->withErrors(['bon'=>'Ce bon de credit est inactif ou cloture. Les ventes ne sont possibles que sur un bon actif.']);
        }

        $date=$request->input('date');
        if ($date < $bon->date_debut || $date > $bon->date_fin) {
            return redirect()->to(\URL::previous())->withErrors(['date'=>'La date de vente est hors periode du bon de credit']);
        }

        $idList=$request->input('id');
        $quantites=$request->input('quantite');
        $prix=$request->input('prix_unitaire');
        $reductions=$request->input('reduction');
        $cb=session('current_boutique');

        $produits=Produit::with(['stock'=>function ($query) use($cb) {
            $query->where('id_boutique', $cb->id);
        }])->whereIn('id', $idList)->get();

        $total=0;
        foreach ($idList as $i=>$idProduit) {
            $produit=$produits->where('id', $idProduit)->first();
            if (!$produit || !$produit->stock || $produit->stock->quantite < $quantites[$i]) {
                $libelle=$produit ? $produit->libelle : 'produit introuvable';
                return redirect()->to(\URL::previous())->withErrors(['stock'=>'Stock insuffisant pour '.$libelle])->withInput();
            }

            $reduction=isset($reductions[$i]) ? (float)$reductions[$i] : 0;
            $total += (((float)$prix[$i]) - $reduction) * (float)$quantites[$i];
        }

        $tva=Parametre::where('nom','=','tva')->first()->valeur;
        $totalAvecTva=$total + ($total * $tva / 100);
        if ($totalAvecTva > $bon->soldeDisponible()) {
            return redirect()->to(\URL::previous())->withErrors(['credit'=>'Le montant de cette sortie depasse le solde disponible du bon'])->withInput();
        }

        $facture=new Facture();
        $facture->date_vente=$date;
        $facture->id_boutique=$cb->id;
        $facture->id_client=$bon->id_client;
        $facture->id_user=\Auth::user()->id;
        $facture->id_bon_credit=$bon->id;
        $facture->mode_vente='credit';
        $facture->paye=0;
        $facture->verse=0;
        $facture->tva=$tva;
        $facture->total=$totalAvecTva;
        $facture->save();
        $facture->numero='CR'.sprintf('%08d', $facture->id);
        $facture->save();

        foreach ($idList as $i=>$idProduit) {
            $produit=$produits->where('id', $idProduit)->first();
            $reduction=isset($reductions[$i]) ? (float)$reductions[$i] : 0;

            $vente=new Vente();
            $vente->id_facture=$facture->id;
            $vente->id_produit=$produit->id;
            $vente->date_vente=$date;
            $vente->id_boutique=$cb->id;
            $vente->prix_achat=$produit->prix_achat;
            $vente->prix_unitaire=$prix[$i];
            $vente->quantite=$quantites[$i];
            $vente->reduction=$reduction;
            $vente->total=(((float)$prix[$i]) - $reduction) * (float)$quantites[$i];
            $vente->save();

            $produit->stock->quantite -= $quantites[$i];
            $produit->stock->save();

            $usage=new Usage();
            $usage->id_produit=$produit->id;
            $usage->id_boutique=$cb->id;
            $usage->details='Vente a credit '.$bon->numero.': Quantite vendue = '.$quantites[$i];
            $usage->date_utilisation=$date.' '.date('H:i:s');
            $usage->stock=$produit->stock->quantite;
            $usage->quantite=$quantites[$i];
            $usage->save();
        }

        return redirect()->route('bons_credit_show', $bon->id);
    }

    private function findBon($id)
    {
        $cb=session('current_boutique');
        return BonCredit::with(['client', 'factures.ventes.produit', 'echeances', 'remboursements.caisse'])
            ->where('id_boutique', $cb->id)
            ->findOrFail($id);
    }

}
