<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\Categorie;
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
