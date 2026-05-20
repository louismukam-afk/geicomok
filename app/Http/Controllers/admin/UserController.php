<?php

namespace GEICOM\Http\Controllers\admin;

use Auth;
use GEICOM\Boutique;
use GEICOM\Functions;
use GEICOM\Log;
use GEICOM\Role;
use GEICOM\User;
use Illuminate\Http\Request;
use GEICOM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $values=[];
    public function __construct()
    {
        $this->values['big_title']='Administration';

        $this->values['title']='Gestion des utilisateurs';
    }


    public function index()
    {
        $r_arr=\Auth::user()->roles;

        if(!Functions::contain(\Auth::user()->roles->pluck('value')->toArray(),0)){
            $u_array=Role::where('value','<=',1)->get()->pluck('id_user')->toArray();
            $u=User::with('boutique')->whereNotIn('id',$u_array)->orderBy('id_boutique')->orderBy('created_at','desc')->paginate(30);


        }
        else{
            $u_array=Role::where('value','<=',0)->get()->pluck('id_user')->toArray();
            $u=User::with('boutique')->whereNotIn('id',$u_array)->orderBy('id_boutique')->orderBy('created_at','desc')->paginate(30);

        }



        $this->values['utilisateurs']=$u;
        $this->values['boutiques']=Boutique::orderBy('nom')->get();
        if(session('success')){
            Session::forget('success');
            $this->values['success']=true;
        }
        return view('admin\utilisateurs',$this->values);
    }


    public function logs(Request $request)
    {
        $cb=session('current_boutique');
        if(!$cb)
            return redirect()->route('home');
        $this->values['title']='Journal des operations';


        $cbId=$cb->id;
        $dateDebut=$request->input('date_debut', date('Y-m-01'));
        $dateFin=$request->input('date_fin', date('Y-m-d'));
        $operation=$request->input('operation', '');
        $user=$request->input('user', 0);

        $query=Log::with('user')
            ->where(function ($q) use($cbId) {
                $q->where('id_boutique','=',$cbId)
                    ->orWhere('id_boutique','=',0);
            })
            ->where('created_at', '>=', $dateDebut.' 00:00:00')
            ->where('created_at', '<=', $dateFin.' 23:59:59');

        if($operation){
            $query->where('operation', $operation);
        }

        if($user){
            $query->where('id_user', $user);
        }

        $l=$query->orderBy('created_at','desc')->paginate(200);

        $this->values['logs']=$l;
        $this->values['users']=User::orderBy('name')->get();
        $this->values['operations']=Log::whereNotNull('operation')
            ->select('operation')
            ->distinct()
            ->orderBy('operation')
            ->pluck('operation');
        $this->values['date_debut']=$dateDebut;
        $this->values['date_fin']=$dateFin;
        $this->values['operation']=$operation;
        $this->values['user_id']=$user;

        return view('admin\logs',$this->values);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function activate(Request $request)
    {

        $this->validate($request,[
            'id'=>'required',
            'boutique'=>'required',
        ]);
        $id=$request->input('id');
        $boutique=$request->input('boutique');
        $u=User::find($id);
        $u->id_boutique=$boutique;
        $u->active=1;
        /*$personnel=$request->input('personnel');
        if($personnel!=0){
            $u->id_personnel=$personnel;
        }*/
        $u->save();
        session(['success'=>true]);
        return redirect(route('user_management'));
    }

    public function deactivate($id)
    {
        $u=User::find($id);
        $u->active=0;
        $u->save();
        session(['success'=>true]);

        return redirect(route('user_management'));



    }


    public function index_role($id)
    {
        $u=User::find($id);
        $r=Role::where('id_user','=',$id)->get();

        $ro=Auth::user()->roles->pluck('value')->toArray();
        $valid=Functions::pp_exists($ro,0);

        $this->values= array('title'=>'Gestion des roles','roles'=>$r,'validated'=>$valid,'user'=>$u);
        if(session('success')){
            Session::forget('success');
            $values['success']=true;
        }

        return view('admin\roles',$this->values);
    }


    public function store_role(Request $request)
    {
        $this->validate($request,[
            'id'=>'required',
            'role'=>'required',
        ]);
        $id=$request->input('id');
        $role=$request->input('role');

        $r=Role::where('id_user','=',$id)->where('value','=',$role)->first();
        if($r)
            return redirect(\URL::previous());

        $c=new Role();
        $c->id_user=$id;
        $c->value=$role;
        $c->save();

        session(['success'=>true]);
        return redirect()->to(\URL::previous())->with('success',true);
    }


    public function destroy_role($id)
    {
        $c=Role::find($id);
        $c->delete();

        return redirect()->to(\URL::previous())->with('success',true);
    }

    public function edit_password(Request $request){
        $validator=Validator::make(\request()->all(),[
            'ancien_mot_de_passe'=>'required',
            'mot_de_passe'=>'required|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->all(),400);
        }
        $amp=$request->input('ancien_mot_de_passe');
        $mp=$request->input('mot_de_passe');
        $cp=Auth::user()->password;

        if(!\Hash::check($amp,$cp)){
            return response()->json(["L'ancien mot de passe ne correspond pas"],400);
        }
        else {
            if($amp==$mp){
                return response()->json(["Vous devez choisir un autre mot de passe"],400);
            }
            \Auth::user()->password=bcrypt($mp);
            \Auth::user()->save();
            return response()->json(['success']);
        }



    }

}
