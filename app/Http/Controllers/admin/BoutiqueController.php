<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Boutique;
use GEICOM\Produit;
use GEICOM\Stock;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class BoutiqueController extends Controller
{

    protected $values=[];

    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion des boutiques';
    }
    public  function index()
    {
        $p=Boutique::orderBy('nom')->get();
        $this->values['boutiques']=$p  ;
        return view('admin.boutique',$this->values);
    }

    public  function store(Request $request)
    {
        $this->validate($request,[
            'nom'=>'required|unique:boutiques',
            'type'=>'required|numeric',
        ]);
        $nom=$request->input('nom');
        $adresse=$request->input('adresse');
        $bp=$request->input('boite_postale');
        $tel=$request->input('telephone');
        $email=$request->input('email');
        $type=$request->input('type');


        $p=new Boutique();
        $p->nom=$nom;
        $p->adresse=$adresse;
        $p->boite_postale=$bp;
        $p->telephone=$tel;
        $p->email=$email;
        $p->active=1;
        $p->type=$type;

        $p->save();

        $p_array=Produit::get()->pluck('id');

        $i_arr=[];
        $i=0;
        $date=date('Y-m-d H:i:s');

        foreach ($p_array as $pId){
            $i_arr[$i]=['id_produit'=>$pId,'id_boutique'=>$p->id,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);


        return redirect()->route('boutique_management')->withSuccess(['ok'=>'']);
    }
    public function update(Request $request)
    {
        $this->validate($request, [

            'id'=>'required|numeric',
            'nom'=>'required',
            'type'=>'required|numeric',
            'active'=>'required|numeric',

        ]);

        $id=$request->input('id');
        $nom=$request->input('nom');
        $adresse=$request->input('adresse');
        $bp=$request->input('boite_postale');
        $tel=$request->input('telephone');
        $email=$request->input('email');
        $type=$request->input('type');
        $active=$request->input('active');

        $p=Boutique::find($id);
        $p->nom=$nom;
        $p->adresse=$adresse;
        $p->boite_postale=$bp;
        $p->telephone=$tel;
        $p->email=$email;
        $p->active=$active;
        $p->type=$type;

        $p->save();
        return redirect()->route('boutique_management')->withSuccess(['ok' => '']);
    }


    public function destroy($id)
    {
        Boutique::destroy($id);
        return redirect()->route('boutique_management')->withSuccess(['ok'=>'']);


    }
    public function destroys(Request $request)
    {
        $idList = $request->input('check');

        Boutique::destroy($idList);
        return response()->json('OK');
    }

    public function getList(){
        $b=Boutique::where('type','=',1)->orderBy('nom')->get();
        $m=Boutique::where('type','=',0)->orderBy('nom')->get();
        $this->values['boutiques']=$b  ;
        $this->values['magasins']=$m  ;
        return response()->json($this->values);
    }

    public function setBoutique(Request $request){
        $id=$request->input('id');

        $b=Boutique::find($id);
        if($b){
            session(['current_boutique'=>$b]);
        }
        return redirect()->route('home');
    }




}
