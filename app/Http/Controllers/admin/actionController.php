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
        if(!$u) {
            return redirect()->route('user_management');
        }
        $actions = $u->actions->pluck('action_name')->toArray();
        $titre = 'Attribution des fonctions : '.$u->name;
        $r=Action::where('id_user', '=', $id)->get();

        $ro=\Auth::user()->roles->pluck('value')->toArray();
        $valid=Functions::pp_exists($ro,0);

        $values= array('titre'=>$titre,'actions'=>$r,'actions_array' => $actions,'validated'=>$valid,'user'=>$u,'sections'=>action::catalog());
        if(session('success')){
            \Session::forget('success');
            $values['success']=true;
        }
        if(session('sync_success')){
            \Session::forget('sync_success');
            $values['sync_success']=true;
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
            $actions = $request->input('actions', []);
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

    public function sync($id)
    {
        $u = User::find($id);
        if(!$u) {
            return redirect()->route('user_management');
        }

        if(\Auth::user()->id != $id) {
            $currentActions = action::where('id_user', '=', $id)->pluck('action_name')->toArray();
            foreach (action::routeActionNames() as $routeAction) {
                if(!in_array($routeAction, $currentActions)) {
                    $ac = new action();
                    $ac->id_user = $id;
                    $ac->action_name = $routeAction;
                    $ac->save();
                }
            }
        }

        return redirect()->to(\URL::previous())->with('sync_success', true);
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
