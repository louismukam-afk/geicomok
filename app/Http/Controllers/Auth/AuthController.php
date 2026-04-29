<?php

namespace GEICOM\Http\Controllers\Auth;

use Auth;
use GEICOM\Boutique;
use GEICOM\User;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        if(Auth::check())
            return redirect(route('home'));
    }


    public function attempt_login(Request $request)
    {
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required'
        ]);
        $username=$request->input('username');
        $password=$request->input('password');
        $remember=$request->input('remember');
        if($remember)
            $remember=true;
        else
            $remember=false;

        if(Auth::attempt(['username'=>$username,'password'=>$password],$remember)){
            $u=Auth::user();
            if($u->active==0){
                Auth::logout();
                return redirect()->to(route('login'))->withErrors(['inactive'=>'Votre compte est désactivé']);

            }
            if($u->id_boutique!=0){
                $b=Boutique::find($u->id_boutique);
                if ($b){
                    if($b->active==0)
                    {
                        Auth::logout();
                        return redirect()->to(route('login'))->withErrors(['inact_boutique'=>'Votre '.trans('main.boutique').' est '.trans('main.desactive') ]);

                    }
                    else
                    {
                        session(['current_boutique'=>$b]);
                    }

                }else{
                    Auth::logout();
                    return redirect()->to(route('login'))->withErrors(['no_boutique'=>'Votre compte n\'est associé à aucune '.trans('main.boutique') ]);
                }
            }
            return redirect(route('home'));

        }
        else
            return redirect()->to(route('login'))->withErrors(['bad_auth'=>'Le nom d\'utilisateur ou le mot de passe sont incorrects'])->withInput($request->all());


    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'username'=>'required|unique:users',
            'name'=>'required',
            'password'=>'required|confirmed|min:6'
        ]);

        $u=new User();
        $u->name=$request->input('name');
        $u->username=$request->input('username');
        $u->password=bcrypt($request->input('password'));
        $u->save();
        if(Auth::attempt(['username'=>$request->input('username'),'password'=>$request->input('password')])){
            if($u->active==0){
                Auth::logout();
                return redirect()->to(route('login'))->withErrors(['inactive'=>'Votre compte est désactivé'])->withInput($request->all());

            }


            return redirect(route('home'));
        }


    }
}
