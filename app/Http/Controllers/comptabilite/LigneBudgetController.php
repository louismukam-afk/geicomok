<?php

namespace GEICOM\Http\Controllers\comptabilite;

use GEICOM\categorie_budgetaires;
use GEICOM\CategorieBudgetaire;
use GEICOM\donnee_ligne_budgetaires;
use GEICOM\DonneeLigneBudgetaire;
use GEICOM\ligne_budgetaires;
use GEICOM\LigneBudgetaire;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use stdClass;

class LigneBudgetController extends Controller
{
    public function __construct()
    {
        session(['big_title'=>trans('comptabilite')]);
//        $this->renewSchoolYear();
    }

    public function index()
    {
        $budget_line=ligne_budgetaires::orderBy('date_fin', 'desc')->paginate(30);
        $values= array('titre'=>trans('Lignes Budgétaires'),'ligne_budget'=>$budget_line);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('comptabilite.ligne_budgetaire',$values);

    }

    public function getLineData($id) {
        $ligne = ligne_budgetaires::with('donneesLigneBudgetaire.categorieBudgetaire')->find($id);
        $dataArray = [];
        foreach ($ligne->donneesLigneBudgetaire as $entry) {
            $c = new stdClass();
            $c->categoryName = $entry->categorieBudgetaire->libelle;
            $c->categoryId = $entry->categorieBudgetaire->id;
            $c->amount = $entry->montant;

            array_push($dataArray, $c);
        }

        return response()->json($dataArray);
    }

    public function report($id) {
        $line = ligne_budgetaires::find($id);
        $lineData = DB::table('donnee_ligne_budgetaires as dlb')
            ->join('categorie_budgetaires as cb', 'dlb.id_categorie_budgetaire', '=', 'cb.id')
            ->leftJoin('decaissements as d', 'd.id_categorie_budgetaire', '=', 'cb.id')
            ->where('dlb.id_ligne_budgetaire', '=', $id)
            ->groupBy(['cb.id', 'dlb.montant'])
            ->selectRaw('cb.id, cb.libelle, cb.numero_compte, SUM(d.montant) as realized_amount, dlb.montant as total_amount')
            ->orderBy('cb.libelle')
            ->get();

            $values = array(
                'titre' => trans('admin.report') . ' ' . trans('admin.ligne_budget'),
                'line' => $line,
                'lineData' => $lineData,
            );

            return view('comptabilite.line_report', $values);
    }

    public function edit_data($id)
    {
        $budget_line = ligne_budgetaires::with('donneesLigneBudgetaire')->find($id);
        $allocated = $budget_line->donneesLigneBudgetaire->sum('montant');
        $budget_categories = categorie_budgetaires::orderBy('libelle')->get();
        $values= array(
            'titre' => trans('Données Ligne Budgétaires'),
            'budget_line' => $budget_line,
            'budget_categories' => $budget_categories,
            'allocated' => $allocated
        );
        
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }
        return view('comptabilite.donnees_ligne_budgetaire',$values);

    }

    public function update_data(Request $request, $id)
    {

        $categoryList = $request->input('categorie');
        $montantList = $request->input('montant');

        $line = ligne_budgetaires::with('donneesLigneBudgetaire')->find($id);
        $data_ids_array = $line->donneesLigneBudgetaire->pluck('id')->toArray();
        // Delete the old Data
        if (sizeof($data_ids_array)) {
            donnee_ligne_budgetaires::destroy($data_ids_array);
        }

        if($categoryList == null){
            session(['success'=>true]);
            return redirect()->action('comptabilite\LigneBudgetController@index');
        }

        $i = 0;


        foreach ($categoryList as $c) {
            $donnee = new donnee_ligne_budgetaires();
            $donnee->id_ligne_budgetaire = $id;
            $donnee->id_categorie_budgetaire = $c;

            $donnee->montant = $montantList[$i];
            $donnee->save();

            $i++;
        }

        session(['success'=>true]);
        return redirect()->action('comptabilite\LigneBudgetController@index');
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
        $this->validate($request, [
            "libelle_ligne" => "required",
            "date_debut" => "required",
            "date_fin" => "required",
        ]);

        $libelle =$request->input('libelle_ligne');
        $date_debut=$request->input('date_debut');
        $date_fin=$request->input('date_fin');
        $total=$request->input('total');
        
        $budget_line=new ligne_budgetaires();
        $budget_line->libelle_ligne=$libelle;
        $budget_line->date_debut=$date_debut;
        $budget_line->date_fin=$date_fin;

        if ($total) {
            $budget_line->total=$total;
        }

        $budget_line->save();
        session(['success'=>true]);
        return redirect()->action('comptabilite\LigneBudgetController@index');
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
    public function update(Request $req)
    {
        $this->validate($req,[
            'id'=>'required|numeric',
            'libelle_ligne'=>'required',
            'date_debut'=>'required',
            'date_fin'=>'',
        ]);
        $id=$req->input('id');
        $libelle=$req->input('libelle_ligne');
        $date_debut=$req->input('date_debut');
        $date_fin=$req->input('date_fin');
        $total=$req->input('total');
        $budget_line=ligne_budgetaires::find($id);
        $budget_line->libelle_ligne=$libelle;
        $budget_line->date_debut=$date_debut;
        $budget_line->date_fin=$date_fin;

        if ($total) {
            $budget_line->total=$total;
        }

        $budget_line->save();
        session(['success'=>true]);
        return redirect()->action('comptabilite\LigneBudgetController@index');
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
            $budget_line=ligne_budgetaires::find($id);
            $budget_line->delete();
            session(['success'=>true]);
        } catch (QueryException $fe) {
            return  redirect()->to(URL::previous())->withErrors(['constraint_violation' => trans('admin.cannot_delete')]);
        }
        return redirect()->action('comptabilite\LigneBudgetController@index');
    }
}
