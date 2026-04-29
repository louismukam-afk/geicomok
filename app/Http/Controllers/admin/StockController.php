<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Categorie;
use GEICOM\Produit;
use GEICOM\securite;
use GEICOM\Stock;
use GEICOM\Usage;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
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
        $this->values['big_title']='Administration';



        $this->values['title']='Gestion manuelle des stocks';
    }


    public function index()
    {
        return view('admin.stock',$this->values);
    }
    public function index_securite()
    {
        $p=Produit::with('categorie')->orderBy('libelle')->paginate(200);
        $c=Categorie::all();
        $this->values['produit']=$p  ;
        $this->values['categories']=$c;
        return view('admin.securite',$this->values);
    }

    public function stock_minimum()
    {
        $p1=Produit::with('securite')->orderBy('libelle')->paginate(200);
      /*  dump($p);
        die();*/

       /* $p = Produit::select('produits.*', 'stocks.quantite', 'securites.stock_minimum')
            ->leftJoin('stocks', 'stocks.id_produit', '=', 'produits.id')
            ->leftJoin('securites', 'securites.id_produit', '=', 'produits.id')
            ->orderBy('produits.libelle')
            ->paginate(200);*/
        $p = Produit::select(
            'produits.*',
            DB::raw('MAX(stocks.quantite) as quantite'),
            DB::raw('MAX(securites.stock_minimum) as stock_minimum')
        )
            ->leftJoin('stocks', 'stocks.id_produit', '=', 'produits.id')
            ->leftJoin('securites', 'securites.id_produit', '=', 'produits.id')
            ->groupBy('produits.id')
            ->orderBy('produits.libelle')
            ->paginate(200);
        $nbCommander = $p->filter(function($p) {
            $sec = $p->securite->first();
            $stockMin = $sec ? $sec->stock_minimum : 0;
            $quantite = isset($p->quantite) ? $p->quantite : 0;
            return $quantite <= $stockMin;
        })->count();
        $securite=securite::all();
        $c=Categorie::all();
        $this->values['categories']=$c;

        $this->values['nbCommander']=$nbCommander  ;
        $this->values['produits']=$p1  ;
        $this->values['produit']=$p  ;
        $this->values['securite']=$securite;
        return view('admin.minimum',$this->values);
    }
    public function index_securite1()
    {
        $p=Produit::with('categorie')->orderBy('libelle')->paginate(200);
        $c=Categorie::all();
        $this->values['produit']=$p  ;
        $this->values['categories']=$c;
        return view('admin.index_stock',$this->values);
    }


    public function get_stock_admin_securite()
    {



        $cb=session('current_boutique')->id;

        $cs_pArray=Stock::where('id_boutique','=',$cb)->get()->pluck('id_produit')->toArray();
        $p_array=Produit::whereNotIn('id',$cs_pArray)->get()->pluck('id');

        $i_arr=[];
        $i=0;
        $date=date('Y-m-d H:i:s');

        foreach ($p_array as $pId){
            $i_arr[$i]=['id_produit'=>$pId,'id_boutique'=>$cb,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);

        $p=Produit::with(['stock'=>function ($query) use($cb) {
            $query->where('id_boutique',$cb);
        }])->orderBy('libelle')->get();

        $this->values['produits']=$p;
        return response()->json($this->values);
    }


     public function get_stock_admin()
    {



        $cb=session('current_boutique')->id;

        $cs_pArray=Stock::where('id_boutique','=',$cb)->get()->pluck('id_produit')->toArray();
        $p_array=Produit::whereNotIn('id',$cs_pArray)->get()->pluck('id');

        $i_arr=[];
        $i=0;
        $date=date('Y-m-d H:i:s');

        foreach ($p_array as $pId){
            $i_arr[$i]=['id_produit'=>$pId,'id_boutique'=>$cb,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);

        $p=Produit::with(['stock'=>function ($query) use($cb) {
            $query->where('id_boutique',$cb);
        }])->orderBy('libelle')->get();

        $this->values['produits']=$p;
        return response()->json($this->values);
    }
    public function update_securite1(Request $request)
    {
        $this->validate($request, [
            'id_produit'    => 'required|numeric',
            'stock_minimum' => 'required|numeric',
            'observation'   => 'required',
        ]);

        $cb   = session('current_boutique')->id;
        $user = \Auth::user()->id;
        $id   = $request->input('id_produit');

        $stock_minimum = $request->input('stock_minimum');
        $observation   = $request->input('observation');

        // Cherche l'enregistrement par id_produit
        $securite = Securite::where('id_produit', $id)->first();

        if (!$securite) {
            // Si aucun enregistrement trouvé, on en crée un
            $securite = new Securite();
            $securite->id_produit  = $id;
            $securite->id_boutique = $cb;
        }

        $securite->stock_minimum = $stock_minimum;
        $securite->observation   = $observation;
        $securite->id_user       = $user;
        $securite->save();

        return redirect()->route('stock_minimum_management')
            ->withSuccess(['ok' => 'Stock de sécurité mis à jour']);
    }


  /*  public function update_securite(Request $request)
    {
        $this->validate($request, [

            'id_produit'=>'required|numeric',
        ]);
        $cb=session('current_boutique')->id;
        $user=\Auth::user()->id;
       $id=$request->input('id_produit');
        $stock_minimum = $request->input('stock_minimum');
        $observation = $request->input('observation');
//        $p=Produit::find($id);
        /*dump($p);
        die();*/
       /* $securite=new securite();
        $securite->id_produit = $id;
        $securite->stock_minimum = $stock_minimum;
        $securite->observation = $observation;
        $securite->id_boutique = $cb;
        $securite->id_user = $user;

        $securite->save();
        return redirect()->route('stock_minimum_management')->withSuccess(['ok' => '']);
    }*/
    public function update_securite(Request $request)
    {
        $this->validate($request, [
            'id_produit' => 'required|numeric',
        ]);

        $cb = session('current_boutique')->id;
        $user = \Auth::user()->id;
        $id = $request->input('id_produit');
        $stock_minimum = $request->input('stock_minimum');
        $observation = $request->input('observation');

        // Vérifier si une sécurité existe déjà pour ce produit
        $existant = securite::where('id_produit', $id)
            ->where('id_boutique', $cb)
            ->first();

        if ($existant) {
            // Supprimer l'ancien enregistrement
            $existant->delete();
        }

        // Créer et enregistrer le nouveau
        $securite = new securite();
        $securite->id_produit = $id;
        $securite->stock_minimum = $stock_minimum;
        $securite->observation = $observation;
        $securite->id_boutique = $cb;
        $securite->id_user = $user;
        $securite->save();

        return redirect()->route('stock_minimum_management')->withSuccess(['ok' => 'Sécurité mise à jour avec succès.']);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $cb=session('current_boutique')->id;

        $this->validate($request,[
            'id'=>'required|numeric',
            'operation'=>'numeric|required',
            'quantite'=>'numeric|required'
        ]);
        $id=$request->input('id');
        $op=$request->input('operation');
        $quantite=$request->input('quantite');
        $s=Stock::with('produit')->find($id);

        $u=new Usage();
        $u->id_produit=$s->produit->id;
        $u->id_boutique=$cb;
        $u->date_utilisation=date('Y-m-d H:i');

        if($op==1)
        {
            $s->quantite+=$quantite;
            $u->details="Quantité ajoutée manuellement = ".$quantite;



        }else
        {
            if($quantite<=($s->quantite))
            {
                $s->quantite-=$quantite;
                $u->details="Quantité retirée manuellement = ".$quantite;

            }else
            {
                return redirect()->route('stock_management')->withErrors(['bad_quantity'=>'Soustraction impossible.Quantité trop petite']);

            }
        }
        $u->quantite=$quantite;
        $u->stock=$s->quantite;


        $s->save();
        $u->save();

        return redirect()->route('stock_management')->withSuccess(['ok'=>'']);
    }

    public function update_ajax(Request $request)
    {
        $cb=session('current_boutique')->id;

        $val=Validator::make($request->all(),[
            'id'=>'required|numeric',
            'operation'=>'numeric|required',
            'quantite'=>'numeric|required'
        ]);

        if($val->fails())
        {
            return response()->json($val->errors()[0],400);
        }
        $id=$request->input('id');
        $op=$request->input('operation');
        $quantite=$request->input('quantite');
        $s=Stock::with('produit')->find($id);

        $u=new Usage();
        $u->id_produit=$s->produit->id;
        $u->id_boutique=$cb;
        $u->date_utilisation=date('Y-m-d H:i');

        if($op==1)
        {
            $s->quantite+=$quantite;
            $u->details="Quantité ajoutée manuellement = ".$quantite;



        }else
        {
            if($quantite<=($s->quantite))
            {
                $s->quantite-=$quantite;
                $u->details="Quantité retirée manuellement = ".$quantite;

            }else
            {
                return response()->json(['Soustraction impossible.Quantité trop petite'],400);

            }
        }
        $u->quantite=$quantite;
        $u->stock=$s->quantite;


        $s->save();
        $u->save();

        return response()->json(['stock'=>$s]);

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
