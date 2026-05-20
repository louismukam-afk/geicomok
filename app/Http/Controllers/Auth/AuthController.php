<?php

namespace GEICOM\Http\Controllers\Auth;

use Auth;
use GEICOM\Boutique;
use GEICOM\Log;
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
        $remember=$request->input('remember') ? true : false;

        if(Auth::attempt(['username'=>$username,'password'=>$password],$remember)){
            $u=Auth::user();
            if((int) $u->active !== 1){
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
            Log::enregistrer('connexion', $u->name.' s est connecte au systeme', $request, $u, $u->id_boutique);
            return redirect(route('home'));
        }

        Log::enregistrer('connexion refusee', 'Tentative de connexion echouee pour '.$username, $request, null, 0);
        return redirect()->to(route('login'))->withErrors(['bad_auth'=>'Le nom d\'utilisateur ou le mot de passe sont incorrects'])->withInput($request->all());
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user=Auth::user();
            Log::enregistrer('deconnexion', $user->name.' s est deconnecte du systeme', $request, $user, $user->id_boutique);
        }

        Auth::logout();
        \Session::flush();
        return redirect()->route('login');
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
        $u->active=0;
        $u->save();

        return redirect()->to(route('login'))
            ->with('success', 'Votre compte a été créé. Il doit être activé par un administrateur avant connexion.');
    }
}
