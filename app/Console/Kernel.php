<?php

namespace GEICOM\Console;

use DateTime;
use GEICOM\EmailList;
use GEICOM\Facture;
use GEICOM\Mail\Rapport1;
use GEICOM\Parametre;
use GEICOM\Produit;
use GEICOM\RapportState;
use GEICOM\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use PDF;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

protected $values;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function(){
            $rs=RapportState::where('state','=',1)->orderBy('created_at','desc')->first();
            if(!$rs){
                $dd=$df=date('Y-m-d');
                $ras=new RapportState();
                $ras->date_rapport=$dd;
                $ras->save();
            }
            else{
                if($rs->date_rapport==date('Y-m-d'))
                {
                  return 0;
                }

                $dt=(new \DateTime($rs->date_rapport))->add(new \DateInterval('P1D'));
                $dd=$dt->format('Y-m-d');

                $df=date('Y-m-d');
                $ras=new RapportState();
                $ras->date_rapport=$dd;
                $ras->save();


            }




            $u=EmailList::all();
            if(count($u)>0){
                $f=Facture::with('ventes.produit')->where('date_vente','>=',$dd)->where('date_vente','<=',$df)->orderBy('date_vente','desc')->orderBy('created_at','desc')->get();
                $this->values['title']='Rapports des ventes';
                $this->values['factures']=$f;
                $this->values['dd']=$dd;
                $this->values['df']=$df;
                $this->values['param']=Parametre::all();
                $pdf = PDF::loadView('rapports.ventes_pdf_kernel',$this->values);
                $name_ventes='rapport_ventes_'.((new DateTime($dd))->format('d-m-Y').'_'.(new DateTime($df))->format('d-m-Y')).'_.pdf';
                //return $pdf->stream($name);
                $pdf->save('public/pdf/'.$name_ventes);


                //stocks

                $p=Produit::with(['stock','ventes'=>function($q) use($dd,$df){
                    $q->where('date_vente','>=',$dd)->where('date_vente','<=',$df);
                },'achats'=>function($q) use($dd,$df){
                    $q->where('date_achat','>=',$dd)->where('date_achat','<=',$df);
                }])->orderBy('libelle')->get();

                $this->values['title']='Rapports des stocks';
                $this->values['produits']=$p;
                //return view('rapports.stocks',$this->values);
                $pdf = PDF::loadView('rapports.stocks_pdf_kernel',$this->values);
                $name_stocks='rapport_stocks_'.((new DateTime($dd))->format('d-m-Y').'_'.(new DateTime($df))->format('d-m-Y')).'_.pdf';
                //return $pdf->stream($name);
                $pdf->save('public/pdf/'.$name_stocks);

                $users=[];
                $i=0;
                foreach ($u as $m)
                {
                    $us=new User();
                    $us->email=$m->email;
                    $users[$i]=$us;
                    $i++;
                }
                $name[0]=$name_ventes;
                $name[1]=$name_stocks;


                \Mail::to($users)->send((new Rapport1($name,'Rapports du magasin')));
                $ras->state=1;
                $ras->save();
            }
            ////Ventes


        })->cron('* * * * * *');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
