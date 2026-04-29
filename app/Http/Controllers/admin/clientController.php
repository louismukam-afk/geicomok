<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Client;
use GEICOM\Pays;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class clientController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion des Clients';
    }

    public function index()
    {
        $c=Client::orderBy('nom')->get();
        $pa=Pays::orderBy('nom')->get();
        $this->values['pays']=$pa;
        $this->values['client']=$c;
        return view('admin.client',$this->values);
    }
    public function store1(Request $request)
    {
        $this->validate($request,[
            'nom'=>'required',

        ]);
        $nom=$request->input('nom');
        $tel=$request->input('telephone');
        $vil=$request->input('ville');
        $id_p=$request->input('id_pays');
        $ad=$request->input('adresse');
        $bp=$request->input('boite_postale');
        $em=$request->input('email');
        $c=new Client();
        $c->nom=$nom;
        $c->telephone=$tel;
        $c->ville=$vil;
        if ($id_p)
            $c->id_pays=$id_p;
        $c->adresse=$ad;
        $c->boite_postale=$bp;
        $c->email=$em;
        $c->save();
        return redirect()->route('new_vente')->withSuccess(['ok'=>'']);

    }



    public function store(Request $request)
    {
        $this->validate($request,[
           'nom'=>'required',

        ]);
        $nom=$request->input('nom');
        $tel=$request->input('telephone');
        $vil=$request->input('ville');
        $id_p=$request->input('id_pays');
        $ad=$request->input('adresse');
        $bp=$request->input('boite_postale');
        $em=$request->input('email');
        $c=new Client();
        $c->nom=$nom;
        $c->telephone=$tel;
        $c->ville=$vil;
        if ($id_p)
        $c->id_pays=$id_p;
        $c->adresse=$ad;
        $c->boite_postale=$bp;
        $c->email=$em;
        $c->save();
        return redirect()->route('client_management')->withSuccess(['ok'=>'']);

    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'nom'=>'required',


        ]);
        $id=$request->input('id');
        $nom=$request->input('nom');
        $tel=$request->input('telephone');
        $vil=$request->input('ville');
        $id_p=$request->input('id_pays');
        $ad=$request->input('adresse');
        $bp=$request->input('boite_postale');
        $em=$request->input('email');
        $c=Client::find($id);
        $c->nom=$nom;
        $c->telephone=$tel;
        $c->ville=$vil;
        if ($id_p)
        $c->id_pays=$id_p;
        $c->adresse=$ad;
        $c->boite_postale=$bp;
        $c->email=$em;
        $c->save();
        return redirect()->route('client_management')->withSuccess(['ok'=>'']);

    }
    public function destroy($id)
    {
        Client::destroy($id);
        return redirect()->route('client_management')->withSuccess(['ok'=>'']);
    }
    public function destroys(Request $request)
    {
        $idlist=$request->input('check');
        Client::destroy($idlist);
        return response()->json('OK');
    }
}
