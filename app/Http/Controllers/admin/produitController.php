<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Boutique;
use GEICOM\Categorie;
use GEICOM\ProduitLies;
use GEICOM\Stock;
use Illuminate\Http\Request;
use GEICOM\Produit;
use GEICOM\Http\Controllers\Controller;

class produitController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion produits';
    }
    public  function index()
    {
        $p=Produit::with('categorie')->orderBy('libelle')->paginate(200);
        $c=Categorie::all();
        $this->values['produit']=$p  ;
        $this->values['categories']=$c;
        return view('admin.produit',$this->values);
    }

    public  function index1()
    {
        $p=Produit::with('categorie')->orderBy('libelle')->paginate(200);
        $c=Categorie::all();
        $this->values['produit']=$p  ;
        $this->values['categories']=$c;
        return view('stocks.edit_produit',$this->values);
    }


    public  function store1(Request $request)
    {
        $this->validate($request,[
            'libelle'=>'required|unique:produits',
            'prix'=>'required|numeric',
            'prix_achat'=>'required|numeric',
            'id_categorie'=>'required',
            'quantite_minimale'=>'required|numeric',
        ]);
        $libele=$request->input('libelle');
        $reference=$request->input('reference');
        $prix=$request->input('prix');
        $prix_a=$request->input('prix_achat');

        $des=$request->input('description');
        $prix_min=$request->input('quantite_minimale');
        $ca=$request->input('id_categorie');
        $p=new Produit();
        $p->libelle=$libele;
        $p->description=$des;
        $p->reference=$reference;
        $p->id_categorie=$ca;
        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix=$prix;
        $p->prix_achat=$prix_a;

        $p->save();

        $date=date('Y-m-d H:i:s');
        $b=Boutique::all();
        $i_arr=[];
        $i=0;
        foreach ($b as $bou){
            $i_arr[$i]=['id_produit'=>$p->id,'id_boutique'=>$bou->id,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);


        return redirect()->route('nouvel_achat')->withSuccess(['ok'=>'']);
    }


    public  function store(Request $request)
    {
        $this->validate($request,[
           'libelle'=>'required|unique:produits',
            'prix'=>'required|numeric',
            'prix_semi_gros' => 'required|numeric',
            'prix_comptoir' => 'required|numeric',
            'prix_gros' => 'required|numeric',
            'prix_achat'=>'required|numeric',
            'id_categorie'=>'required',
            'quantite_minimale'=>'required|numeric',
        ]);
        $libele=$request->input('libelle');
        $reference=$request->input('reference');
        $prix=$request->input('prix');
        $prix_semi_gros = $request->input('prix_semi_gros');
        $prix_comptoir = $request->input('prix_comptoir');
        $prix_gros = $request->input('prix_gros');
        $prix_a=$request->input('prix_achat');

        $des=$request->input('description');
        $prix_min=$request->input('quantite_minimale');
        $ca=$request->input('id_categorie');
        $p=new Produit();
        $p->libelle=$libele;
        $p->description=$des;
        $p->reference=$reference;
        $p->id_categorie=$ca;

        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix=$prix;
        $p->prix_semi_gros = $prix_semi_gros;
        $p->prix_comptoir = $prix_comptoir;
        $p->prix_gros = $prix_gros;
        $p->prix_achat=$prix_a;


        $p->save();
      /*  dump($p);
        die();*/

        $date=date('Y-m-d H:i:s');
        $b=Boutique::all();
        $i_arr=[];
        $i=0;
        foreach ($b as $bou){
            $i_arr[$i]=['id_produit'=>$p->id,'id_boutique'=>$bou->id,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);


        return redirect()->route('produit_management')->withSuccess(['ok'=>'']);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
            'prix' => 'required|numeric',
            'prix_semi_gros' => 'required|numeric',
            'prix_comptoir' => 'required|numeric',
            'prix_gros' => 'required|numeric',
            'prix_achat' => 'required|numeric',

            'id_categorie' => 'required',
            'id'=>'required|numeric',
            'quantite_minimale'=>'required|numeric',

        ]);

        $id=$request->input('id');
        $libele = $request->input('libelle');
        $reference = $request->input('reference');
        $prix = $request->input('prix');
       $prix_semi_gros = $request->input('prix_semi_gros');
        $prix_comptoir = $request->input('prix_comptoir');
        $prix_gros = $request->input('prix_gros');
        $prix_a = $request->input('prix_achat');

        $des = $request->input('description');
        $prix_min = $request->input('quantite_minimale');
        $ca = $request->input('id_categorie');
        $p=Produit::find($id);
        $p->libelle = $libele;
        $p->description = $des;
        $p->reference = $reference;
        $p->id_categorie = $ca;
       /* $p->prix_semi_gros = $prix_semi_gros;
        $p->prix_comptoir = $prix_comptoir;
        $p->prix_gros = $prix_gros;*/
        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix = $prix;
        $p->prix_semi_gros = $prix_semi_gros;
        $p->prix_comptoir = $prix_comptoir;
        $p->prix_gros = $prix_gros;
        $p->prix_achat = $prix_a;

        $p->save();
        return redirect()->route('produit_management')->withSuccess(['ok' => '']);
    }
    public function search(Request $request)
    {
        $query = $request->get('q');

        $produits = Produit::with('categorie')
            ->where('libelle', 'like', '%' . $query . '%')
            ->orWhere('reference', 'like', '%' . $query . '%')
            ->get();

        return response()->json($produits);
    }



    public function update1(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
            'prix' => 'required|numeric',
            'prix_achat' => 'required|numeric',

            'id_categorie' => 'required',
            'id'=>'required|numeric',
            'quantite_minimale'=>'required|numeric',

        ]);

        $id=$request->input('id');
        $libele = $request->input('libelle');
        $reference = $request->input('reference');
        $prix = $request->input('prix');
        $prix_a = $request->input('prix_achat');

        $des = $request->input('description');
        $prix_min = $request->input('quantite_minimale');
        $ca = $request->input('id_categorie');
        $p=Produit::find($id);
        $p->libelle = $libele;
        $p->description = $des;
        $p->reference = $reference;
        $p->id_categorie = $ca;
        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix = $prix;
        $p->prix_achat = $prix_a;

        $p->save();
        return redirect()->route('nouvel_achat')->withSuccess(['ok' => '']);
    }



    public function destroy($id)
    {
        produit::destroy($id);
        return redirect()->route('produit_management')->withSuccess(['ok'=>'']);


    }
    public function destroys(Request $request)
    {
        $idList = $request->input('check');

        produit::destroy($idList);
        return response()->json('OK');
    }

    public function index_lies($id)
    {
        $pl=ProduitLies::with(['produit_p','produit_c'])->where('id_produit_parent','=',$id)->get();
        $arr=$pl->pluck('id_produit')->toArray();

        $p=Produit::whereNotIn('id',$arr)->orderBy('libelle')->get();
        $this->values['title']='Produits liés';
        $this->values['produit']=Produit::find($id);

        $this->values['produits']=$p;
        $this->values['produit_lies']=$pl;

        return view('admin.produit_lies',$this->values);
    }

    public function store_lies(Request $request)
    {
        $this->validate($request,[
           'id'=>'required|numeric',
            'produit'=>'required',
            'quantite'=>'required|numeric',
        ]);

        $pl=new ProduitLies();
        $pl->id_produit_parent=$request->input('id');
        $pl->id_produit=$request->input('produit');
        $pl->quantite=$request->input('quantite');

        $pl->save();

        return redirect()->to(route('produit_lies_management',$request->input('id')))->withSuccess(['success'=>true]);

    }

    public function destroy_lies($id)
    {
        $pl=ProduitLies::find($id);
        $id_p=$pl->id_produit_parent;
        ProduitLies::destroy($id);

        return redirect()->to(route('produit_lies_management',$id_p))->withSuccess(['success'=>true]);

    }

    public  function find(Request $request)
    {

        $cb=session('current_boutique')->id;

        $pattern=$request->input('pattern');

        $p=Produit::with(['categorie','stock'=>function ($query) use($cb) {
            $query->where('id_boutique',$cb);
        }])->whereRaw('lower(libelle) LIKE ? OR lower(reference) LIKE ?',['%'.strtolower($pattern).'%','%'.strtolower($pattern).'%'])->orderBy('libelle')->limit(10)->get();
        $this->values['produits']=$p  ;
        $this->values['pattern']=$pattern  ;
        return response()->json($this->values);

    }

    public  function find_by_m(Request $request)
    {

        $magasin=$request->input('magasin');

        $pattern=$request->input('pattern');

        $p=Produit::with(['categorie','stock'=>function ($query) use($magasin) {
            $query->where('id_boutique',$magasin);
        }])->whereRaw('lower(libelle) LIKE ? OR lower(reference) LIKE ?',['%'.strtolower($pattern).'%','%'.strtolower($pattern).'%'])->orderBy('libelle')->limit(10)->get();
        $this->values['produits']=$p  ;
        $this->values['pattern']=$pattern  ;
        return response()->json($this->values);

    }



    }
