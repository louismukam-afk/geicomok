<?php

namespace GEICOM\Http\Controllers;

use Faker\Provider\DateTime;
use GEICOM\Facture;
use GEICOM\Stock;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->middleware('boutique');

        $this->values['big_title']='Management';

        $this->values['title']='Vue d\'ensemble';
    }


    public function index(){
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $this->values['boutique']=$cb;

        return view('management',$this->values);
    }

    public function getInfos(){
        $cb=session('current_boutique');
        $cbId=$cb->id;
        $s=Stock::with('produit')->where('id_boutique','=',$cbId)->where('quantite','>',0)->get();

            $dd=date('Y-01-01');
            $df=date('Y-12-31 23:59:59');



            $v=Facture::with('ventes')->where('id_boutique','=',$cbId)->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->get();
            $ventes=$v->sum('total');
            $year=intval(date('Y'));
            $month=intval(date('m'));
            $arr=[];
            $arr_months=[];
            $arr_ventes=[];

            $n=5;
            for ($i=4;$i>=0;$i--){
                $arr[$n-$i-1]=self::rightPeriod($year,$month,$i+1);
            }
            $arr[5]=[$month,$year];
        for ($i=0;$i<6;$i++){
            $d=new \DateTime($arr[$i][1].'-'.sprintf('%02d',$arr[$i][0]).'-01');
            $arr_months[$i]=self::getMonth($arr[$i][0]);
            $arr_ventes[$i]= Facture::with('ventes')->where('id_boutique','=',$cbId)->where('date_vente','>=',$d)->where('date_vente','<=',$d->format('Y-m-t'))->sum('total');

        }





        $this->values['months']=$arr_months;
        $this->values['ventesBM']=$arr_ventes;
        $this->values['stocks']=$s;
        $this->values['ventes']=$ventes;
        $this->values['factures']=$v;

        return response()->json($this->values);
    }

    public  static function rightPeriod($year,$month,$minus){
        $m=$month-$minus;
        if($m<1){
            $m=12-$m;
            $year--;
        }
        return [$m,$year];
    }
 public  static function getMonth($month){
       switch ($month){
           case 1: return 'Janvier';
           break;
        case 2: return 'Février';
           break;
        case 3: return 'Mars';
           break;
        case 4: return 'Avril';
           break;
        case 5: return 'Mai';
           break;
        case 6: return 'Juin';
           break;
        case 7: return 'Juillet';
           break;
        case 8: return 'Aout';
           break;
        case 9: return 'Septembre';
           break;
        case 10: return 'Octobre';
           break;
        case 11: return 'novembre';
           break;
        case 12: return 'Décembre';
           break;
           default: return null;
       }
    }

}
