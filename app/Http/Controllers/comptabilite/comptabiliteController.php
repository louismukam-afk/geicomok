<?php
namespace GEICOM\Http\Controllers\comptabilite;
use GEICOM\Http\Requests;
use GEICOM\Http\Controllers\Controller;
use GEICOM\Parametre;
use GEICOM\decaissement;
use GEICOM\Vente;
use Illuminate\Http\Request;
class comptabiliteController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('admin.comptabilite')]);
//        $this->renewSchoolYear();
    }

	public function index()
	{
	    $d=decaissement::all();
	  /*  dump($d);
	    die();*/
      /*$pe=payement::whereAnneeScolaire(\session('year'))->get();

        $total_e=0;
        foreach ($pe as $pay){
        $total_e+=$pay->montant;
        }


        $ps=payement_salaire::whereAnneeScolaire(\session('year'))->get();
*/
       /* $d=decaissement::all();




        $total_ps=0;
        /*foreach ($ps as $pay){
        $total_ps+=$pay->net_a_payer;
        }*/
       $ve=Vente::all();
        $total_ps=0;
        foreach ($ve as $pay){
        $total_ps+=$pay->total;
        }
       $total_d=0;
        foreach ($d as $pay){
            $total_d+=$pay->montant;
        }

        $values= array('sorties'=>$total_d,'entrees'=>$total_ps);
        return view('comptabilite.index',$values);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
