<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Personnel;
use GEICOM\Salaire;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class salaireController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion des Salaires';
    }

    public function index()
    {
        $s1=Salaire::all();
        $arr=[];
        foreach ($s1 as $s)
        {
            array_push($arr,$s->id_personnel);
        }
        $p=Personnel::all();
        $p1=Personnel::whereNotIn('id',$arr)->get();
        $this->values['personnel']=$p;
        $this->values['salaire']=$s1;
        $this->values['personnel1']=$p1;
        return view('admin.salaire',$this->values);
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'montant'=>'required|',
            'id_personnel'=>'required'
        ]);
        $nom=$request->input('montant');
        $id_pays=$request->input('id_personnel');
        $p=new Salaire();
        $p->montant=$nom;
        $p->id_personnel=$id_pays;
        $p->save();
        return redirect()->route('salaire_management')->withSuccess(['ok'=>'']);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'montant'=>'required|',
            'id_personnel'=>'required',
            'id'=>'required|numeric'
        ]);
        $id=$request->input('id');
        $nom=$request->input('montant');
        $id_pays=$request->input('id_personnel');
        $p=Salaire::find($id);
        $p->montant=$nom;
        $p->id_personnel=$id_pays;
        $p->save();
        return redirect()->route('salaire_management')->withSuccess(['ok'=>'']);
    }

    public function destroy($id)
    {
        Salaire::destroy($id);
        return redirect()->route('salaire_management')->withSuccess(['ok'=>'']);
    }
    public function destroys(Request $request)
    {
        $idlist=$request->input('check');
        Salaire::destroy($idlist);
        return response()->json('OK');
    }

}
