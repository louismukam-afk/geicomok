<?php

namespace GEICOM\Http\Controllers\stocks;

use GEICOM\Categorie;
use GEICOM\Inventaire;
use GEICOM\InventaireLigne;
use GEICOM\Produit;
use GEICOM\Stock;
use GEICOM\Usage;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class InventaireController extends Controller
{
    protected $values=[];

    public function __construct()
    {
        $this->middleware('boutique');
        $this->values['big_title']='Gestion des stocks';
    }

    public function index(Request $request)
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->values['title']='Inventaires';
        $this->values['categories']=Categorie::orderBy('libelle')->get();
        $this->values['date_debut']=$request->input('date_debut', date('Y-m-01'));
        $this->values['date_fin']=$request->input('date_fin', date('Y-m-d'));
        $this->values['categorie_id']=$request->input('categorie', 0);
        $this->values['inventaires']=Inventaire::where('id_boutique', $cbId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('stocks.inventaires.index', $this->values);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'date_debut'=>'date|required',
            'date_fin'=>'date|required',
        ]);

        $cb=session('current_boutique');
        $cbId=$cb->id;
        $categorie=$request->input('categorie', 0);

        $inventaire=new Inventaire();
        $inventaire->id_boutique=$cbId;
        $inventaire->id_user=\Auth::user()->id;
        $inventaire->date_debut=$request->input('date_debut');
        $inventaire->date_fin=$request->input('date_fin');
        $inventaire->observations=$request->input('observations');
        $inventaire->statut=Inventaire::STATUT_BROUILLON;
        $inventaire->save();

        $queryProduits=Produit::with(['categorie', 'stock'=>function ($query) use($cbId) {
            $query->where('id_boutique', $cbId);
        }]);

        if ($categorie) {
            $queryProduits->where('id_categorie', $categorie);
        }

        $produits=$queryProduits->orderBy('id_categorie')
            ->orderBy('libelle')
            ->get();

        foreach ($produits as $produit) {
            $ligne=new InventaireLigne();
            $ligne->id_inventaire=$inventaire->id;
            $ligne->id_produit=$produit->id;
            $ligne->id_categorie=$produit->id_categorie;
            $ligne->quantite_theorique=$produit->stock ? $produit->stock->quantite : 0;
            $ligne->quantite_reelle=null;
            $ligne->ecart=0;
            $ligne->prix_achat=$produit->prix_achat;
            $ligne->valeur_ecart=0;
            $ligne->save();
        }

        return redirect()->route('inventaires_show', $inventaire->id);
    }

    public function show($id)
    {
        $inventaire=$this->findInventaire($id);
        $this->values['title']='Saisie inventaire';
        $this->values['inventaire']=$inventaire;
        $this->values['groupes']=$inventaire->lignes->groupBy('id_categorie');

        return view('stocks.inventaires.show', $this->values);
    }

    public function update(Request $request, $id)
    {
        $inventaire=$this->findInventaire($id);
        if ($inventaire->statut == Inventaire::STATUT_CONSOLIDE) {
            return redirect()->route('inventaires_show', $inventaire->id)->withErrors(['inventaire'=>'Cet inventaire est deja consolide']);
        }

        $this->validate($request,[
            'date_debut'=>'date|required',
            'date_fin'=>'date|required',
        ]);

        $inventaire->date_debut=$request->input('date_debut');
        $inventaire->date_fin=$request->input('date_fin');
        $inventaire->observations=$request->input('observations');
        $inventaire->save();

        $quantites=$request->input('quantite_reelle', []);
        foreach ($inventaire->lignes as $ligne) {
            if (!array_key_exists($ligne->id, $quantites) || $quantites[$ligne->id] === '') {
                continue;
            }

            $reelle=(float)$quantites[$ligne->id];
            $ligne->quantite_reelle=$reelle;
            $ligne->ecart=$reelle - $ligne->quantite_theorique;
            $ligne->valeur_ecart=$ligne->ecart * $ligne->prix_achat;
            $ligne->save();
        }

        return redirect()->route('inventaires_show', $inventaire->id)->withSuccess(['ok'=>'Quantites reelles enregistrees']);
    }

    public function consolider($id)
    {
        $inventaire=$this->findInventaire($id);
        if ($inventaire->statut == Inventaire::STATUT_CONSOLIDE) {
            return redirect()->route('inventaires_show', $inventaire->id)->withErrors(['inventaire'=>'Cet inventaire est deja consolide']);
        }

        \DB::transaction(function () use ($inventaire) {
            $dateConsolidation=date('Y-m-d H:i:s');

            foreach ($inventaire->lignes as $ligne) {
                if ($ligne->quantite_reelle === null) {
                    continue;
                }

                $stock=Stock::where('id_produit', $ligne->id_produit)
                    ->where('id_boutique', $inventaire->id_boutique)
                    ->first();

                if (!$stock) {
                    $stock=new Stock();
                    $stock->id_produit=$ligne->id_produit;
                    $stock->id_boutique=$inventaire->id_boutique;
                }

                $stock->quantite=$ligne->quantite_reelle;
                $stock->save();

                $ligne->stock_consolide=$stock->quantite;
                $ligne->save();

                $this->enregistrerUsageInventaire($inventaire, $ligne, $stock, $dateConsolidation);
            }

            $inventaire->statut=Inventaire::STATUT_CONSOLIDE;
            $inventaire->date_consolidation=$dateConsolidation;
            $inventaire->save();
        });

        return redirect()->route('inventaires_show', $inventaire->id)->withSuccess(['ok'=>'Inventaire consolide']);
    }

    private function enregistrerUsageInventaire($inventaire, $ligne, $stock, $dateConsolidation)
    {
        $existe=Usage::where('id_produit', $ligne->id_produit)
            ->where('id_boutique', $inventaire->id_boutique)
            ->where('details', 'like', 'Consolidation inventaire #'.$inventaire->id.'%')
            ->first();

        if ($existe) {
            return;
        }

        $usage=new Usage();
        $usage->id_produit=$ligne->id_produit;
        $usage->id_boutique=$inventaire->id_boutique;
        $usage->details='Consolidation inventaire #'.$inventaire->id.
            ' : stock theorique = '.$ligne->quantite_theorique.
            ', stock reel = '.$ligne->quantite_reelle.
            ', ecart = '.$ligne->ecart.
            ', nouveau stock utilise pour les ventes = '.$stock->quantite;
        $usage->date_utilisation=$dateConsolidation;
        $usage->stock=(int)round($stock->quantite);
        $usage->quantite=(int)round(abs($ligne->ecart));
        $usage->save();
    }

    public function rapport(Request $request)
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $dateDebut=$request->input('date_debut', date('Y-m-01'));
        $dateFin=$request->input('date_fin', date('Y-m-d'));

        $inventaires=Inventaire::with('lignes.produit.categorie')
            ->where('id_boutique', $cbId)
            ->where('date_debut', '<=', $dateFin)
            ->where('date_fin', '>=', $dateDebut)
            ->orderBy('date_debut')
            ->get();

        $this->values['title']='Rapport inventaire';
        $this->values['date_debut']=$dateDebut;
        $this->values['date_fin']=$dateFin;
        $this->values['inventaires']=$inventaires;

        return view('stocks.inventaires.rapport', $this->values);
    }

    private function findInventaire($id)
    {
        $cb=session('current_boutique');
        return Inventaire::with(['lignes.produit.categorie', 'lignes.categorie'])
            ->where('id_boutique', $cb->id)
            ->findOrFail($id);
    }
}
