<?php

namespace GEICOM\Http\Controllers;

use GEICOM\EmailList;
use GEICOM\Log;
use GEICOM\Parametre;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

    }

    public function index_parametres(){
        $p=Parametre::all();


        $this->values['title']='Paramètres';
        $this->values['p']=$p;
        return view('admin.parametres',$this->values);
    }

    public function store_parametres(Request $request){
        $nom_e=$request->input('nom_e');
        $tva=$request->input('tva');
        $tva_a=$request->input('tva_achat');

        $adresse=$request->input('adresse');
        $boite_postale=$request->input('boite_postale');
        $telephone=$request->input('telephone');
        $web=$request->input('web');
        $email=$request->input('email');

        if($nom_e){
            $p=Parametre::where('nom','=','nom_e')->first();
            $p->valeur=$nom_e;
            $p->save();
        }


        if(is_numeric($tva)){
            $p=Parametre::where('nom','=','tva')->first();
            $p->valeur=$tva;
            $p->save();
        }

        $tva_a=floatval($tva_a);
            $p=Parametre::where('nom','=','tva_achat')->first();
            if($p){
                $p->valeur=$tva_a;
            }
            else
            {

                $p=new Parametre();
                $p->nom='tva_achat';
                $p->valeur=$tva_a;
            }
            $p->save();


        if($adresse){
            $p=Parametre::where('nom','=','adresse')->first();
            $p->valeur=$adresse;
            $p->save();
        }

        if($boite_postale)
        {
            $p=Parametre::where('nom','=','boite_postale')->first();
            $p->valeur=$boite_postale;
            $p->save();
        }

        if($telephone){
            $p=Parametre::where('nom','=','telephone')->first();
            $p->valeur=$telephone;
            $p->save();
        }

        if($web){
            $p=Parametre::where('nom','=','web')->first();
            $p->valeur=$web;
            $p->save();
        }

        if($email){
            $p=Parametre::where('nom','=','email')->first();
            $p->valeur=$email;
            $p->save();
        }


        return redirect()->to(\URL::previous());

    }

    public function index_email()
    {
        $e=EmailList::orderBy('email')->get();
        $this->values['emails_list']=$e;
        $this->values['title']='Emails de contact';
        return view('admin.emails',$this->values);


    }

    public function store_email(Request $request)
    {
        $this->validate($request,[
           'email'=>'email|required|unique:email_lists'
        ]);
        $email=$request->input('email');
        $e= new EmailList();
        $e->email=$email;
        $e->save();
        return redirect()->to(\URL::previous());

    }

    public function  destroy_email($id)
    {
        EmailList::destroy($id);
        return redirect()->to(\URL::previous());

    }

    public static function log($message){
        return Log::enregistrer('operation', $message, request());
    }
}
