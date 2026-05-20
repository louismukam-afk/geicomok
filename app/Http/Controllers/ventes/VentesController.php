<?php

namespace GEICOM\Http\Controllers\ventes;

use Auth;
use GEICOM\Caisse;
use GEICOM\Client;
use GEICOM\Facture;
use GEICOM\Fournisseur;
use GEICOM\Functions;
use GEICOM\Http\Controllers\DefaultController;
use GEICOM\Http\Middleware\privileges\g_edit;
use GEICOM\Parametre;
use GEICOM\Pays;
use GEICOM\Produit;
use GEICOM\Usage;
use GEICOM\Vente;
use GEICOM\MouvementCaisse;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class VentesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $values=[];

    public function __construct()
    {
        $this->middleware('boutique');


        $this->values['big_title']='Gestion des ventes';

        $this->values['title']='Nouvelle vente';
    }

    private function normalizePeriod(Request $request)
    {
        $dd = $request->input('dd') ?: '1970-01-01 00:00:00';
        $df = $request->input('df') ?: date('Y-m-d 23:59:59');

        if (strlen($dd) === 10) {
            $dd .= ' 00:00:00';
        }
        if (strlen($df) === 10) {
            $df .= ' 23:59:59';
        }

        return [$dd, $df];
    }

    public function index()
    {
        array_forget($this->values,'title');


        $c=Client::orderBy('nom')->get();
        //$id_user=\Auth::user();
        $users = \Auth::user()->orderBy('name')->get(); // tous les utilisateurs

        $this->values['utilisateur']=$users;
        $this->values['Clients']=$c;
        return view('ventes.index',$this->values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cb=session('current_boutique')->id;


        $tva=Parametre::where('nom','=','tva')->first()->valeur;
        $c=Client::orderBy('nom')->get();
        $p=Pays::orderBy('nom')->get();
        $this->values['tva']=$tva;

        $this->values['clients']=$c;
        $this->values['pays']=$p;
        $this->values['caisses_entree']=Caisse::where('type', Caisse::TYPE_ENTREE)
            ->where('active', 1)
            ->whereHas('users', function ($query) {
                $query->where('users.id', \Auth::user()->id);
            })
            ->orderBy('nom')
            ->get();

        return view('ventes.new_vente',$this->values);

    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'client'         => 'required',
            'id'             => 'required|array',
            'date'           => 'date|required',
            'montant_verse'  => 'numeric|required',
            'id_caisse'      => 'required|numeric'
        ]);

        $id_list        = $request->input('id');
        $quantite_list  = $request->input('quantite');
        $prix_list      = $request->input('prix_unitaire');
        $reduction_list = $request->input('reduction');
        $reduction_gen  = $request->input('reduction_generale');
        $client         = $request->input('client');
        $date           = $request->input('date');
        $verse          = $request->input('montant_verse');
        $manuel          = $request->input('numfacture_manuel');
        $cb             = session('current_boutique')->id;
        $idCaisse       = $request->input('id_caisse');
        $caisse = Caisse::where('id', $idCaisse)->where('type', Caisse::TYPE_ENTREE)->first();
        if (!$caisse) {
            return redirect()->to(\URL::previous())->withErrors(['caisse' => 'Veuillez choisir une caisse d entree valide']);
        }

        if ($id_list) {
            // Création de la facture
            $f = new Facture();
            $f->date_vente = $date;
            $f->id_boutique = $cb;
            $f->id_client = $client;
            $f->numfacture_manuel = $manuel;
            $f->id_user = \Auth::user()->id;
            $f->id_caisse = $idCaisse;
            $f->save();

            $f->numero = 'FA' . sprintf('%08d', $f->id);

            $total = 0;

            // Charger tous les produits nécessaires
            $produits = Produit::with(['stock' => function ($query) use ($cb) {
                $query->where('id_boutique', $cb);
            }])->whereIn('id', $id_list)->get();

            // Boucle sur chaque produit du formulaire
            foreach ($id_list as $i => $id) {
                $prod = $produits->where('id', $id)->first();

                if (!$prod || !isset($prod->stock) || $prod->stock->quantite < $quantite_list[$i]) {
                    $f->delete();
                    return redirect()->to(\URL::previous())->withErrors([
                        'qi' => 'Quantité de ' . (isset($prod->libelle) ? $prod->libelle : 'produit') . ' insuffisante'

                    ]);
                }

                // Création de la vente
                $v = new Vente();
                $v->id_facture = $f->id;
                $v->id_produit = $prod->id;
                $v->numfacture_manuel = $manuel;
                $v->date_vente = $date;
                $v->id_boutique = $cb;
                $v->prix_achat = $prod->prix_achat;

                // Prix saisi dans le formulaire
                $v->prix_unitaire = $prix_list[$i];

                // Déduction du stock
                $v->quantite = $quantite_list[$i];
                $prod->stock->quantite -= $quantite_list[$i];
                $prod->stock->save();

                // Gestion réduction
                $red = isset($reduction_list[$i]) ? $reduction_list[$i] : null;
                if (!is_null($red) && $red !== '' && $red > 0) {
                    if ($red > ($v->prix_unitaire - $prod->prix_minimum)) {
                        $f->delete();
                        return redirect()->to(\URL::previous())->withErrors([
                            're' => 'La réduction de ' . $red . ' sur ' . $prod->libelle . ' est trop élevée'
                        ]);
                    }

                    $v->reduction = $red;
                    $v->total = ($v->prix_unitaire - $red) * $quantite_list[$i];
                } else {
                    $v->total = $v->prix_unitaire * $quantite_list[$i];
                }

                $total += $v->total;
                $v->save();

                // Historique usage
                $u = new Usage();
                $u->id_produit = $prod->id;
                $u->id_boutique = $cb;
                $u->details = "Vente: Quantité vendue = " . $quantite_list[$i];
                $u->date_utilisation = $date . ' ' . date('H:i:s');
                $u->stock = $prod->stock->quantite;
                $u->quantite = $quantite_list[$i];
                $u->save();
            }

            // Application TVA et réduction générale
            $tva = Parametre::where('nom', '=', 'tva')->first()->valeur;

            if ($reduction_gen) {
               // $total = $total - ($total * $reduction_gen / 100);
                $total = $total - $reduction_gen;
                $f->reduction = $reduction_gen;
            }

            $f->tva = $tva;
            $f->total = $total + ($total * $tva / 100);

            $f->verse = ($verse >= $f->total) ? $f->total : $verse;

            $f->save();
            MouvementCaisse::enregistrer($idCaisse, 'entree', $f->verse, 'vente', $f->id, 'Vente '.$f->numero, $date.' '.date('H:i:s'));

            return redirect()->route('show_facture', $f->id);
        }
    }

    public function storeko(Request $request)
    {
        $this->validate($request,[
            'client'         => 'required',
            'id'             => 'required',
            'date'           => 'date|required',
            'montant_verse'  => 'numeric|required'
        ]);

        $id_list         = $request->input('id');
        $quantite_list   = $request->input('quantite');
        $prix_list = $request->input('prix_unitaire');  // <-- récupère prix[] depuis la vue
        $reduction_list  = $request->input('reduction');
        $reduction_gen   = $request->input('reduction_generale');
        $client          = $request->input('client');
        $date            = $request->input('date');
        $verse           = $request->input('montant_verse');
        $cb              = session('current_boutique')->id;
      dd($request->all());

       if ($id_list) {

     /*      // Charger tous les produits avec stock en une seule requête
           $produits = Produit::with(['stock' => function ($query) use ($cb) {
               $query->where('id_boutique', $cb);
           }])->whereIn('id', $id_list)->get();

           // Vérification du stock avant toute création
           foreach ($id_list as $index => $id) {
               $prod = $produits->where('id', $id)->first();

               if (!$prod || !isset($prod->stock) || $prod->stock->quantite < $quantite_list[$index]) {
                   return redirect()->to(\URL::previous())->withErrors([
                       'qi' => 'Quantité de ' . (isset($prod->libelle) ? $prod->libelle : 'le produit') . ' insuffisante'
                   ]);
               }
           }*/
        if($id_list) {
            $f = new Facture();
            $f->date_vente = $date;
            $f->id_boutique = $cb;
            $f->id_client = $client;
            $f->id_user = \Auth::user()->id;
            $f->save();

            $f->numero = 'FA' . sprintf('%08d', $f->id);

            $i = 0;
            $total = 0;

            $p = Produit::with(['stock'=>function ($query) use($cb) {
                $query->where('id_boutique',$cb);
            }])->whereIn('id', $id_list)->get();

            foreach ($id_list as $id) {
                // Charger tous les produits avec stock en une seule requête
                $produits = Produit::with(['stock' => function ($query) use ($cb) {
                    $query->where('id_boutique', $cb);
                }])->whereIn('id', $id_list)->get();

                foreach ($id_list as $index => $id) {
                    $prod = $produits->where('id', $id)->first();

                    if (!$prod || !isset($prod->stock) || $prod->stock->quantite < $quantite_list[$index]) {
                        return redirect()->to(\URL::previous())->withErrors([
                            'qi' => 'Quantité de ' . (isset($prod->libelle) ? $prod->libelle : 'le produit') . ' insuffisante'
                        ]);
                    }
                }
            }
//                $prod = $p->where('id', '=', $id)->first();

                $v = new Vente();
                $v->id_facture = $f->id;
                $v->id_produit = $prod->id;
                $v->date_vente = $date;
                $v->id_boutique = $cb;
                $v->prix_achat = $prod->prix_achat;

                // ✅ Prix unitaire choisi dans le formulaire
                $v->prix_unitaire = $prix_list[$i];

                if($prod->stock->quantite >= $quantite_list[$i]) {
                    $v->quantite = $quantite_list[$i];
                    $prod->stock->quantite -= $quantite_list[$i];
                } else {
                    $f->delete();
                    return redirect()->to(\URL::previous())->withErrors([
                        'qi' => 'Quantité de '.$prod->libelle.' insuffisante'
                    ]);
                }
                $prod->stock->save();

                // Gestion réduction
                // Gestion réduction
                // Vérifie que la réduction est bien un nombre > 0
                $red = isset($reduction_list[$i]) ? $reduction_list[$i] : null;
                if (!is_null($red) && $red !== '' && $red > 0) {
                    if ($red > ($v->prix_unitaire - $prod->prix_minimum)) {
                        $f->delete();
                        return redirect()->to(\URL::previous())->withErrors([
                            're' => 'La réduction de '. $red .' sur  '.$prod->libelle.' est trop élevée'
                        ]);
                    }


                    $v->reduction = $red;
                    $v->total = ($v->prix_unitaire - $red) * $quantite_list[$i];
                } else {
                    $v->total = $v->prix_unitaire * $quantite_list[$i];
                }

                $total += $v->total;

                $v->save();

                $u = new Usage();
                $u->id_produit = $prod->id;
                $u->id_boutique = $cb;
                $u->details = "Vente: Quantité vendu = ".$quantite_list[$i];
                $u->date_utilisation = $date.' '.date('H:i:s');
                $u->stock = $prod->stock->quantite;
                $u->quantite = $quantite_list[$i];
                $u->save();

                $i++;
            }

            $tva = Parametre::where('nom','=','tva')->first()->valeur;

            if($reduction_gen){
                $total = $total - ($total * $reduction_gen / 100);
                $f->reduction = $reduction_gen;
            }

            $f->tva = $tva;
            $f->total = $total + ($total * $tva / 100);

            if($verse >= $f->total)
                $f->verse = $f->total;
            else
                $f->verse = $verse;

            $f->save();

            return redirect()->route('show_facture', $f->id);
        }
    }
    public function store1(Request $request)
    {
        $this->validate($request, [
            'client'        => 'required',
            'id'            => 'required',
            'date'          => 'date|required',
            'montant_verse' => 'numeric|required'
        ]);
        dump($request);
        die();
        $id_list        = $request->input('id');
        $quantite_list  = $request->input('quantite');
        $prix_list      = $request->input('prix_unitaire');
        $reduction_list = $request->input('reduction');
        $reduction_gen  = $request->input('reduction_generale');
        $client         = $request->input('client');
        $date           = $request->input('date');
        $verse          = $request->input('montant_verse');
        $cb             = session('current_boutique')->id;

        if ($id_list) {

            // Charger tous les produits avec stock en une seule requête
            $produits = Produit::with(['stock' => function ($query) use ($cb) {
                $query->where('id_boutique', $cb);
            }])->whereIn('id', $id_list)->get();

            // Vérification du stock avant toute création
            foreach ($id_list as $index => $id) {
                $prod = $produits->where('id', $id)->first();

                if (!$prod || !isset($prod->stock) || $prod->stock->quantite < $quantite_list[$index]) {
                    return redirect()->to(\URL::previous())->withErrors([
                        'qi' => 'Quantité de ' . (isset($prod->libelle) ? $prod->libelle : 'le produit') . ' insuffisante'
                    ]);
                }
            }



            // Création de la facture
            $f = new Facture();
            $f->date_vente  = $date;
            $f->id_boutique = $cb;
            $f->id_client   = $client;
            $f->id_user     = \Auth::user()->id;
            $f->save();
            $f->numero = 'FA' . sprintf('%08d', $f->id);

            $total = 0;
            foreach ($id_list as $i => $id) {
                $prod = $produits->where('id', $id)->first();
                $v = new Vente();
                $v->id_facture   = $f->id;
                $v->id_produit   = $prod->id;
                $v->date_vente   = $date;
                $v->id_boutique  = $cb;
                $v->prix_achat   = $prod->prix_achat;
                $v->prix_unitaire= $prix_list[$i];
                $v->quantite     = $quantite_list[$i];

                // Met à jour le stock
                $prod->stock->quantite -= $quantite_list[$i];
                $prod->stock->save();

                // Gestion réduction
                $red = isset($reduction_list[$i]) ? $reduction_list[$i] : null;
                if (!is_null($red) && $red !== '' && $red > 0) {
                    if ($red > ($v->prix_unitaire - $prod->prix_minimum)) {
                        $f->delete();
                        return redirect()->to(\URL::previous())->withErrors([
                            're' => 'La réduction de ' . $red . ' sur ' . $prod->libelle . ' est trop élevée'
                        ]);
                    }
                    $v->reduction = $red;
                    $v->total     = ($v->prix_unitaire - $red) * $quantite_list[$i];
                } else {
                    $v->total = $v->prix_unitaire * $quantite_list[$i];
                }

                $total += $v->total;
                $v->save();

                // Journalisation usage
                $u = new Usage();
                $u->id_produit      = $prod->id;
                $u->id_boutique     = $cb;
                $u->details         = "Vente: Quantité vendue = " . $quantite_list[$i];
                $u->date_utilisation= $date . ' ' . date('H:i:s');
                $u->stock           = $prod->stock->quantite;
                $u->quantite        = $quantite_list[$i];
                $u->save();
            }

            $tva = Parametre::where('nom', '=', 'tva')->first()->valeur;

            if ($reduction_gen) {
                $total -= $total * $reduction_gen / 100;
                $f->reduction = $reduction_gen;
            }

            $f->tva   = $tva;
            $f->total = $total + ($total * $tva / 100);
            $f->verse = ($verse >= $f->total) ? $f->total : $verse;
            $f->save();

            return redirect()->route('show_facture', $f->id);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

   /* public function store(Request $request)
    {
        $this->validate($request,[
            'client'         => 'required',
            'id'             => 'required',
            'date'           => 'date|required',
            'montant_verse'  => 'numeric|required'
        ]);

        $id_list        = $request->input('id');
        $quantite_list  = $request->input('quantite');
        $prix_list      = $request->input('prix'); // <-- récupère les prix unitaires saisis dans le formulaire
        $reduction_list = $request->input('reduction');
        $reduction_gen  = $request->input('reduction_generale');
        $client         = $request->input('client');
        $date           = $request->input('date');
        $verse          = $request->input('montant_verse');
        $cb             = session('current_boutique')->id;

        if($id_list) {
            $f = new Facture();
            $f->date_vente = $date;
            $f->id_boutique = $cb;
            $f->id_client = $client;
            $f->id_user = \Auth::user()->id;
            $f->save();

            $f->numero = 'FA' . sprintf('%08d', $f->id);
            $f->save();

            $i = 0;
            $total = 0;

            $p = Produit::with(['stock'=>function ($query) use($cb) {
                $query->where('id_boutique',$cb);
            }])->whereIn('id', $id_list)->get();

            foreach ($id_list as $id) {
                $prod = $p->where('id', '=', $id)->first();

                $v = new Vente();
                $v->id_facture = $f->id;
                $v->id_produit = $prod->id;
                $v->date_vente = $date;
                $v->id_boutique = $cb;
                $v->prix_achat = $prod->prix_achat;

                // Vérif stock
                if($prod->stock->quantite >= $quantite_list[$i]) {
                    $v->quantite = $quantite_list[$i];
                    $prod->stock->quantite -= $quantite_list[$i];
                } else {
                    $f->delete();
                    return redirect()->to(\URL::previous())->withErrors(['qi'=>'Quantité de '.$prod->libelle.' insuffisante']);
                }
                $prod->stock->save();

                // ⚡ utiliser le prix du formulaire si renseigné
                $prix_unitaire = isset($prix_list[$i]) && $prix_list[$i] > 0 ? $prix_list[$i] : $prod->prix;
                $v->prix_unitaire = $prix_unitaire;

                $red = !empty($reduction_list[$i]) ? $reduction_list[$i] : null;

                if ($red) {
                    if($red > ($prix_unitaire - $prod->prix_minimum)) {
                        $f->delete();
                        return redirect()->to(\URL::previous())->withErrors(['re'=>'La réduction de '. $red .' sur  '.$prod->libelle.' est trop élevée']);
                    }

                    $v->reduction = $red;
                    $v->total = ($prix_unitaire - $red) * $quantite_list[$i];
                } else {
                    $v->total = $prix_unitaire * $quantite_list[$i];
                }

                $total += $v->total;
                $v->save();

                // Historique d’usage
                $u = new Usage();
                $u->id_produit = $prod->id;
                $u->id_boutique = $cb;
                $u->details = "Vente: Quantité vendue = ".$quantite_list[$i];
                $u->date_utilisation = $date.' '.date('H:i:s');
                $u->stock = $prod->stock->quantite;
                $u->quantite = $quantite_list[$i];
                $u->save();

                $i++;
            }

            // TVA
            $tva = Parametre::where('nom','=','tva')->first()->valeur;

            if($reduction_gen){
                $total = $total - ($total * $reduction_gen / 100);
                $f->reduction = $reduction_gen;
            }
            $f->tva = $tva;
            $f->total = $total + ($total * $tva / 100);

            if($verse >= $f->total)
                $f->verse = $f->total;
            else
                $f->verse = $verse;

            $f->save();

            return redirect()->route('show_facture',$f->id);
        }
    }*/

    /*   public function store(Request $request)
       {
           $this->validate($request,[
              'client'=>'required',
               'id'=>'required',
               'date'=>'date|required',
               'montant_verse'=>'numeric|required'

           ]);
           $id_list=$request->input('id');
           $quantite_list=$request->input('quantite');
           $reduction_list=$request->input('reduction');
           $reduction_gen=$request->input('reduction_generale');
           $client=$request->input('client');
           $date=$request->input('date');
           $verse=$request->input('montant_verse');
           $cb=session('current_boutique')->id;


           if($id_list) {
               $f = new Facture();
               $f->date_vente = $date;
               $f->id_boutique=$cb;
               $f->id_client = $client;
               $f->id_user=\Auth::user()->id;
               $f->save();
               $f->numero = 'FA' . sprintf('%08d', $f->id);
               $i = 0;$total=0;
               $p = Produit::with(['stock'=>function ($query) use($cb) {
                   $query->where('id_boutique',$cb);
               }])->whereIn('id', $id_list)->get();
               foreach ($id_list as $id) {
                   $prod = $p->where('id', '=', $id)->first();

                   $v = new Vente();
                   $v->id_facture = $f->id;
                   $v->id_produit = $prod->id;
                   $v->date_vente=$date;
                   $v->id_boutique=$cb;
                   $v->prix_achat=$prod->prix_achat;

                   if($prod->stock->quantite>=$quantite_list[$i])
                   {
                       $v->quantite = $quantite_list[$i];
                       $prod->stock->quantite-=$quantite_list[$i];
                   }else
                   {
                       $f->delete();
                       return redirect()->to(\URL::previous())->withErrors(['qi'=>'Quantité de '.$prod->libelle.' insuffisante']);
                      // $v->quantite =  $prod->stock->quantite;
                       //$prod->stock->quantite=0;
                   }
                   $prod->stock->save();
                   $v->prix_unitaire = $prod->prix;
                   $red=null;
                   if($reduction_list[$i])
                   {
                           $red=$reduction_list[$i];

                   }

                   if ($red) {
                       if($red > ($prod->prix-$prod->prix_minimum)){
                           $f->delete();
                           return redirect()->to(\URL::previous())->withErrors(['re'=>'La réduction de '. $red .' sur  '.$prod->libelle.' est trop élevée']);

                       }

                       $v->reduction = $red;
                       //$v->total = ($prod->prix * $quantite_list[$i]) - (($prod->prix * $quantite_list[$i]) * $red / 100);
                       $v->total = ($prod->prix -$red)* $quantite_list[$i] ;

                   } else {
                       $v->total = ($prod->prix * $quantite_list[$i]);
                   }

                   $total+=$v->total;

                    $v->save();
                    $u=new Usage();
                    $u->id_produit=$prod->id;
                    $u->id_boutique=$cb;
                    $u->details="Vente: Quantité vendu = ".$quantite_list[$i];
                    $u->date_utilisation=$date.' '.date('H:i:s');
                    $u->stock=$prod->stock->quantite;
                    $u->quantite=$quantite_list[$i];
                    $u->save();
                   $i++;
               }

               //$tva=19.25;
               $tva=Parametre::where('nom','=','tva')->first()->valeur;

               if($reduction_gen){
                   $total=$total-($total*$reduction_gen/100);
                   $f->reduction=$reduction_gen;
               }
               $f->tva=$tva;
               $f->total=$total+($total*($tva)/100);
                   if($verse>=$f->total)
                       $f->verse=$f->total;
                   else
                       $f->verse=$verse;

               $f->save();

               return redirect()->route('show_facture',$f->id);


           }
       }*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function facture($id)
    {


        $f=Facture::with('ventes.produit','client','vendeur')->find($id);
        $this->values['title']='Facture';

        $p=Parametre::all();

        $this->values['param']=$p;
        $this->values['boutique']=session('current_boutique');
        $this->values['facture']=$f;
        return view('ventes.facture',$this->values);
    }

    public function changeFactureState(Request $request){
        $this->validate($request,[
           'id'=>'required|numeric',
           'montant'=>'required|numeric',
           'op'=>'required|numeric',
        ]);
        $id=$request->input('id');
        $montant=$request->input('montant');
        $op=$request->input('op');
        $f=Facture::find($id);
        if($op==0){
            $f->verse+=$montant;
            $str='ajouter';
        }
        else{
            $r=Auth::user()->roles->pluck('value')->toArray();
            if(!Functions::pp_exists($r,2)){
                return redirect()->route('not_auth');
            }
            $f->verse-=$montant;
            $str='soustrait';
        }

        if($f->verse<0)
            $f->verse=0;

        $f->save();

        DefaultController::log("L'utilisateur ".\Auth::user()->name.' a '.$str.' '.$montant.' sur la '.trans('main.m_verse').' pour la facture '.$f->numero);
        return redirect()->to(\URL::previous());
    }
    public function destroyFacture($id)
    {
        // Récupère la facture avec ses ventes
        $facture = Facture::with('ventes.produit.stock')->find($id);

        if (!$facture) {
            return redirect()->back()->withErrors(['erreur' => 'Facture introuvable']);
        }

        foreach ($facture->ventes as $vente) {
            $produit = $vente->produit;

            if ($produit && $produit->stock) {
                // On remet la quantité vendue dans le stock
                $produit->stock->quantite += $vente->quantite;
                $produit->stock->save();
            }

            // (Optionnel) Supprimer les usages créés pour cette vente
            Usage::where('id_produit', $vente->id_produit)
                ->where('id_boutique', $facture->id_boutique)
                ->whereDate('date_utilisation', $vente->date_vente)
                ->where('quantite', $vente->quantite)
                ->delete();

            // Supprimer la vente
            $vente->delete();
        }

        // Supprimer la facture
        $facture->delete();

        return redirect()->route('liste_ventes')->with('success', 'Facture et ventes associées supprimées.');
    }


    public function liste_ventes(Request $request)
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->validate($request,[
            'client'=>'required',
        ]);

        list($dd, $df) = $this->normalizePeriod($request);
        $client=$request->input('client');
        /*dump($client);
        die();*/
       // \Auth::user()->id;
        $user=0;

        if(!$dd)
            $dd='1970-01-01';
        if(!$df)
            $df=date('Y-m-d 23:59:59');

        $this->values['title']='Récapitulatif des ventes';

        if($client==0)

            $f=Facture::with(['client', 'user'])->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

        else{
            $f=Facture::with(['client', 'user'])->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->where('id_client','=',$client)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

            $this->values['client']=Client::find($client);

        }

        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['factures']=$f;
        return view('ventes.liste_ventes',$this->values);

    }

    public function liste_ventes_connecte(Request $request)
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->validate($request,[
            'client'=>'required',
        ]);

        list($dd, $df) = $this->normalizePeriod($request);
        $client=$request->input('client');
        $user=\Auth::user()->id;

        $this->values['title']='Récapitulatif de mes ventes';

        $queryFactures = Facture::with(['client', 'user'])
            ->where('id_boutique','=',$cbId)
            ->where('date_vente','>=',$dd)
            ->where('date_vente','<=',$df)
            ->where('id_user','=',$user)
            ->orderBy('date_vente','desc')
            ->orderBy('created_at','desc');

        if($client!=0){
            $queryFactures->where('id_client','=',$client);
            $this->values['client']=Client::find($client);
        }

        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['factures']=$queryFactures->get();
        return view('ventes.liste_ventes',$this->values);
    }

    //ventes avec benefices

    public function liste_ventes_produit(Request $request)
    {
        $cb = session('current_boutique');
        $cbId = $cb->id;

        $this->validate($request, [
            'client' => 'required',
        ]);

        list($dd, $df) = $this->normalizePeriod($request);
        $client = $request->input('client');

        $this->values['title'] = 'Récapitulatif des ventes par client et produit';

        $queryFactures = Facture::with(['client', 'ventes.produit'])
            ->where('id_boutique', $cbId)
            ->whereBetween('date_vente', [$dd, $df])
            ->orderBy('date_vente', 'desc')
            ->orderBy('created_at', 'desc');

        if ($client != 0) {
            $queryFactures->where('id_client', $client);
            $this->values['client'] = Client::find($client);
        }

        $factures = $queryFactures->get();

        // Tableau des bénéfices par client et produit
        $ventesParClient = [];

        foreach ($factures as $facture) {
            $clientId = $facture->id_client;
            $clientNom = isset($facture->client->nom) ? $facture->client->nom : 'Client inconnu';

            if (!isset($ventesParClient[$clientId])) {
                $ventesParClient[$clientId] = [
                    'client_nom' => $clientNom,
                    'produits' => []
                ];
            }

            foreach ($facture->ventes as $vente) {
                $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;

                if (!isset($ventesParClient[$clientId]['produits'][$vente->id_produit])) {
                    $ventesParClient[$clientId]['produits'][$vente->id_produit] = [
                        'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                        'quantite_totale' => 0,
                        'total_vente' => 0,
                        'total_benefice' => 0,
                    ];
                }

                $ventesParClient[$clientId]['produits'][$vente->id_produit]['quantite_totale'] += $vente->quantite;
                $ventesParClient[$clientId]['produits'][$vente->id_produit]['total_vente'] += $vente->total;
                $ventesParClient[$clientId]['produits'][$vente->id_produit]['total_benefice'] += $benefice;
            }
        }
        $this->values['factures'] = $factures;
//        $this->values['beneficesParProduit'] = $beneficesParProduit;
        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['ventesParClient'] = $ventesParClient;

        return view('ventes.liste_ventes_produit', $this->values);
    }
    public function liste_ventes_user(Request $request)
    {
        $cb = session('current_boutique');
        $cbId = $cb->id;

        $this->validate($request, [
            'client' => 'required',
            'user' => 'required',
        ]);

        list($dd, $df) = $this->normalizePeriod($request);
        $client = $request->input('client');
        $user = $request->input('user');

        $this->values['title'] = 'Récapitulatif des ventes par utilisateur et produit';

        $queryFactures = Facture::with(['client', 'ventes.produit', 'user'])
            ->where('id_boutique', $cbId)
            ->whereBetween('date_vente', [$dd, $df])
            ->orderBy('date_vente', 'desc')
            ->orderBy('created_at', 'desc');

        if ($user != 0) {
            $queryFactures->where('id_user', $user);
        }

        if ($client != 0) {
            $queryFactures->where('id_client', $client);
            $this->values['client'] = Client::find($client);
        }

        $factures = $queryFactures->get();

        $ventesParUtilisateur = [];

        foreach ($factures as $facture) {
            $userId = $facture->id_user;
            $clientId = $facture->id_client;
            $userNom = isset($facture->user->name) ? $facture->user->name : 'Utilisateur inconnu';
            $clientNom = isset($facture->client->nom) ? $facture->client->nom : 'Client inconnu';

            if (!isset($ventesParUtilisateur[$userId])) {
                $ventesParUtilisateur[$userId] = [
                    'user_nom' => $userNom,
                    'clients' => []
                ];
            }

            if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId])) {
                $ventesParUtilisateur[$userId]['clients'][$clientId] = [
                    'client_nom' => $clientNom,
                    'produits' => []
                ];
            }

            foreach ($facture->ventes as $vente) {
                $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;

                if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit])) {
                    $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit] = [
                        'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                        'quantite_totale' => 0,
                        'total_vente' => 0,
                        'total_benefice' => 0,
                    ];
                }

                $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit]['quantite_totale'] += $vente->quantite;
                $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit]['total_vente'] += $vente->total;
                $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit]['total_benefice'] += $benefice;
            }
        }

        $this->values['factures'] = $factures;
        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['ventesParUtilisateur'] = $ventesParUtilisateur;
       // $this->values['users'] = \App\Models\User::all(); // Pour le formulaire
        $this->values['users'] =  $user = \Auth::user()->id;  // Pour le formulaire

        return view('ventes.liste_ventes_user', $this->values);
    }
    public function liste_ventes_clients(Request $request)
    {
        $cb = session('current_boutique');
        $cbId = $cb->id;

        // Validation optionnelle (user et client peuvent être absents ou 0)
        $this->validate($request, [
            'client' => 'nullable|integer',
            'user' => 'nullable|integer',
            'dd' => 'nullable|date',
            'df' => 'nullable|date',
        ]);

        // Dates avec valeurs par défaut si non renseignées
        list($dd, $df) = $this->normalizePeriod($request);

        // Récupérer les filtres user et client, avec 0 par défaut (signifie "tous")
        $client = $request->input('client', 0);
        $user = $request->input('user', 0);

        $this->values['title'] = 'Récapitulatif des ventes par utilisateur et produit';

        // Construire la requête principale
        $queryFactures = Facture::with(['client', 'ventes.produit', 'user'])
            ->where('id_boutique', $cbId)
            ->whereBetween('date_vente', [$dd, $df])
            ->orderBy('date_vente', 'desc')
            ->orderBy('created_at', 'desc');

        // Appliquer filtre user seulement si != 0
        if ($user != 0) {
            $queryFactures->where('id_user', $user);
        }

        // Appliquer filtre client seulement si != 0
        if ($client != 0) {
            $queryFactures->where('id_client', $client);
            $this->values['client'] = Client::find($client);
        } else {
            $this->values['client'] = null;
        }

        $factures = $queryFactures->get();

        // Préparer la structure de ventes par utilisateur et client
        $ventesParUtilisateur = [];

        foreach ($factures as $facture) {
            $userId = $facture->id_user;
            $clientId = $facture->id_client;
            $userNom = isset($facture->user->name) ? $facture->user->name : 'Utilisateur inconnu';
            $clientNom = isset($facture->client->nom) ? $facture->client->nom : 'Client inconnu';

            if (!isset($ventesParUtilisateur[$userId])) {
                $ventesParUtilisateur[$userId] = [
                    'user_nom' => $userNom,
                    'clients' => []
                ];
            }

            if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId])) {
                $ventesParUtilisateur[$userId]['clients'][$clientId] = [
                    'client_nom' => $clientNom,
                    'produits' => []
                ];
            }

            foreach ($facture->ventes as $vente) {
                $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;
                $totalAchat = $vente->prix_achat * $vente->quantite;

                if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit])) {
                    $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit] = [
                        'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                        'quantite_totale' => 0,
                        'total_vente' => 0,
                        'total_benefice' => 0,
                        'total_achat' => 0,
                    ];
                }

                $produitData = &$ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit];

                $produitData['quantite_totale'] += $vente->quantite;
                $produitData['total_vente'] += $vente->total;
                $produitData['total_benefice'] += $benefice;
                $produitData['total_achat'] += $totalAchat;
            }
        }

        // Trier les produits par total d'achat décroissant
        foreach ($ventesParUtilisateur as &$userData) {
            foreach ($userData['clients'] as &$clientData) {
                uasort($clientData['produits'], function ($a, $b) {
                    if ($a['total_achat'] == $b['total_achat']) {
                        return 0;
                    }
                    return ($a['total_achat'] < $b['total_achat']) ? 1 : -1;
                });
            }
        }
        unset($userData, $clientData); // nettoyer références

        $this->values['factures'] = $factures;
        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['ventesParUtilisateur'] = $ventesParUtilisateur;
        $this->values['users'] = \Auth::user()->id;

        return view('ventes.liste_client_ventesup', $this->values);
    }

    public function liste_ventes_clients12(Request $request)
    {
        $cb = session('current_boutique');
        $cbId = $cb->id;
      /*  dump($request);
        die();*/
        $this->validate($request, [
            'client' => 'required',
            'user' => 'required',
        ]);

        $dd = $request->input('dd') ?: '1970-01-01';
        $df = $request->input('df') ?: date('Y-m-d 23:59:59');
        $client = $request->input('client');

        $user = $request->input('user');
        $this->values['title'] = 'Récapitulatif des ventes par utilisateur et produit';

        $queryFactures = Facture::with(['client', 'ventes.produit', 'user'])
            ->where('id_boutique', $cbId)
            ->whereBetween('date_vente', [$dd, $df])
            ->where('id_user', $user)
            ->orderBy('date_vente', 'desc')
            ->orderBy('created_at', 'desc');

        if ($client != 0) {
            $queryFactures->where('id_client', $client);
            $this->values['client'] = Client::find($client);
        }

        $factures = $queryFactures->get();

        $ventesParUtilisateur = [];

        foreach ($factures as $facture) {
            $userId = $facture->id_user;
            $clientId = $facture->id_client;
            $userNom = isset($facture->user->name) ? $facture->user->name : 'Utilisateur inconnu';
            $clientNom = isset($facture->client->nom) ? $facture->client->nom : 'Client inconnu';

            if (!isset($ventesParUtilisateur[$userId])) {
                $ventesParUtilisateur[$userId] = [
                    'user_nom' => $userNom,
                    'clients' => []
                ];
            }

            if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId])) {
                $ventesParUtilisateur[$userId]['clients'][$clientId] = [
                    'client_nom' => $clientNom,
                    'produits' => []
                ];
            }

            foreach ($facture->ventes as $vente) {
                $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;
                $totalAchat = $vente->prix_achat * $vente->quantite;

                if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit])) {
                    $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit] = [
                        'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                        'quantite_totale' => 0,
                        'total_vente' => 0,
                        'total_benefice' => 0,
                        'total_achat' => 0,
                    ];
                }

                $produitData = &$ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit];

                $produitData['quantite_totale'] += $vente->quantite;
                $produitData['total_vente'] += $vente->total;
                $produitData['total_benefice'] += $benefice;
                $produitData['total_achat'] += $totalAchat;
            }
        }

        // Trier les produits par total d'achat décroissant (version compatible PHP < 7)
        foreach ($ventesParUtilisateur as &$userData) {
            foreach ($userData['clients'] as &$clientData) {
                uasort($clientData['produits'], function ($a, $b) {
                    if ($a['total_achat'] == $b['total_achat']) {
                        return 0;
                    }
                    return ($a['total_achat'] < $b['total_achat']) ? 1 : -1;
                });
            }
        }
        unset($userData, $clientData); // important pour les références

        $this->values['factures'] = $factures;
        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['ventesParUtilisateur'] = $ventesParUtilisateur;
        $this->values['users'] = \Auth::user()->id;

        return view('ventes.liste_client_ventesup', $this->values);
    }

    /* public function liste_ventes_clients(Request $request)
     {
         $cb = session('current_boutique');
         $cbId = $cb->id;

         $this->validate($request, [
             'client' => 'required',
             'user' => 'required',
         ]);

         $dd = $request->input('dd') ?: '1970-01-01';
         $df = $request->input('df') ?: date('Y-m-d 23:59:59');
         $client = $request->input('client');
         $user = $request->input('user');

         $this->values['title'] = 'Récapitulatif des ventes par utilisateur et produit';

         $queryFactures = Facture::with(['client', 'ventes.produit', 'user'])
             ->where('id_boutique', $cbId)
             ->whereBetween('date_vente', [$dd, $df])
             ->where('id_user', $user)
             ->orderBy('date_vente', 'desc')
             ->orderBy('created_at', 'desc');

         if ($client != 0) {
             $queryFactures->where('id_client', $client);
             $this->values['client'] = Client::find($client);
         }

         $factures = $queryFactures->get();

         $ventesParUtilisateur = [];

         foreach ($factures as $facture) {
             $userId = $facture->id_user;
             $clientId = $facture->id_client;
             $userNom = isset($facture->user->name) ? $facture->user->name : 'Utilisateur inconnu';
             $clientNom = isset($facture->client->nom) ? $facture->client->nom : 'Client inconnu';

             if (!isset($ventesParUtilisateur[$userId])) {
                 $ventesParUtilisateur[$userId] = [
                     'user_nom' => $userNom,
                     'clients' => []
                 ];
             }

             if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId])) {
                 $ventesParUtilisateur[$userId]['clients'][$clientId] = [
                     'client_nom' => $clientNom,
                     'produits' => []
                 ];
             }

             foreach ($facture->ventes as $vente) {
                 $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;
                 $totalAchat = $vente->prix_achat * $vente->quantite;

                 if (!isset($ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit])) {
                     $ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit] = [
                         'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                         'quantite_totale' => 0,
                         'total_vente' => 0,
                         'total_benefice' => 0,
                         'total_achat' => 0,
                     ];
                 }

                 $produitData = &$ventesParUtilisateur[$userId]['clients'][$clientId]['produits'][$vente->id_produit];

                 $produitData['quantite_totale'] += $vente->quantite;
                 $produitData['total_vente'] += $vente->total;
                 $produitData['total_benefice'] += $benefice;
                 $produitData['total_achat'] += $totalAchat;
             }
         }

         // Trier les produits par total d'achat décroissant
         foreach ($ventesParUtilisateur as &$userData) {
             foreach ($userData['clients'] as &$clientData) {
                 uasort($clientData['produits'], function ($a, $b) {
                     return $b['total_achat'] <=> $a['total_achat'];
                 });
             }
         }
         unset($userData, $clientData); // pour éviter les références persistantes

         $this->values['factures'] = $factures;
         $this->values['dd'] = $dd;
         $this->values['df'] = $df;
         $this->values['ventesParUtilisateur'] = $ventesParUtilisateur;
         $this->values['users'] = \Auth::user()->id;

         return view('ventes.liste_client_ventesup', $this->values);
     }*/



    public function liste_ventes_user1(Request $request)
    {
        $cb = session('current_boutique');
        $cbId = $cb->id;

        $this->validate($request, [
            'client' => 'required',
        ]);

        $dd = $request->input('dd') ?: '1970-01-01';
        $df = $request->input('df') ?: date('Y-m-d 23:59:59');
        $client = $request->input('client');
        $user = \Auth::user()->id;

        $this->values['title'] = 'Récapitulatif des ventes par utilisateur et produit';

        $queryFactures = Facture::with(['client', 'ventes.produit'])
            ->where('id_boutique', $cbId)
            ->whereBetween('date_vente', [$dd, $df])
            ->where('id_user', $user)
            ->orderBy('date_vente', 'desc')
            ->orderBy('created_at', 'desc');

        if ($client != 0) {
            $queryFactures->where('id_client', $client);
            $this->values['client'] = Client::find($client);
        }

        $factures = $queryFactures->get();

        // Tableau des bénéfices par client et produit
        $ventesParClient = [];

        foreach ($factures as $facture) {
            $clientId = $facture->id_client;
            $clientNom = isset($facture->client->nom) ? $facture->client->nom : 'Client inconnu';

            if (!isset($ventesParClient[$clientId])) {
                $ventesParClient[$clientId] = [
                    'client_nom' => $clientNom,
                    'produits' => []
                ];
            }

            foreach ($facture->ventes as $vente) {
                $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;

                if (!isset($ventesParClient[$clientId]['produits'][$vente->id_produit])) {
                    $ventesParClient[$clientId]['produits'][$vente->id_produit] = [
                        'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                        'quantite_totale' => 0,
                        'total_vente' => 0,
                        'total_benefice' => 0,
                    ];
                }

                $ventesParClient[$clientId]['produits'][$vente->id_produit]['quantite_totale'] += $vente->quantite;
                $ventesParClient[$clientId]['produits'][$vente->id_produit]['total_vente'] += $vente->total;
                $ventesParClient[$clientId]['produits'][$vente->id_produit]['total_benefice'] += $benefice;
            }
        }
        $this->values['factures'] = $factures;
//        $this->values['beneficesParProduit'] = $beneficesParProduit;
        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['ventesParClient'] = $ventesParClient;

        return view('ventes.liste_ventes_user', $this->values);
    }


    public function liste_ventes_produit1(Request $request)
    {
        $cb = session('current_boutique');
        $cbId = $cb->id;

        $this->validate($request, [
            'client' => 'required',
        ]);

        $dd = $request->input('dd') ?: '1970-01-01';
        $df = $request->input('df') ?: date('Y-m-d 23:59:59');
        $client = $request->input('client');
        $user = \Auth::user()->id;

        $this->values['title'] = 'Récapitulatif des ventes par produit';
        if ($client ==0) {
            $queryFactures = Facture::with(['client', 'ventes.produit'])
                ->where('id_boutique', $cbId)
                ->whereBetween('date_vente', [$dd, $df])
                ->where('id_user', $user)
                ->orderBy('date_vente', 'desc')
                ->orderBy('created_at', 'desc');
        }else{
            $queryFactures = Facture::with(['client', 'ventes.produit'])
                ->where('id_boutique', $cbId)
                ->whereBetween('date_vente', [$dd, $df])
                ->where('id_user', $user)
                ->orderBy('date_vente', 'desc')
                ->orderBy('created_at', 'desc');
            $queryFactures->where('id_client', $client);
            $this->values['client'] = Client::find($client);
        }

        $factures = $queryFactures->get();

        // Tableau des bénéfices par produit
        $beneficesParProduit = [];

        foreach ($factures as $facture) {
            foreach ($facture->ventes as $vente) {
                $benefice = ($vente->prix_unitaire - $vente->prix_achat) * $vente->quantite;

                if (!isset($beneficesParProduit[$vente->id_produit])) {
                    $beneficesParProduit[$vente->id_produit] = [
//                        'produit' => $vente->produit->libelle ?? '',
                        'produit' => isset($vente->produit->libelle) ? $vente->produit->libelle : '',
                        'quantite_totale' => 0,
                        'total_vente' => 0,
                        'total_benefice' => 0,
                    ];
                }

                $beneficesParProduit[$vente->id_produit]['quantite_totale'] += $vente->quantite;
                $beneficesParProduit[$vente->id_produit]['total_vente'] += $vente->total;
                $beneficesParProduit[$vente->id_produit]['total_benefice'] += $benefice;
            }
        }

        $this->values['dd'] = $dd;
        $this->values['df'] = $df;
        $this->values['factures'] = $factures;
        $this->values['beneficesParProduit'] = $beneficesParProduit;

        return view('ventes.liste_ventes_produit', $this->values);
    }




    public function liste_ventesUser(Request $request)
    {
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->validate($request,[
            'client'=>'required',
        ]);

        $dd=$request->input('dd');
        $df=$request->input('df');
        $client=$request->input('client');

        if(!$dd)
            $dd='1970-01-01';
        if(!$df)
            $df=date('Y-m-d 23:59:59');

        $this->values['title']='Récapitulatif des ventes';

        if($client==0)

            $f=Facture::with('client')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

        else{
            $f=Facture::with('client')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->where('id_client','=',$client)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();
            $this->values['client']=Client::find($client);

        }

        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['factures']=$f;
        return view('ventes.liste_ventes',$this->values);

    }


    public function details_ventes($id)
    {
        $this->values['title']='Details des ventes';

        $f=Facture::with(['ventes.produit','client','vendeur'])->find($id);

        $this->values['facture']=$f;
        return view('ventes.details_vente',$this->values);

    }
     public function liste_clients()
        {
            $this->values['title']='Liste des clients';

            $c=Client::with('pays')->orderBy('nom')->get();
            $this->values['clients']=$c;
            return view('ventes.liste_clients',$this->values);

        }
         public function liste_fournisseurs()
                {
                    $this->values['title']='Liste des fournisseurs';

                    $c=Fournisseur::with('pays')->orderBy('nom')->get();
                    $this->values['fournisseurs']=$c;
                    return view('ventes.liste_fournisseurs',$this->values);

                }

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
    public function update(Request $request, $id)
    {
        //
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
