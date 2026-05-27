<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Boutique;
use GEICOM\Categorie;
use GEICOM\ProduitLies;
use GEICOM\Stock;
use GEICOM\Support\ImportTable;
use Illuminate\Http\Request;
use GEICOM\Produit;
use GEICOM\Http\Controllers\Controller;

class produitController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion produits';
    }
    public  function index()
    {
        $p=Produit::with('categorie')->orderBy('libelle')->paginate(200);
        $c=Categorie::all();
        $this->values['produit']=$p  ;
        $this->values['categories']=$c;
        return view('admin.produit',$this->values);
    }

    public function importForm()
    {
        $this->values['title']='Import des produits';
        $this->values['categories']=Categorie::orderBy('libelle')->get();
        return view('admin.import_produits', $this->values);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'fichier' => 'required|file',
        ]);

        try {
            $rows = ImportTable::readRows($request->file('fichier'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('import_produit')->withErrors(['fichier' => $e->getMessage()]);
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $libelle = trim(array_get($row, 'libelle', array_get($row, 'nom', '')));

            if ($libelle === '') {
                $skipped++;
                $errors[] = 'Ligne '.$line.' ignoree : libelle vide.';
                continue;
            }

            if (Produit::whereRaw('lower(libelle) = ?', [strtolower($libelle)])->exists()) {
                $skipped++;
                continue;
            }

            $categorie = $this->findImportCategorie($row);
            if (!$categorie) {
                $skipped++;
                $errors[] = 'Ligne '.$line.' ignoree : categorie introuvable.';
                continue;
            }

            $p = new Produit();
            $p->libelle = $libelle;
            $p->reference = trim(array_get($row, 'reference', ''));
            $p->description = trim(array_get($row, 'description', ''));
            $p->id_categorie = $categorie->id;
            $p->prix = $this->numberValue(array_get($row, 'prix', array_get($row, 'prix_vente', 0)));
            $p->prix_gros = $this->numberValue(array_get($row, 'prix_gros', 0));
            $p->prix_semi_gros = $this->numberValue(array_get($row, 'prix_semi_gros', 0));
            $p->prix_comptoir = $this->numberValue(array_get($row, 'prix_comptoir', 0));
            $p->prix_achat = $this->numberValue(array_get($row, 'prix_achat', 0));
            $p->prix_minimum = $this->numberValue(array_get($row, 'prix_minimum', array_get($row, 'quantite_minimale', 0)));
            $p->quantite_minimale = $this->numberValue(array_get($row, 'quantite_minimale', 0));
            $p->save();

            $this->createStocksForProduct($p->id);
            $created++;
        }

        return redirect()->route('import_produit')->with('import_result', [
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }

    public function template()
    {
        $headers = [
            'id',
            'libelle',
            'description',
            'quantite_minimale',
            'reference',
            'id_categorie',
            'categorie',
            'prix',
            'prix_achat',
            'prix_minimum',
            'prix_semi_gros',
            'prix_comptoir',
            'prix_gros',
        ];
        $examples = [
            ['', 'Savon', 'Savon parfum citron', '0', 'REF001', '', 'Cosmetiques', '1000', '700', '0', '950', '980', '900'],
            ['', 'Jus orange', 'Bouteille 50cl', '0', 'REF002', '', 'Boissons', '500', '350', '0', '475', '490', '450'],
        ];
        $path = ImportTable::createXlsxTemplate($headers, $examples);

        return response()->download($path, 'template_import_produits.xlsx')->deleteFileAfterSend(true);
    }

    public  function index1()
    {
        $p=Produit::with('categorie')->orderBy('libelle')->paginate(200);
        $c=Categorie::all();
        $this->values['produit']=$p  ;
        $this->values['categories']=$c;
        return view('stocks.edit_produit',$this->values);
    }


    public  function store1(Request $request)
    {
        $this->validate($request,[
            'libelle'=>'required|unique:produits',
            'prix'=>'required|numeric',
            'prix_achat'=>'required|numeric',
            'id_categorie'=>'required',
            'quantite_minimale'=>'required|numeric',
        ]);
        $libele=$request->input('libelle');
        $reference=$request->input('reference');
        $prix=$request->input('prix');
        $prix_a=$request->input('prix_achat');

        $des=$request->input('description');
        $prix_min=$request->input('quantite_minimale');
        $ca=$request->input('id_categorie');
        $p=new Produit();
        $p->libelle=$libele;
        $p->description=$des;
        $p->reference=$reference;
        $p->id_categorie=$ca;
        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix=$prix;
        $p->prix_achat=$prix_a;

        $p->save();

        $date=date('Y-m-d H:i:s');
        $b=Boutique::all();
        $i_arr=[];
        $i=0;
        foreach ($b as $bou){
            $i_arr[$i]=['id_produit'=>$p->id,'id_boutique'=>$bou->id,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);


        return redirect()->route('nouvel_achat')->withSuccess(['ok'=>'']);
    }


    public  function store(Request $request)
    {
        $this->validate($request,[
           'libelle'=>'required|unique:produits',
            'prix'=>'required|numeric',
            'prix_semi_gros' => 'required|numeric',
            'prix_comptoir' => 'required|numeric',
            'prix_gros' => 'required|numeric',
            'prix_achat'=>'required|numeric',
            'id_categorie'=>'required',
            'quantite_minimale'=>'required|numeric',
        ]);
        $libele=$request->input('libelle');
        $reference=$request->input('reference');
        $prix=$request->input('prix');
        $prix_semi_gros = $request->input('prix_semi_gros');
        $prix_comptoir = $request->input('prix_comptoir');
        $prix_gros = $request->input('prix_gros');
        $prix_a=$request->input('prix_achat');

        $des=$request->input('description');
        $prix_min=$request->input('quantite_minimale');
        $ca=$request->input('id_categorie');
        $p=new Produit();
        $p->libelle=$libele;
        $p->description=$des;
        $p->reference=$reference;
        $p->id_categorie=$ca;

        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix=$prix;
        $p->prix_semi_gros = $prix_semi_gros;
        $p->prix_comptoir = $prix_comptoir;
        $p->prix_gros = $prix_gros;
        $p->prix_achat=$prix_a;


        $p->save();
      /*  dump($p);
        die();*/

        $date=date('Y-m-d H:i:s');
        $b=Boutique::all();
        $i_arr=[];
        $i=0;
        foreach ($b as $bou){
            $i_arr[$i]=['id_produit'=>$p->id,'id_boutique'=>$bou->id,'created_at'=>$date,'updated_at'=>$date];

            $i++;
        }
        Stock::insert($i_arr);


        return redirect()->route('produit_management')->withSuccess(['ok'=>'']);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
            'prix' => 'required|numeric',
            'prix_semi_gros' => 'required|numeric',
            'prix_comptoir' => 'required|numeric',
            'prix_gros' => 'required|numeric',
            'prix_achat' => 'required|numeric',

            'id_categorie' => 'required',
            'id'=>'required|numeric',
            'quantite_minimale'=>'required|numeric',

        ]);

        $id=$request->input('id');
        $libele = $request->input('libelle');
        $reference = $request->input('reference');
        $prix = $request->input('prix');
       $prix_semi_gros = $request->input('prix_semi_gros');
        $prix_comptoir = $request->input('prix_comptoir');
        $prix_gros = $request->input('prix_gros');
        $prix_a = $request->input('prix_achat');

        $des = $request->input('description');
        $prix_min = $request->input('quantite_minimale');
        $ca = $request->input('id_categorie');
        $p=Produit::find($id);
        $p->libelle = $libele;
        $p->description = $des;
        $p->reference = $reference;
        $p->id_categorie = $ca;
       /* $p->prix_semi_gros = $prix_semi_gros;
        $p->prix_comptoir = $prix_comptoir;
        $p->prix_gros = $prix_gros;*/
        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix = $prix;
        $p->prix_semi_gros = $prix_semi_gros;
        $p->prix_comptoir = $prix_comptoir;
        $p->prix_gros = $prix_gros;
        $p->prix_achat = $prix_a;

        $p->save();
        return redirect()->route('produit_management')->withSuccess(['ok' => '']);
    }
    public function search(Request $request)
    {
        $query = $request->get('q');

        $produits = Produit::with('categorie')
            ->where('libelle', 'like', '%' . $query . '%')
            ->orWhere('reference', 'like', '%' . $query . '%')
            ->get();

        return response()->json($produits);
    }



    public function update1(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
            'prix' => 'required|numeric',
            'prix_achat' => 'required|numeric',

            'id_categorie' => 'required',
            'id'=>'required|numeric',
            'quantite_minimale'=>'required|numeric',

        ]);

        $id=$request->input('id');
        $libele = $request->input('libelle');
        $reference = $request->input('reference');
        $prix = $request->input('prix');
        $prix_a = $request->input('prix_achat');

        $des = $request->input('description');
        $prix_min = $request->input('quantite_minimale');
        $ca = $request->input('id_categorie');
        $p=Produit::find($id);
        $p->libelle = $libele;
        $p->description = $des;
        $p->reference = $reference;
        $p->id_categorie = $ca;
        if($prix_min)
            $p->prix_minimum=$prix_min;
        $p->prix = $prix;
        $p->prix_achat = $prix_a;

        $p->save();
        return redirect()->route('nouvel_achat')->withSuccess(['ok' => '']);
    }



    public function destroy($id)
    {
        produit::destroy($id);
        return redirect()->route('produit_management')->withSuccess(['ok'=>'']);


    }
    public function destroys(Request $request)
    {
        $idList = $request->input('check');

        produit::destroy($idList);
        return response()->json('OK');
    }

    public function index_lies($id)
    {
        $pl=ProduitLies::with(['produit_p','produit_c'])->where('id_produit_parent','=',$id)->get();
        $arr=$pl->pluck('id_produit')->toArray();

        $p=Produit::whereNotIn('id',$arr)->orderBy('libelle')->get();
        $this->values['title']='Produits liés';
        $this->values['produit']=Produit::find($id);

        $this->values['produits']=$p;
        $this->values['produit_lies']=$pl;

        return view('admin.produit_lies',$this->values);
    }

    public function store_lies(Request $request)
    {
        $this->validate($request,[
           'id'=>'required|numeric',
            'produit'=>'required',
            'quantite'=>'required|numeric',
        ]);

        $pl=new ProduitLies();
        $pl->id_produit_parent=$request->input('id');
        $pl->id_produit=$request->input('produit');
        $pl->quantite=$request->input('quantite');

        $pl->save();

        return redirect()->to(route('produit_lies_management',$request->input('id')))->withSuccess(['success'=>true]);

    }

    public function destroy_lies($id)
    {
        $pl=ProduitLies::find($id);
        $id_p=$pl->id_produit_parent;
        ProduitLies::destroy($id);

        return redirect()->to(route('produit_lies_management',$id_p))->withSuccess(['success'=>true]);

    }

    public  function find(Request $request)
    {

        $cb=session('current_boutique')->id;

        $pattern=$request->input('pattern');

        $p=Produit::with(['categorie','stock'=>function ($query) use($cb) {
            $query->where('id_boutique',$cb);
        }])->whereRaw('lower(libelle) LIKE ? OR lower(reference) LIKE ?',['%'.strtolower($pattern).'%','%'.strtolower($pattern).'%'])->orderBy('libelle')->limit(10)->get();
        $p->each(function ($produit) use ($cb) {
            if (!$produit->stock) {
                $stock = new Stock();
                $stock->id_produit = $produit->id;
                $stock->id_boutique = $cb;
                $stock->quantite = 0;
                $produit->setRelation('stock', $stock);
            }
        });
        $this->values['produits']=$p  ;
        $this->values['pattern']=$pattern  ;
        return response()->json($this->values)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');

    }

    public  function find_by_m(Request $request)
    {

        $magasin=$request->input('magasin');

        $pattern=$request->input('pattern');

        $p=Produit::with(['categorie','stock'=>function ($query) use($magasin) {
            $query->where('id_boutique',$magasin);
        }])->whereRaw('lower(libelle) LIKE ? OR lower(reference) LIKE ?',['%'.strtolower($pattern).'%','%'.strtolower($pattern).'%'])->orderBy('libelle')->limit(10)->get();
        $p->each(function ($produit) use ($magasin) {
            if (!$produit->stock) {
                $stock = new Stock();
                $stock->id_produit = $produit->id;
                $stock->id_boutique = $magasin;
                $stock->quantite = 0;
                $produit->setRelation('stock', $stock);
            }
        });
        $this->values['produits']=$p  ;
        $this->values['pattern']=$pattern  ;
        return response()->json($this->values)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');

    }

    private function findImportCategorie($row)
    {
        $idCategorie = trim(array_get($row, 'id_categorie', ''));
        if ($idCategorie !== '') {
            $categorie = Categorie::find($idCategorie);
            if ($categorie) {
                return $categorie;
            }
        }

        $libelle = trim(array_get($row, 'categorie', array_get($row, 'libelle_categorie', '')));
        if ($libelle === '') {
            return null;
        }

        $categorie = Categorie::whereRaw('lower(libelle) = ?', [strtolower($libelle)])->first();
        if ($categorie) {
            return $categorie;
        }

        $categorie = new Categorie();
        $categorie->libelle = $libelle;
        $categorie->save();

        return $categorie;
    }

    private function createStocksForProduct($idProduit)
    {
        $date = date('Y-m-d H:i:s');
        $boutiques = Boutique::all();
        $stocks = [];

        foreach ($boutiques as $boutique) {
            $stocks[] = [
                'id_produit' => $idProduit,
                'id_boutique' => $boutique->id,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        if (count($stocks) > 0) {
            Stock::insert($stocks);
        }
    }

    private function numberValue($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return 0;
        }

        $value = str_replace(' ', '', $value);
        $value = str_replace(',', '.', $value);

        return is_numeric($value) ? (float) $value : 0;
    }

    }
