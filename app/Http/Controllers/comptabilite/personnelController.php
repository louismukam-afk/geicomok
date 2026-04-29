<?php namespace gesecole\Http\Controllers\personnel;

use gesecole\fonction;
use gesecole\fonction_personnel;
use gesecole\Http\Requests;
use gesecole\Http\Controllers\Controller;
use gesecole\personnel;
use gesecole\parametres;
use gesecole\titulaire;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class personnelController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function __construct()
    {

        session(['big_title'=>trans('menu.gestion_personnel')]);
        $this->renewSchoolYear();
    }
	public function index()
	{
        return view('personnel.index');
	}

	 public function index_personnel(){
         $titre=trans('admin.personnel');
         $m=personnel::orderBy('nom')
         ->paginate(1000)

         ;
         $values= array('titre'=>$titre,'personnel'=>$m);
            if(session('success')){
                $values['success']=true;
                Session::forget('success');
            }


         return view('personnel.personne',$values);
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
	public function store(Request $req)
	{
        $this->validate($req,[
            'nom'=>'required',
            'date_naiss'=>'required|date',
            'lieu_naiss'=>'required',
            'nationalite'=>'required',
            'sexe'=>'required',
            'tel1'=>'required',
            'adresse'=>'required',
            'type'=>'required|numeric',
            'date_rec'=>'date',


        ]);
        $date_rec=$req->input('date_rec');

        $c=new personnel();
        $c->nom=$req->input('nom');
        $c->nationalite=$req->input('nationalite');
        $c->adresse=$req->input('adresse');
        $c->sexe=$req->input('sexe');
        $c->diplome=$req->input('diplome');
        $c->tel1=$req->input('tel1');
        $c->tel2=$req->input('tel2');
        $c->date_naiss=$req->input('date_naiss');
        $c->lieu_naiss=$req->input('lieu_naiss');
        if($date_rec==null)
            $c->date_entree=date('Y-m-d');
        else
            $c->date_entree=$date_rec;
        $c->email=$req->input('email');
        $c->type=$req->input('type');
        $c->matieres=$req->input('matieres');
        $c->autre_information=$req->input('autre_information');
        $c->num_contribuable=$req->input('num_contribuable');
        $c->num_ss=$req->input('num_ss');
        $c->categorie=$req->input('categorie');
        $c->echelon=$req->input('echelon');

        $c->save();

        $param = parametres::all();
        $pa = $param->where('nom', 'initiales_etablissement')->first();

        $front = session('year');
        $front = substr($front, 2, 2);
        $number = sprintf('%03d', $c->id);
        $c->matricule = $front . $pa->valeur .'P'.$number;
        $c->save();

        session(['success'=>true]);
        return redirect()->action('personnel\personnelController@index_personnel');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
        $titre=trans('admin.search_personnel');
        $values= array('titre'=>$titre);
        return view('personnel.search_personnel',$values);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $titre=trans('admin.infos_personnel');
        $p=personnel::find($id);
        $f2=fonction::all();
        $fp=fonction_personnel::whereIdPersonnel($id)->get();
        $t=titulaire::whereAnneeScolaire(\session('year'))->where('id_personnel','=',$id)->get()->first();
        $values= array('titre'=>$titre,'personnel'=>$p,'fonctions'=>$fp,'titulaire'=>$t,'fonction'=>$f2);

        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }

        return view('personnel.edit_personnel',$values);

    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $req,$id)
	{
        $this->validate($req,[
            'nom'=>'required',
            'date_naiss'=>'required',
            'lieu_naiss'=>'required',
            'sexe'=>'required',
            'nationalite'=>'required',
            'tel1'=>'required',
            'adresse'=>'required',
            'type'=>'required|numeric',
            'date_rec'=>'date',


        ]);
        $date_rec=$req->input('date_rec');


        $c=personnel::find($id);
        $c->nom=$req->input('nom');
        $c->nationalite=$req->input('nationalite');
        $c->adresse=$req->input('adresse');
        $c->sexe=$req->input('sexe');
        $c->diplome=$req->input('diplome');
        if($date_rec==null)
        {}
        else
            $c->date_entree=$date_rec;
        $c->tel1=$req->input('tel1');
        $c->tel2=$req->input('tel2');
        $c->date_naiss=$req->input('date_naiss');
        $c->lieu_naiss=$req->input('lieu_naiss');
        $c->email=$req->input('email');
        $c->type=$req->input('type');
        $c->matieres=$req->input('matieres');
        $c->autre_information=$req->input('autre_information');
        $c->num_contribuable=$req->input('num_contribuable');
        $c->num_ss=$req->input('num_ss');
        $c->categorie=$req->input('categorie');
        $c->echelon=$req->input('echelon');

        $c->save();
        session(['success'=>true]);
        return redirect()->action('personnel\personnelController@edit',[$id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        try {
            personnel::destroy([$id]);
            session(['success'=>true]);
        } catch (QueryException $fe) {
            return  redirect()->to(URL::previous())->withErrors(['constraint_violation' => trans('admin.cannot_delete')]);
        }
        return redirect()->to(\URL::previous());
	}

}
