<?php

namespace GEICOM\Http\Controllers\admin;

use GEICOM\action;
use GEICOM\Functions;
use GEICOM\User;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class actionController extends Controller
{
    public function index($id)
    {
        $u=User::with('actions')->find($id);
        $actions = $u->actions->pluck('action_name')->toArray();
        $titre = trans_choice('admin.action', 10) . ': '.$u->fullname;
        $r=Action::where('id_user', '=', $id)->get();

        $ro=\Auth::user()->roles->pluck('value')->toArray();
        $valid=Functions::pp_exists($ro,0);

        $values= array('titre'=>$titre,'actions'=>$r,'actions_array' => $actions,'validated'=>$valid,'user'=>$u);
        if(session('success')){
            \Session::forget('success');
            $values['success']=true;
        }

        return view('admin.actions',$values);
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
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $current_user = \Auth::user();
        $id = $request->input('id');

        if($current_user->id != $id) {
            $actions = $request->input('actions');
            action::where('id_user', '=', $id)->delete();
            foreach ($actions as $a) {
                $ac = new action();
                $ac->id_user = $id;
                $ac->action_name = $a;
                $ac->save();
            }
        }


        return redirect()->to(\URL::previous())->with('success',true);
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
