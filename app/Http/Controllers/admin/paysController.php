<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Pays;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class paysController extends Controller
{

    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion des Pays';
    }

    public function index()
    {
        $p=Pays::orderBy('nom')->get();
        $this->values['pays']=$p;
        return view('admin.pays',$this->values);
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'nom'=>'required|unique:pays'
        ]);
        $nom=$request->input('nom');
        $p=new Pays();
        $p->nom=$nom;
        $p->save();

        return redirect()->route('pays_management')->withSuccess(['ok'=>'']);

    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'id'=>'required|numeric',
            'nom'=>'required|unique:pays'
            ]);
        $id=$request->input('id');
        $nom=$request->input('nom');
        $p=Pays::find($id);
        $p->nom=$nom;
        $p->save();
        return redirect()->route('pays_management')->withSuccess(['ok'=>'']);
    }
    public function destroy($id)
    {
        Pays::destroy($id);
        return redirect()->route('pays_management')->withSuccess(['ok'=>'']);


    }
    public function destroys(Request $request)
    {
        $idList=$request->input('check');

        Pays::destroy($idList);
        return response()->json('OK');



    }

}
