<?php

namespace GEICOM\Http\Controllers\rapports;

use DateTime;
use GEICOM\EmailList;
use GEICOM\Livraison;
use GEICOM\Produit;
use PDF;
use GEICOM\Facture;
use GEICOM\Mail\Rapport0;
use GEICOM\Parametre;
use GEICOM\User;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class RapportController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->middleware('boutique');

        $this->values['big_title']='Rapports';

    }

    public function index(){
        return view('rapports.index',$this->values);
    }

    public function rapport_ventes(Request $request){

        $dd= $request->input('date_debut');
        $df= $request->input('date_fin');
        $cb=session('current_boutique');
        $cbId=$cb->id;

        if(!$dd ){
            $f=Facture::with('ventes.produit')->where('id_boutique','=',$cbId)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();
            $dd=$f->min('date_vente');
            $df=$f->max('date_vente');
        }else{
            if(!$df){
                $f=Facture::with('ventes.produit')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

            }
            else{
                $f=Facture::with('ventes.produit')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

            }
        }
        $this->values['title']='Rapports des ventes';
        $this->values['boutique']=$cb;
        $this->values['factures']=$f;
        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['param']=Parametre::all();
        return view('rapports.ventes',$this->values);
    }

    public function rapport_stocks(Request $request){
        $dd= $request->input('date_debut');
        $df= $request->input('date_fin');

        $cb=session('current_boutique');
        $cbId=$cb->id;

        if(!$dd ){
            $dd='1970-01-01';

        }
        if (!$df)
            $df=date('Y-m-d 23:59:59');


        $this->values['title']='Rapports des stocks';
        $this->values['boutique']=$cb;
        $this->values['dd']=$dd;
        $this->values['df']=$df;
        $this->values['param']=Parametre::all();
        return view('rapports.stocks',$this->values);

    }

    public function rapport_stocks_ajax(Request $request){
        $dd= $request->input('date_debut');
        $df= $request->input('date_fin');

        $cb=session('current_boutique');
        $cbId=$cb->id;

        if(!$dd ){
            $dd='1970-01-01';

        }
        if (!$df)
            $df=date('Y-m-d 23:59:59');


                $p=Produit::with(['stock'=>function ($query) use($cbId) {
                    $query->where('id_boutique',$cbId);
                },'ventes'=>function($q) use($dd,$df,$cbId){
                    $q->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->where('id_boutique',$cbId);
                },'achats'=>function($q) use($dd,$df,$cbId){
                    $q->where('date_achat','>=',$dd)->where('date_achat','<=',$df)->where('id_boutique',$cbId);
                }])->orderBy('libelle')->get();




        $this->values['produits']=$p;
        return response()->json($this->values);

    }

    public function send_rapport_stocks(Request $request)
    {
        $dd= $request->input('date_debut');
        $df= $request->input('date_fin');

        $cb=session('current_boutique');
        $cbId=$cb->id;

        $u=EmailList::all();
        if(count($u)>0){
            if(!$dd ){
                $dd='1970-01-01';

            }
            if (!$df)
                $df=date('Y-m-d 23:59:59');

                    $p=Produit::with(['stock'=>function ($query) use($cbId) {
                        $query->where('id_boutique',$cbId);
                    },'ventes'=>function($q) use($dd,$df,$cbId){
                        $q->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->where('id_boutique',$cbId);
                    },'achats'=>function($q) use($dd,$df,$cbId){
                        $q->where('date_achat','>=',$dd)->where('date_achat','<=',$df)->where('id_boutique',$cbId);
                    }])->orderBy('libelle')->get();



            $this->values['title']='Rapports des stocks';
            $this->values['produits']=$p;
            $this->values['boutique']=$cb;
            $this->values['dd']=$dd;
            $this->values['df']=$df;
            $this->values['param']=Parametre::all();
            //return view('rapports.stocks',$this->values);
            $pdf = PDF::loadView('rapports.stocks_pdf',$this->values);
            $name='rapport_stocks_'.((new DateTime($dd))->format('d-m-Y').'_'.(new DateTime($df))->format('d-m-Y')).'_.pdf';
            //return $pdf->stream($name);
            $pdf->save('pdf/'.$name);


            $users=[];
            $i=0;
            foreach ($u as $m)
            {
                $us=new User();
                $us->email=$m->email;
                $users[$i]=$us;
                $i++;
            }

            \Mail::to($users)->send((new Rapport0($name,'Rapport des stocks')));
            return response('ok');
        }
        return response()->json(['error'=>'Aucun récepteur n\'a été défini'],500);

    }


    public function send_rapport_ventes(Request $request)
    {
        $dd= $request->input('date_debut');
        $df= $request->input('date_fin');

        $cb=session('current_boutique');
        $cbId=$cb->id;

        $u=EmailList::all();
        if(count($u)>0){
            if(!$dd ){
                $f=Facture::with('ventes.produit')->where('id_boutique','=',$cbId)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();
                $dd=$f->min('date_vente');
                $df=$f->max('date_vente');
            }else{
                if(!$df){
                    $f=Facture::with('ventes.produit')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

                }
                else{
                    $f=Facture::with('ventes.produit')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();

                }
            }
            $this->values['title']='Rapports des ventes';
            $this->values['factures']=$f;
            $this->values['boutique']=$cb;
            $this->values['dd']=$dd;
            $this->values['df']=$df;
            $this->values['param']=Parametre::all();
            $pdf = PDF::loadView('rapports.ventes_pdf',$this->values);
            $name='rapport_ventes_'.((new DateTime($dd))->format('d-m-Y').'_'.(new DateTime($df))->format('d-m-Y')).'_.pdf';
            //return $pdf->stream($name);
            $pdf->save('pdf/'.$name);


            $users=[];
            $i=0;
            foreach ($u as $m)
            {
                $us=new User();
                $us->email=$m->email;
                $users[$i]=$us;
                $i++;
            }

            \Mail::to($users)->send((new Rapport0($name,'Rapport des ventes')));
            return response('ok');
        }


        return response()->json(['error'=>'Aucun récepteur n\'a été défini'],500);

    }
}
