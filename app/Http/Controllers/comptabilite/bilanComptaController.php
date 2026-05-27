<?php namespace GEICOM\Http\Controllers\comptabilite;

use Carbon\Carbon;
use GEICOM\Achat;
use GEICOM\EntreeSpeciale;
use GEICOM\EntreeSpecialeRemboursement;
use GEICOM\MouvementCaisse;
use GEICOM\Vente;
use GEICOM\decaissement;
use GEICOM\produit;
use GEICOM\Http\Requests;
use GEICOM\Http\Controllers\Controller;
use GEICOM\payement;
use GEICOM\payement_salaire;
use GEICOM\personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class bilanComptaController extends Controller {

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

	public function index(Request $request)
	{

        $this->validate($request,[
           'date_debut' => 'date',
            'date_fin' => 'date',
        ]);
        $tt=trans('admin.toute_lannee');
        $option=$request->input('option');
        $titre="Bilan des entrées";

        $date_debut=$request->input('date_debut');
        $date_fin=$request->input('date_fin');
        $date_range=$request->input('date_range');

        if($date_range && ($date_debut != null || $date_fin != null)) {
            if($date_debut!=null && $date_fin!=null){
               /* $p=Vente::with('produit')
                    ->where('date_vente','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                    ->where('date_vente','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date_vente','desc')
                    ->get();*/
                $p = Vente::with(['produit', 'facture.client'])
                    ->where('date_vente', '>=', (new \DateTime($date_debut))->format('Y-m-d'))
                    ->where('date_vente', '<=', (new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date_vente', 'desc')
                    ->get();


            } elseif ($date_debut != null && $date_fin == null) {
               // $p=Vente::with('produit')
                $p = Vente::with(['produit', 'facture.client'])
                    ->where('date_vente','>=',(new \DateTime($date_debut))->format('Y-m-d'))
                    ->orderBy('date_vente','desc')
                    ->get();
            } else {
                //$p=Vente::with('produit')
                $p = Vente::with(['produit', 'facture.client'])
                    ->where('date_vente','<=',(new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date_vente','desc')
                    ->get();
            }
        }
       else{
            if($option=='month')
            {
                $tt=trans('Mois');

                $dd=date('Y-m-01');
                $df=date('Y-m-t');

                //$p=Vente::with('produit')
                $p = Vente::with(['produit', 'facture.client'])
                    ->where('date_vente','>=',(new \DateTime($dd))->format('Y-m-d'))
                    ->where('date_vente','<=',(new \DateTime($df))->format('Y-m-d'))
                    ->orderBy('date_vente','desc')
                    ->get();


            }
            elseif ($option=='week'){
                $tt=trans('Semaine');

                $monday = Carbon::now()->startOfWeek()->toDateString();
                $sunday = Carbon::now()->endOfWeek()->toDateString();
                //$p=Vente::with('produit')
                $p = Vente::with(['produit', 'facture.client'])
                    ->where('date_vente','>=',(new \DateTime($monday))->format('Y-m-d'))
                    ->where('date_vente','<=',(new \DateTime($sunday))->format('Y-m-d'))
                    ->orderBy('date_vente','desc')
                    ->get();

            }
            elseif ($option=='today'){
                $tt="Aujourd\'ui";

                $d = date('Y-m-d');
                //$p=Vente::with('produit')
                $p = Vente::with(['produit', 'facture.client'])
                    ->where('date_vente','=', $d)
                    ->orderBy('date_vente','created_at')
                    ->get();
            }
            else{
                $tt="Toute l\'année";
                //$p=Vente::with('produit')
                $p = Vente::with(['produit', 'facture.client'])
                ->orderBy('date_vente','desc')->get();

            }

        }



        $mouvementsEntreesSpeciales = $this->mouvementsCaisseParPeriode('entree', 'entree_speciale', $option, $date_range, $date_debut, $date_fin);

        $total=0;
        foreach ($p as $pay){
            $total+=$pay->total;
        }
        $total_entrees_speciales = 0;
        foreach ($mouvementsEntreesSpeciales as $mouvement) {
            $total_entrees_speciales += $mouvement->montant;
        }

        $entreesSpeciales = EntreeSpeciale::whereIn('id', $mouvementsEntreesSpeciales->pluck('source_id')->toArray())->get()->keyBy('id');
        $values= array(
            'titre'=>$titre,
            'payements'=>$p,
            'total'=>$total,
            'table_titre'=>$tt,
            'mouvements_entrees_speciales'=>$mouvementsEntreesSpeciales,
            'entrees_speciales'=>$entreesSpeciales,
            'total_entrees_speciales'=>$total_entrees_speciales
        );
        if($date_range && ($date_debut != null || $date_fin != null))
        {
            $values['date_debut']=$date_debut;
            $values['date_fin']=$date_fin;
            $values['table_titre'] = $date_debut . ' - ' . $date_fin;

        }
        if(session('success')){
            $values['success']=true;
            Session::forget('success');
        }
        $values['annee_scolaire'] = \session('year');



        return view('comptabilite.bilanEntrees',$values);
	}

    public function indexSorties(Request $request)
    {
        $this->validate($request, [
            'date_debut' => 'date',
            'date_fin' => 'date',
        ]);

        $titre = trans('Bilan des sorties');

        $tt = trans('Toutes l\'année');
        $option = $request->input('option');
        $date_debut = $request->input('date_debut');
        $date_fin = $request->input('date_fin');
        $date_range=$request->input('date_range');

        if($date_range && ($date_debut != null || $date_fin != null)) {
            if ($date_debut != null && $date_fin != null) {
              /*  $p = Achat::with('produit','livraison')
                    ->where('date_achat', '>=', (new \DateTime($date_debut))->format('Y-m-d'))
                    ->where('date_achat', '<=', (new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date_achat', 'desc')
                    ->get();*/
                $p = Achat::with(['produit', 'livraison.fournisseur'])
                    ->where('date_achat', '>=', (new \DateTime($date_debut))->format('Y-m-d'))
                    ->where('date_achat', '<=', (new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date_achat', 'desc')
                    ->get();


                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')
                    ->where('date', '>=', (new \DateTime($date_debut))->format('Y-m-d'))
                    ->where('date', '<=', (new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->get();

            } elseif ($date_debut != null && $date_fin == null) {
//                $p = Achat::with('produit')
                $p = Achat::with(['produit', 'livraison.fournisseur'])
                    ->where('date_achat', '>=', (new \DateTime($date_debut))->format('Y-m-d'))
                    ->orderBy('date_achat', 'desc')
                    ->get();

                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')
                    ->where('date', '>=', (new \DateTime($date_debut))->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->get();
            } else {
              //  $p = Achat::with('produit')
                $p = Achat::with(['produit', 'livraison.fournisseur'])

                    ->where('date_achat', '<=', (new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date_achat', 'desc')
                    ->get();

                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')
                    ->where('date', '<=', (new \DateTime($date_fin))->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->get();
                }
        }
            else {
            if ($option == 'month') {
                $tt = trans('Mois');

                $dd = date('Y-m-01');
                $df = date('Y-m-t');

              //  $p = Achat::with('produit')
                $p = Achat::with(['produit', 'livraison.fournisseur'])
                    ->where('date_achat', '>=', (new \DateTime($dd))->format('Y-m-d'))
                    ->where('date_achat', '<=', (new \DateTime($df))->format('Y-m-d'))
                    ->orderBy('date_achat', 'desc')
                    ->get();
                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')
                    ->where('date', '>=', (new \DateTime($dd))->format('Y-m-d'))
                    ->where('date', '<=', (new \DateTime($df))->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->get();


            } elseif ($option == 'week') {
                $tt = trans('Semaine');

                $monday = Carbon::now()->startOfWeek()->toDateString();
                $sunday = Carbon::now()->endOfWeek()->toDateString();
                //$p = Achat::with('produit')
                $p = Achat::with(['produit', 'livraison.fournisseur'])
                    ->where('date_achat', '>=', (new \DateTime($monday))->format('Y-m-d'))
                    ->where('date_achat', '<=', (new \DateTime($sunday))->format('Y-m-d'))
                    ->orderBy('date_achat', 'desc')
                    ->get();

                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')
                    ->where('date', '>=', (new \DateTime($monday))->format('Y-m-d'))
                    ->where('date', '<=', (new \DateTime($sunday))->format('Y-m-d'))
                    ->orderBy('date', 'desc')
                    ->get();

            } elseif ($option == 'today') {
                $tt = trans('Aujourd\'hui');

                $d = date('Y-m-d');
               // $p = Achat::with('produit')
                $p = Achat::with(['produit', 'livraison.fournisseur'])
                    ->where('date_achat', '=', $d)
                    ->orderBy('date_achat', 'desc')
                    ->get();

                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')
                    ->where('date', '=', $d)
                    ->orderBy('date', 'desc')
                    ->get();

            } else {
                $tt = trans('Toutes l\'année');
               // $p = Achat::with('produit')->orderBy('date_achat', 'desc')->get();
                 $p = Achat::with(['produit', 'livraison.fournisseur'])
                    ->orderBy('date_achat', 'desc')->get();

                $d = decaissement::with('personnel','ligne_budgetaire','categorie_budgetaire')->orderBy('date', 'desc')->get();


            }
        }


            $mouvementsRemboursements = $this->mouvementsCaisseParPeriode('sortie', 'remboursement_entree_speciale', $option, $date_range, $date_debut, $date_fin);

            $total_p = 0;
            foreach ($p as $pay) {
                $total_p += $pay->total;
            }
            $total_d = 0;
            foreach ($d as $pay) {
                $total_d += $pay->montant;
            }
            $total_remboursements = 0;
            foreach ($mouvementsRemboursements as $mouvement) {
                $total_remboursements += $mouvement->montant;
            }
            $remboursementsSpeciaux = EntreeSpecialeRemboursement::with('entreeSpeciale')
                ->whereIn('id', $mouvementsRemboursements->pluck('source_id')->toArray())
                ->get()
                ->keyBy('id');
            $pers = personnel::all();
            $values = array(
                'titre' => $titre,
                'payements' => $p,
                'total' => $total_p,
                'total_d' => $total_d,
                'decaissements' => $d,
                'personnels' => $pers,
                'table_titre' => $tt,
                'mouvements_remboursements' => $mouvementsRemboursements,
                'remboursements_speciaux' => $remboursementsSpeciaux,
                'total_remboursements' => $total_remboursements
            );
            if ($date_range && ($date_debut != null || $date_fin != null)) {
                $values['date_debut'] = $date_debut;
                $values['date_fin'] = $date_fin;
                $values['table_titre'] = $date_debut . ' - ' . $date_fin;
            }
            if (session('success')) {
                $values['success'] = true;
                Session::forget('success');
            }


            return view('comptabilite.bilanSorties', $values);
        }

    private function mouvementsCaisseParPeriode($type, $sourceType, $option, $dateRange, $dateDebut, $dateFin)
    {
        $query = MouvementCaisse::with('caisse')
            ->where('type', $type)
            ->where('source_type', $sourceType);

        if ($dateRange && ($dateDebut != null || $dateFin != null)) {
            if ($dateDebut != null) {
                $query->where('date_mouvement', '>=', (new \DateTime($dateDebut))->format('Y-m-d').' 00:00:00');
            }
            if ($dateFin != null) {
                $query->where('date_mouvement', '<=', (new \DateTime($dateFin))->format('Y-m-d').' 23:59:59');
            }
        } elseif ($option == 'month') {
            $query->where('date_mouvement', '>=', date('Y-m-01').' 00:00:00')
                ->where('date_mouvement', '<=', date('Y-m-t').' 23:59:59');
        } elseif ($option == 'week') {
            $query->where('date_mouvement', '>=', Carbon::now()->startOfWeek()->toDateString().' 00:00:00')
                ->where('date_mouvement', '<=', Carbon::now()->endOfWeek()->toDateString().' 23:59:59');
        } elseif ($option == 'today') {
            $query->where('date_mouvement', '>=', date('Y-m-d').' 00:00:00')
                ->where('date_mouvement', '<=', date('Y-m-d').' 23:59:59');
        }

        return $query->orderBy('date_mouvement', 'desc')->get();
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
