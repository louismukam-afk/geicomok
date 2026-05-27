<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Categorie;
use GEICOM\Support\ImportTable;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class CategorieController extends Controller
{

    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion des categories';
    }

    public function index()
    {
        $c=Categorie::orderBy('libelle')->get();
        $this->values['categories']=$c;
        return view('admin.categorie',$this->values);
    }

    public function importForm()
    {
        $this->values['title']='Import des categories';
        return view('admin.import_categories', $this->values);
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'fichier' => 'required|file',
        ]);

        try {
            $rows = ImportTable::readRows($request->file('fichier'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('import_categorie')->withErrors(['fichier' => $e->getMessage()]);
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $libelle = trim(array_get($row, 'libelle', array_get($row, 'categorie', '')));

            if ($libelle === '') {
                $skipped++;
                $errors[] = 'Ligne '.($index + 2).' ignoree : libelle vide.';
                continue;
            }

            if (Categorie::whereRaw('lower(libelle) = ?', [strtolower($libelle)])->exists()) {
                $skipped++;
                continue;
            }

            $c = new Categorie();
            $c->libelle = $libelle;
            $c->save();
            $created++;
        }

        return redirect()->route('import_categorie')->with('import_result', [
            'created' => $created,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }

    public function template()
    {
        $headers = ['id', 'libelle'];
        $examples = [
            ['', 'Boissons'],
            ['', 'Epicerie'],
        ];
        $path = ImportTable::createXlsxTemplate($headers, $examples);

        return response()->download($path, 'template_import_categories.xlsx')->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            ['libelle'=>'required|unique:categories']);
        $libelle=$request->input('libelle');

        $c=new Categorie();
        $c->libelle=$libelle;
        $c->save();
        return redirect()->route('categorie_management')->withSuccess(['ok'=>'']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request,
            [
                'id'=>'required|numeric',
                'libelle'=>'required|unique:categories'
            ]);
        $id=$request->input('id');
        $libelle=$request->input('libelle');

        $c=Categorie::find($id);
        $c->libelle=$libelle;
        $c->save();
        return redirect()->route('categorie_management')->withSuccess(['ok'=>'']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Categorie::destroy($id);
        return redirect()->route('categorie_management')->withSuccess(['ok'=>'']);


    }
    public function destroys(Request $request)
    {
        $idList=$request->input('check');

        Categorie::destroy($idList);
        return response()->json('OK');



    }

}
