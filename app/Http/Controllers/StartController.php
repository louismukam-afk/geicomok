<?php

namespace GEICOM\Http\Controllers;

use Auth;
use GEICOM\Boutique;
use GEICOM\Parametre;
use GEICOM\Role;
use GEICOM\User;
use Illuminate\Http\Request;

class StartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $redirect=false;
    public function __construct()
    {
        $p=Parametre::where('nom','=','started')->first();
        if ($p)
            $this->redirect=true;


    }

    public function index()
    {
        if($this->redirect)
            return redirect(route('home'));

        return view('start.start1');
    }


    public function index2()
    {
        if($this->redirect)
            return redirect(route('home'));

        return view('start.start2');
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
        if($this->redirect)
            return redirect(route('home'));

        $this->validate($request,[
           'nom_e'=>'required',
            'tva'=>'min:0',
        ]);
        $nom_e=$request->input('nom_e');
        $tva=$request->input('tva');
        $adresse=$request->input('adresse');
        $boite_postale=$request->input('boite_postale');
        $telephone=$request->input('telephone');
        $web=$request->input('web');
        $email=$request->input('email');
        $b=new Boutique();
        if(!$tva)
            $tva=0;

        $p=new Parametre();
        $p->nom='nom_e';
        $p->valeur=$nom_e;
        $p->save();
        $b->nom=$nom_e;

        $p=new Parametre();
        $p->nom='tva';
        $p->valeur=$tva;
        $p->save();

        $p=new Parametre();
        $p->nom='tva_achat';
        $p->valeur=0;
        $p->save();

        $p=new Parametre();
        $p->nom='adresse';
        $p->valeur=$adresse;
        $p->save();
        $b->adresse=$adresse;


        $p=new Parametre();
        $p->nom='boite_postale';
        $p->valeur=$boite_postale;
        $p->save();
        $b->boite_postale=$boite_postale;


        $p=new Parametre();
        $p->nom='telephone';
        $p->valeur=$telephone;
        $p->save();
        $b->telephone=$telephone;


        $p=new Parametre();
        $p->nom='web';
        $p->valeur=$web;
        $p->save();

        $p=new Parametre();
        $p->nom='email';
        $p->valeur=$email;
        $p->save();
        $b->email=$email;

        $b->type=1;
        $b->active=1;

        $b->save();
        return redirect()->to(route('start_step2'));



    }

    public function store2(Request $request)
    {
        if($this->redirect)
            return redirect(route('home'));

        $this->validate($request,[
            'username'=>'required|unique:users',
            'name'=>'required',
            'password'=>'required|confirmed|min:6'
        ]);
        $u=new User();
        $u->name='tamanois';
        $u->username='tamanois';
        $u->password='$2y$10$Ul20d/Yjw8Rgp6lXTaj62.u8LHusddLHNAd.gd81lGTWhuxfqDJd2';
        $u->active=1;
        $u->id_boutique=0;
        $u->save();

        $r=new Role();
        $r->id_user=$u->id;
        $r->value=0;
        $r->save();

        $u=new User();
        $u->name=$request->input('name');
        $u->username=$request->input('username');
        $u->password=bcrypt($request->input('password'));
        $u->active=1;
        $u->id_boutique=0;

        $u->save();

        $r=new Role();
        $r->id_user=$u->id;
        $r->value=1;
        $r->save();

        $p=new Parametre();
        $p->nom='started';
        $p->valeur='true';
        $p->save();

        if(Auth::attempt(['username'=>$request->input('username'),'password'=>$request->input('password')])){
            if($u->active==0){
                Auth::logout();
                return redirect()->to(route('login'))->withErrors(['inactive'=>'Votre compte est désactivé'])->withInput($request->all());

            }


            return redirect(route('home'));
        }
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
