<?php

namespace GEICOM\Http\Controllers\stocks;

use GEICOM\Achat;
use GEICOM\Approvisionnement;
use GEICOM\Boutique;
use GEICOM\Caisse;
use GEICOM\Categorie;
use GEICOM\Client;
use GEICOM\Fournisseur;
use GEICOM\Livraison;
use GEICOM\Parametre;
use GEICOM\Pays;
use GEICOM\Produit;
use GEICOM\ProduitApprov;
use GEICOM\Usage;
use GEICOM\MouvementCaisse;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class StockController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->middleware('boutique');

        $this->values['big_title']='Gestion des stocks';

        $this->values['title']='Historique d\'un produit';
    }


    public function index()
    {

        $cb=session('current_boutique');
        $cbId=$cb->id;

        $c=Fournisseur::orderBy('nom')->get();
        $this->values['Fournisseurs']=$c;
        $c1=Categorie::all();
        $this->values['categories']=$c1;
        $m=Boutique::whereType(0)->orderBy('nom')->get();
        $this->values['magasins']=$m;


        $p=Produit::with(['stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        },'categorie','produit_lie.produit_c.stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->has('produit_lie')->orderBy('libelle')->get();
        $this->values['produits']=$p;

        array_forget($this->values,'title');

        return view('stocks.index')->with($this->values);
    }

    public function index1()
    {

        $cb=session('current_boutique');
        $cbId=$cb->id;

        $c=Fournisseur::orderBy('nom')->get();
        $this->values['Fournisseurs']=$c;
        $c1=Categorie::all();
        $this->values['categories']=$c1;
        $m=Boutique::whereType(0)->orderBy('nom')->get();
        $this->values['magasins']=$m;


        $p=Produit::with(['stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        },'categorie','produit_lie.produit_c.stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->has('produit_lie')->orderBy('libelle')->get();
        $this->values['produits']=$p;

        array_forget($this->values,'title');

        return view('stocks.index1')->with($this->values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function historique(Request $request){
        $this->validate($request,[
            "produit"=>"required",
        ]);

        $cb=session('current_boutique');
        $cbId=$cb->id;

        $produit=$request->input('produit');

        $p=Produit::with(['stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->find($produit);
        $u0=Usage::where('id_produit','=',$produit)->where('id_boutique','=',$cbId)->orderBy('date_utilisation')->orderBy('id')->get();
        $timeline=$this->buildUsageTimeline($u0);

        $u=Usage::where('id_produit','=',$produit)
            ->where('id_boutique','=',$cbId)
            ->orderBy('date_utilisation','desc')
            ->orderBy('id','desc')
            ->paginate(30);

        $u->getCollection()->transform(function ($usage) use ($timeline) {
            return $this->enrichUsageStock($usage, $timeline);
        });

        $qte=-99999999;
        $lqte=-99999999;
        $i=0;
        $fdate=date('Y-m-d');$ldate=date('Y-m-d');
        $ecart=0;
        $ecart_s=0;
        $nb_moy=0;
        $s_moy=0;
        $f_moy=0;
        foreach ($u0 as $usage){
            if($lqte<$usage->stock)
            {
                if($i==0){
                    $qte=$usage->stock;
                    $fdate=$usage->date_utilisation;

                    $lqte=$usage->stock;
                    $ldate=$usage->date_utilisation;
                }



                $ecart=date_diff((new \DateTime($fdate)),(new \DateTime($ldate)))->days;
                $ecart_s=$qte-$lqte;

                $fdate=$usage->date_utilisation;
                $qte=$usage->stock;
                if($ecart>0){
                    $nb_moy++;
                    $s_moy+=($ecart_s/$ecart);

                }




            }
            $ldate=$usage->date_utilisation;
            $lqte=$usage->stock;
            $i++;
        }
        $ecart=date_diff((new \DateTime($fdate)),(new \DateTime($ldate)))->days;
        $ecart_s=$qte-$lqte;
        if($ecart>0){
            $nb_moy++;

            $s_moy+=($ecart_s/$ecart);

        }
        if ($nb_moy>0)
            $f_moy=$s_moy/$nb_moy;

        $moy_jours=0;
        $moy_prod=0;

        if ($f_moy>=1)
        {
            $moy_jours=1;
            $moy_prod=round($f_moy,2);
        }else if ($f_moy>0){
            $moy_jours=round((1/$f_moy),2);
            $moy_prod=1;
        }


        $dernierMouvement=count($timeline) ? end($timeline) : null;
        $stockEnregistre=$p->stock ? $p->stock->quantite : 0;
        $stockCalcule=$dernierMouvement ? $dernierMouvement['stock_restant'] : $stockEnregistre;

        $this->values['moyenne_produit']=$moy_prod;
        $this->values['moyenne_jour']=$moy_jours;
        $this->values['stock_calcule']=$stockCalcule;
        $this->values['stock_enregistre']=$stockEnregistre;

        $this->values['usages']=$u;
        $this->values['produit']=$p;
        $this->values['title']='Historique d\'un produit';



        return view('stocks.historique',$this->values);
    }

    public function synchroniser_historique(Request $request)
    {
        $this->validate($request,[
            "produit"=>"required",
        ]);

        $cb=session('current_boutique');
        $cbId=$cb->id;
        $produit=$request->input('produit');

        $p=Produit::with(['stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->find($produit);

        if (!$p || !$p->stock) {
            return redirect()->to(\URL::previous())->withErrors(['stock'=>'Stock introuvable pour ce produit']);
        }

        $usages=Usage::where('id_produit','=',$produit)
            ->where('id_boutique','=',$cbId)
            ->orderBy('date_utilisation')
            ->orderBy('id')
            ->get();
        $timeline=$this->buildUsageTimeline($usages);

        if (!count($timeline)) {
            return redirect()->to(\URL::previous())->withErrors(['stock'=>'Aucun historique disponible pour synchroniser ce stock']);
        }

        $dernierMouvement=end($timeline);
        $p->stock->quantite=$dernierMouvement['stock_restant'];
        $p->stock->save();

        return redirect()->route('view_historique', ['produit'=>$produit])->withSuccess(['ok'=>'Stock synchronisé avec l historique']);
    }

    public function historique_general(Request $request)
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $produit=$request->input('produit', 0);
        $dateDebut=$request->input('date_debut', date('Y-m-01'));
        $dateFin=$request->input('date_fin', date('Y-m-d'));
        $dateDebutSql=$dateDebut.' 00:00:00';
        $dateFinSql=$dateFin.' 23:59:59';

        $produits=Produit::with(['categorie', 'stock'=>function ($query) use($cbId) {
            $query->where('id_boutique', $cbId);
        }])->orderBy('libelle')->get();
        $produitsMap=$produits->keyBy('id');

        $query=Usage::where('id_boutique', $cbId)
            ->where('date_utilisation', '<=', $dateFinSql)
            ->orderBy('id_produit')
            ->orderBy('date_utilisation')
            ->orderBy('id');

        if ($produit != 0) {
            $query->where('id_produit', $produit);
        }

        $usages=$query->get();
        $lignes=[];

        foreach ($usages->groupBy('id_produit') as $idProduit => $usagesProduit) {
            $timeline=$this->buildUsageTimeline($usagesProduit);

            foreach ($usagesProduit as $usage) {
                if ($usage->date_utilisation < $dateDebutSql || $usage->date_utilisation > $dateFinSql) {
                    continue;
                }

                $usage=$this->enrichUsageStock($usage, $timeline);
                $usage->produit_info=$produitsMap->has($idProduit) ? $produitsMap->get($idProduit) : null;
                $lignes[]=$usage;
            }
        }

        usort($lignes, function ($a, $b) {
            if ($a->date_utilisation == $b->date_utilisation) {
                return $b->id - $a->id;
            }
            return strcmp($b->date_utilisation, $a->date_utilisation);
        });

        $page=(int)$request->input('page', 1);
        $perPage=100;
        $items=array_slice($lignes, ($page - 1) * $perPage, $perPage);
        $paginator=new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            count($lignes),
            $perPage,
            $page,
            [
                'path'=>route('historique_stocks_general'),
                'query'=>$request->except('page'),
            ]
        );

        $this->values['title']='Historique des stocks par periode';
        $this->values['produits']=$produits;
        $this->values['produit_id']=$produit;
        $this->values['date_debut']=$dateDebut;
        $this->values['date_fin']=$dateFin;
        $this->values['usages']=$paginator;

        return view('stocks.historique_general', $this->values);
    }

    private function buildUsageTimeline($usages)
    {
        $timeline=[];
        $stockCourant=null;

        foreach ($usages as $usage) {
            $sens=$this->usageSens($usage->details);
            $quantite=abs((float)$usage->quantite);
            $variation=$sens === 'sortie' ? -$quantite : $quantite;
            $stockEnregistre=(float)$usage->stock;
            $stockAvant=$stockCourant === null ? $stockEnregistre - $variation : $stockCourant;
            $stockRestant=$stockAvant + $variation;

            $timeline[$usage->id]=[
                'sens'=>$sens,
                'stock_avant'=>$stockAvant,
                'stock_restant'=>$stockRestant,
                'stock_enregistre'=>$stockEnregistre,
                'stock_ecart'=>abs($stockRestant - $stockEnregistre) > 0.0001,
                'is_inventaire'=>$this->isInventaireUsage($usage->details),
            ];

            $stockCourant=$stockRestant;
        }

        return $timeline;
    }

    private function enrichUsageStock($usage, $timeline=[])
    {
        if (isset($timeline[$usage->id])) {
            $usage->sens=$timeline[$usage->id]['sens'];
            $usage->stock_avant=$timeline[$usage->id]['stock_avant'];
            $usage->stock_restant=$timeline[$usage->id]['stock_restant'];
            $usage->stock_enregistre=$timeline[$usage->id]['stock_enregistre'];
            $usage->stock_ecart=$timeline[$usage->id]['stock_ecart'];
            $usage->is_inventaire=$timeline[$usage->id]['is_inventaire'];

            return $usage;
        }

        $sens=$this->usageSens($usage->details);
        $quantite=abs((float)$usage->quantite);
        $stockRestant=(float)$usage->stock;
        $usage->sens=$sens;
        $usage->stock_restant=$stockRestant;
        $usage->stock_avant=$sens === 'sortie' ? $stockRestant + $quantite : $stockRestant - $quantite;
        $usage->stock_enregistre=$stockRestant;
        $usage->stock_ecart=false;
        $usage->is_inventaire=$this->isInventaireUsage($usage->details);

        return $usage;
    }

    private function usageSens($details)
    {
        $details=strtolower((string)$details);

        if (strpos($details, 'vente') !== false
            || strpos($details, 'vendu') !== false
            || strpos($details, 'vendue') !== false
            || strpos($details, 'retir') !== false
            || strpos($details, 'ecart = -') !== false
            || strpos($details, 'ecart=-') !== false
            || strpos($details, 'change') !== false
            || strpos($details, 'chang') !== false
            || strpos($details, 'approvisionnement de ') !== false) {
            return 'sortie';
        }

        return 'entree';
    }

    private function isInventaireUsage($details)
    {
        $details=strtolower((string)$details);
        return strpos($details, 'inventaire') !== false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->values['title']='Achat de produits';
        $c=Categorie::all();
        $this->values['categories']=$c;
        $p=Pays::orderBy('nom')->get();
        $this->values['pays']=$p;


        $c=Fournisseur::orderBy('nom')->get();
        $tva=Parametre::where('nom','=','tva')->first()->valeur;
        $tva_a=Parametre::where('nom','=','tva_achat')->first();

        $this->values['tva']=$tva;
        $this->values['tva_achat']=$tva_a;


        $this->values['fournisseurs']=$c;
        $this->values['caisses_sortie']=Caisse::where('type', Caisse::TYPE_SORTIE)
            ->where('active', 1)
            ->whereHas('users', function ($query) {
                $query->where('users.id', \Auth::user()->id);
            })
            ->orderBy('nom')
            ->get();

        return view('stocks.new_achat',$this->values);

    }

    public function create_approv(Request $request)
    {
        $magasin=$request->input('magasin');
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->values['title']='Achat de produits';


        $tva=Parametre::where('nom','=','tva')->first()->valeur;
        $tva_a=Parametre::where('nom','=','tva_achat')->first();
        $this->values['magasin']=Boutique::find($magasin);

        $this->values['tva']=$tva;
        $this->values['tva_achat']=$tva_a;



        return view('stocks.new_approv',$this->values);

    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'fournisseur'=>'required',
            'id'=>'required',
            'date'=>'date|required',
            'id_caisse'=>'required|numeric'

        ]);
        $id_list=$request->input('id');
        $quantite_list=$request->input('quantite');
        $reduction_list=$request->input('reduction');
        $reduction_gen=$request->input('reduction_generale');
        $fournisseur=$request->input('fournisseur');
        $date=$request->input('date');
        $idCaisse=$request->input('id_caisse');
        $caisse=Caisse::where('id', $idCaisse)->where('type', Caisse::TYPE_SORTIE)->first();
        if(!$caisse) {
            return redirect()->to(\URL::previous())->withErrors(['caisse'=>'Veuillez choisir une caisse de sortie valide']);
        }

        $cb=session('current_boutique');
        $cbId=$cb->id;

        if($id_list) {
            $f = new Livraison();
            $f->date_approv = $date;
            $f->id_fournisseur = $fournisseur;
            $f->id_boutique=$cbId;
            $f->id_user=\Auth::user()->id;
            $f->id_caisse=$idCaisse;
            $f->save();
            $f->numero = 'LI' . sprintf('%08d', $f->id);
            $i = 0;$total=0;
            $p = Produit::with(['stock'=>function ($query) use($cbId) {
                $query->where('id_boutique',$cbId);
            }])->whereIn('id', $id_list)->get();

            $totalPrevisionnel = 0;
            foreach ($id_list as $index => $id) {
                $prodPrevision = $p->where('id', '=', $id)->first();
                $redPrevision = isset($reduction_list[$index]) ? $reduction_list[$index] : null;
                if ($prodPrevision) {
                    if ($redPrevision) {
                        $totalPrevisionnel += ($prodPrevision->prix_achat - $redPrevision) * $quantite_list[$index];
                    } else {
                        $totalPrevisionnel += $prodPrevision->prix_achat * $quantite_list[$index];
                    }
                }
            }
            $tvaPrevision = 0;
            $tvaAchatPrevision = Parametre::where('nom','=','tva_achat')->first();
            if ($tvaAchatPrevision && $tvaAchatPrevision->valeur == 1) {
                $tvaPrevision = Parametre::where('nom','=','tva')->first()->valeur;
            }
            if ($reduction_gen) {
                $totalPrevisionnel = $totalPrevisionnel - ($totalPrevisionnel * $reduction_gen / 100);
            }
            $totalPrevisionnel = $totalPrevisionnel + ($totalPrevisionnel * ($tvaPrevision) / 100);
            if ($caisse->solde() < $totalPrevisionnel) {
                $f->delete();
                return redirect()->to(\URL::previous())->withErrors(['solde'=>'Solde insuffisant dans la caisse de sortie']);
            }

            foreach ($id_list as $id) {
                $prod = $p->where('id', '=', $id)->first();

                $v = new Achat();
                $v->id_livraison = $f->id;
                $v->id_produit = $prod->id;
                $v->id_boutique=$cbId;
                $v->date_achat=$date;

                    $v->quantite = $quantite_list[$i];
                    $prod->stock->quantite+=$quantite_list[$i];

                $prod->stock->save();
                $v->prix = $prod->prix_achat;
                $red=null;
                if($reduction_list[$i])
                {
                    $red=$reduction_list[$i];

                }

                if ($red) {
                    //$v->reduction = $red;
                    //$v->total = ($prod->prix * $quantite_list[$i]) - (($prod->prix * $quantite_list[$i]) * $red / 100);
                    $v->total = ($prod->prix_achat -$red)* $quantite_list[$i] ;

                } else {
                    $v->total = ($prod->prix_achat * $quantite_list[$i]);
                }

                $total+=$v->total;

                $v->save();
                $u=new Usage();
                $u->id_produit=$prod->id;
                $u->id_boutique=$cbId;
                $u->details="Achat: Quantité acheté = ".$quantite_list[$i];
                $u->date_utilisation=$date.' '.date('H:i:s');
                $u->stock=$prod->stock->quantite;
                $u->quantite=$quantite_list[$i];
                $u->save();
                $i++;
            }

            //TVA statique
            $tva=0;
            $tva_a=Parametre::where('nom','=','tva_achat')->first();
            if($tva_a)
                if ($tva_a->valeur==1)
                    $tva=Parametre::where('nom','=','tva')->first()->valeur;

            if($reduction_gen){
                $total=$total-($total*$reduction_gen/100);
             //   $f->reduction=$reduction_gen;
            }
            $f->tva=$tva;
            $f->total=$total+($total*($tva)/100);
            $f->save();
            MouvementCaisse::enregistrer($idCaisse, 'sortie', $f->total, 'achat', $f->id, 'Achat '.$f->numero, $date.' '.date('H:i:s'));

            return redirect()->route('nouvel_achat');


        }
    }


    public function store_approv(Request $request)
    {
        $this->validate($request,[
            'magasin'=>'required',
            'id'=>'required',
            'date'=>'date|required'

        ]);
        $id_list=$request->input('id');
        $quantite_list=$request->input('quantite');
        $reduction_list=$request->input('reduction');
        $reduction_gen=$request->input('reduction_generale');
        $magasin=$request->input('magasin');
        $date=$request->input('date');

        $cb=session('current_boutique');
        $cbId=$cb->id;

        if($id_list) {
            $f = new Approvisionnement();
            $f->date_approv = $date;
            $f->id_magasin = $magasin;
            $f->id_boutique=$cbId;
            $f->id_user=\Auth::user()->id;

            $f->save();
            $f->numero = 'APP' . sprintf('%08d', $f->id);
            $i = 0;$total=0;
            $p = Produit::with(['stock'=>function ($query) use($magasin) {
                $query->where('id_boutique',$magasin);
            }])->whereIn('id', $id_list)->get();
            $p2 = Produit::with(['stock'=>function ($query) use($cbId) {
                $query->where('id_boutique',$cbId);
            }])->whereIn('id', $id_list)->get();
            foreach ($id_list as $id) {
                $prod = $p->where('id', '=', $id)->first();
                $prod2 = $p2->where('id', '=', $id)->first();

                $v = new ProduitApprov();
                $v->id_approvisionnement = $f->id;
                $v->id_produit = $prod->id;
                $v->id_boutique=$cbId;
                $v->date_approv=$date;

                $v->quantite = $quantite_list[$i];

                if($prod->stock->quantite>=$quantite_list[$i])
                {
                    $v->quantite = $quantite_list[$i];
                    $prod->stock->quantite-=$quantite_list[$i];
                    $prod2->stock->quantite+=$quantite_list[$i];
                }else
                {
                    $f->delete();
                    return redirect()->to(\URL::previous())->withErrors(['qi'=>'Quantité de '.$prod->libelle.' insuffisante dans  '.Boutique::find($magasin)->nom]);
                    // $v->quantite =  $prod->stock->quantite;
                    //$prod->stock->quantite=0;
                }

                $prod->stock->save();
                $prod2->stock->save();
                $v->prix = $prod->prix_achat;
                $red=null;
                if($reduction_list[$i])
                {
                    $red=$reduction_list[$i];

                }

                if ($red) {
                    //$v->reduction = $red;
                    //$v->total = ($prod->prix * $quantite_list[$i]) - (($prod->prix * $quantite_list[$i]) * $red / 100);
                    $v->total = ($prod->prix_achat -$red)* $quantite_list[$i] ;

                } else {
                    $v->total = ($prod->prix_achat * $quantite_list[$i]);
                }

                $total+=$v->total;

                $v->save();
                $u=new Usage();
                $u->id_produit=$prod->id;
                $u->id_boutique=$cbId;
                $u->details="Approvisionnement depuis ".Boutique::find($magasin)->nom.": Quantité  = ".$quantite_list[$i];
                $u->date_utilisation=$date.' '.date('H:i:s');
                $u->stock=$prod2->stock->quantite;
                $u->quantite=$quantite_list[$i];
                $u->save();

                $u=new Usage();
                $u->id_produit=$prod->id;
                $u->id_boutique=$magasin;
                $u->details="Approvisionnement de ".$cb->nom.": Quantité livrée = ".$quantite_list[$i];
                $u->date_utilisation=$date.' '.date('H:i:s');
                $u->stock=$prod->stock->quantite;
                $u->quantite=$quantite_list[$i];
                $u->save();
                $i++;
            }

            //TVA statique
            $tva=0;
            $tva_a=Parametre::where('nom','=','tva_achat')->first();
            if($tva_a)
                if ($tva_a->valeur==1)
                    $tva=Parametre::where('nom','=','tva')->first()->valeur;

            if($reduction_gen){
                $total=$total-($total*$reduction_gen/100);
                //   $f->reduction=$reduction_gen;
            }
            $f->tva=$tva;
            $f->total=$total+($total*($tva)/100);
            $f->save();

            return redirect()->route('stocks');


        }
    }

    public function liste_produits(Request $request){
        $mode=$request->input('mode');
        if($mode==0){
            $item=Produit::with('categorie')->orderBy('libelle')->get();
            $view='liste_produits_by_nom';
        }else{
            $item=Categorie::with(['produits'=>function($q){
                $q->orderBy('libelle');
            }])->orderBy('libelle')->get();
            $view='liste_produits_by_cat';
        }


        $this->values['title']='Liste des produits';

        $this->values['items']=$item;
        return view('stocks.'.$view,$this->values);
    }





    public function liste_achats(Request $request)
    {

        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->validate($request,[
            'fournisseur'=>'required',
        ]);

        $dd=$request->input('dd');
        $df=$request->input('df');
        $fournisseur=$request->input('fournisseur');

        if(!$dd)
            $dd='1970-01-01';
        if(!$df)
            $df=date('Y-m-d 23:59:59');

        $this->values['title']='Récapitulatifs des achats';

        if($fournisseur==0)

        $f=Livraison::with(['fournisseur','utilisateur'])->where('id_boutique','=',$cbId)->where('date_approv','>=',$dd)->where('date_approv','<=',$df)->orderBy('date_approv','desc')->orderBy('created_at','desc')->get();

        else{
            $f=Livraison::with(['fournisseur','utilisateur'])->where('id_boutique','=',$cbId)->where('date_approv','>=',$dd)->where('date_approv','<=',$df)->where('id_fournisseur','=',$fournisseur)->orderBy('date_approv','desc')->orderBy('created_at','desc')->get();

            $this->values['fournisseur']=Fournisseur::find($fournisseur);

        }
        $p=Parametre::all();

        $this->values['param']=$p;

        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['livraisons']=$f;
        return view('stocks.liste_achats',$this->values);

    }

    public function liste_approvs(Request $request)
    {

        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->validate($request,[
            'magasin'=>'required',
        ]);

        $dd=$request->input('dd');
        $df=$request->input('df');
        $magasin=$request->input('magasin');

        if(!$dd)
            $dd='1970-01-01';
        if(!$df)
            $df=date('Y-m-d 23:59:59');

        $this->values['title']='Récapitulatifs des approvisionnements';

        if($magasin==0)

            $f=Approvisionnement::with(['magasin','utilisateur'])->where('id_boutique','=',$cbId)->where('date_approv','>=',$dd)->where('date_approv','<=',$df)->orderBy('date_approv','desc')->orderBy('created_at','desc')->get();

        else{
            $f=Approvisionnement::with(['magasin','utilisateur'])->where('id_boutique','=',$cbId)->where('date_approv','>=',$dd)->where('date_approv','<=',$df)->where('id_magasin','=',$magasin)->orderBy('date_approv','desc')->orderBy('created_at','desc')->get();

            $this->values['magasin']=Boutique::find($magasin);

        }

        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['approvisionnements']=$f;
        return view('stocks.liste_approvs',$this->values);

    }

    public function details_achats($id)
    {
        $this->values['title']='Details des achats';

        $f=Livraison::with(['achats.produit','fournisseur','utilisateur'])->find($id);

        $this->values['livraison']=$f;
        return view('stocks.details_achats',$this->values);

    }
 public function details_approvs($id)
    {
        $this->values['title']='Details des approvisionnements';

        $f=Approvisionnement::with(['items.produit','magasin','utilisateur'])->find($id);

        $this->values['approvisionnement']=$f;
        return view('stocks.details_approvs',$this->values);

    }

    public function view_stock()
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;

       /* $p=Produit::with(['categorie','stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->orderBy('libelle')->get();
        $values['produits']=$p;*/
        $values['title']='Verifier les Stocks';

        return view('stocks.stock_produits',$values);
    }

    public function get_stock_ajax()
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;

        $p=Produit::with(['categorie','stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->orderBy('libelle')->get();
        $this->values['produits']=$p;
       // $values['title']='Verifier les Stocks';

        return response()->json($this->values);
    }


    public function detailler_produit(Request $request)
    {

        $cb=session('current_boutique');
        $cbId=$cb->id;

        $this->validate($request,[
           'produit'=>'required',
            'quantite'=>'required|numeric'
        ]);
        $produit=$request->input('produit');
        $quantite=$request->input('quantite');

        $p=Produit::with(['produit_lie.produit_c.stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        },'stock'=>function ($query) use($cbId) {
            $query->where('id_boutique',$cbId);
        }])->find($produit);

        if ($p->stock->quantite < $quantite)
            return redirect()->to(\URL::previous())->withErrors(['no_stock'=>'Le stock du produit est insuffisant']);

        $p->stock->quantite-=$quantite;
        $p->stock->save();
        $u1=new Usage();
        $u1->id_produit=$p->id;
        $u1->id_boutique=$cbId;
        $u1->date_utilisation=date('Y-m-d H:i:s');
        $u1->quantite=$quantite;
        $u1->stock=$p->stock->quantite;
        $u1->details='Changé en '.$p->produit_lie->produit_c->libelle;
        $u1->save();

        $p->produit_lie->produit_c->stock->quantite+=$quantite*$p->produit_lie->quantite;
        $p->produit_lie->produit_c->stock->save();
        $u1=new Usage();
        $u1->id_produit=$p->produit_lie->produit_c->id;
        $u1->id_boutique=$cbId;
        $u1->date_utilisation=date('Y-m-d H:i:s');
        $u1->quantite=$quantite*$p->produit_lie->quantite;
        $u1->stock=$p->produit_lie->produit_c->stock->quantite;
        $u1->details='Provenant  de '.$p->libelle;
        $u1->save();

        return redirect()->to(\URL::previous());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
