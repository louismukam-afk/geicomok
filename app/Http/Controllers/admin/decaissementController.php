<?php namespace GEICOM\Http\Controllers\admin;

use GEICOM\ligne_budgetaires;
use GEICOM\Caisse;
use GEICOM\decaissement;
use GEICOM\Http\Controllers\Controller;

use GEICOM\Personnel;
use GEICOM\MouvementCaisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class decaissementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {
        session(['big_title'=>trans('Comptabilite')]);
//        $this->renewSchoolYear();
    }

	public function index(Request $req)
	{
        $values= array('titre'=>trans('admin.sortie_caisse'));

        if(session('success')){

            $values['success']=session('success');
            Session::forget('success');
        }
        $pattern=$req->input('search');
            $d=decaissement::with(['Personnel', 'ligne_budgetaire', 'categorie_budgetaire'])
                ->join('Personnel', 'id_personnel', '=', 'Personnel.id')
                ->whereRaw(' lower(nom) LIKE ?', ['%' . strtolower($pattern) . '%'])
                ->select(['decaissements.*', 'Personnel.nom as nom_personnel'])
                ->orderBy('created_at','desc')
                ->paginate(30);

        $li=ligne_budgetaires::orderBy('date_fin', 'desc')->get();
        $p=Personnel::orderBy('nom')->get();
        $values['personnel']=$p;
        $values['ligne']=$li;
        $values['decaissement']=$d;
        $values['caisses_sortie']=Caisse::where('type', Caisse::TYPE_SORTIE)
            ->where('active', 1)
            ->whereHas('users', function ($query) {
                $query->where('users.id', \Auth::user()->id);
            })
            ->orderBy('nom')
            ->get();

        return view('admin.search_decaissement',$values);
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
	public function store(Request $request)
	{
		$this->validate($request,[
		   'personnel'=>'required|numeric',
            'montant'=>'required|numeric',
            'motif'=>'required',
            'ligne_budgetaire'=>'required',
            'categorie_budgetaire'=>'required',
            'id_caisse'=>'required|numeric'
        ]);
		
		$d=new decaissement();
		$d->id_personnel=$request->input('personnel');
        $d->montant=$request->input('montant');
        $caisse=Caisse::find($request->input('id_caisse'));
        if(!$caisse || $caisse->solde() < $d->montant) {
            return redirect()->to(\URL::previous())->withErrors(['solde'=>'Solde insuffisant dans la caisse de sortie']);
        }
        $d->date=$request->input('date');
        $d->motif=$request->input('motif');
        $d->id_ligne_budgetaire=$request->input('ligne_budgetaire');
        $d->id_categorie_budgetaire=$request->input('categorie_budgetaire');
        $d->id_caisse=$request->input('id_caisse');
       // $d->date=date('Y-m-d');

        $current_user = \Auth::user();
        $d->id_creator = $current_user->id;
        $d->id_last_editor = $current_user->id;
		$d->save();
        MouvementCaisse::enregistrer($d->id_caisse, 'sortie', $d->montant, 'decaissement', $d->id, 'Sortie caisse: '.$d->motif, $d->date.' '.date('H:i:s'));

        session(['success'=>true]);
        return redirect()->action('admin\decaissementController@index');
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
	public function update_amount(Request $request)
	{
		$this->validate($request, [
		    'id' => 'required|numeric',
		    'montant' => 'required|numeric',
        ]);
		$id = $request->input('id');
		$montant = $request->input('montant');
		$d =decaissement::find($id);
		if($d) {
		    $d->montant = $montant;
            $current_user = \Auth::user();
            $d->id_last_editor = $current_user->id;
		    $d->save();
        }
        session(['success'=>true]);
		return redirect()->to(URL::previous());
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
