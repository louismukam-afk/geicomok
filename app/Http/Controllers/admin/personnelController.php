<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Pays;
use GEICOM\Personnel;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class personnelController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion du personnel';
    }

    public function index()
    {
        $p=Personnel::orderBy('nom')->get();
        $pa=Pays::orderBy('nom')->get();
        $this->values['pays']=$pa;
        $this->values['personnel']=$p;
        return view('admin.personnel',$this->values);
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'nom'=>'required',
            'telephone'=>'required',
            'id_pays'=>'required',
            'date_entree'=>'required',
            'email'=>'email',
        ]);


        $nom=$request->input('nom');
        $date=$request->input('date_nais');
        $lieu=$request->input('lieu_nais');
        $id_pays=$request->input('id_pays');
        $sexe=$request->input('sexe');
        $tel=$request->input('telephone');
        $date_t=$request->input('date_entree');
        $add=$request->input('addresse');
        $autres=$request->input('autres');
        $email=$request->input('email');
        $p=new Personnel();
        $p->nom=$nom;
        $p->date_naiss=$date;
        $p->lieu_naiss=$lieu;
        $p->sexe=$sexe;
        $p->id_pays=$id_pays;
        $p->telephone=$tel;
        $p->date_entree=$date_t;
        $p->addresse=$add;
        $p->autres=$autres;
        $p->email=$email;
        $p->save();
        return redirect()->route('personnel_management')->withSuccess(['ok'=>'']);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'id'=>'required|numeric',
            'nom'=>'required',
            'telephone'=>'required',
            'id_pays'=>'required',
            'date_entree'=>'required',
            'email'=>'email',

        ]);
        $id=$request->input('id');
        $nom=$request->input('nom');

        $id_pays=$request->input('id_pays');
        $sexe=$request->input('sexe');
        $tel=$request->input('telephone');
        $date_t=$request->input('date_entree');
        $add=$request->input('addresse');
        $autres=$request->input('autres');


        $p=Personnel::find($id);
        $p->nom=$nom;

        $p->sexe=$sexe;
        $p->id_pays=$id_pays;
        $p->telephone=$tel;
        $p->date_entree=$date_t;
        $p->addresse=$add;
        $p->autres=$autres;

        $p->save();
        return redirect()->route('personnel_management')->withSuccess(['ok'=>'']);
    }
    public function destroy($id)
    {
        Personnel::destroy($id);
        return redirect()->route('personnel_management')->withSuccess(['ok'=>'']);
    }
    public function destroys(Request $request)
    {
        $idlist=$request->input('check');
        Personnel::destroy($idlist);
        return response()->json('OK');
    }
}
