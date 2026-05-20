<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public static function enregistrer($operation, $message, $request=null, $user=null, $idBoutique=null)
    {
        $user=$user ?: (\Auth::check() ? \Auth::user() : null);
        $boutique=session('current_boutique');

        $log=new self();
        $log->message=$message;
        $log->operation=$operation;
        $log->id_user=$user ? $user->id : 0;
        $log->id_boutique=$idBoutique !== null ? $idBoutique : ($boutique ? $boutique->id : 0);

        if ($request) {
            $route=$request->route();
            $log->route_name=$route ? $route->getName() : null;
            $log->method=$request->method();
            $log->url=$request->fullUrl();
            $log->ip=$request->ip();
            $log->user_agent=substr((string)$request->header('User-Agent'), 0, 1000);
        }

        $log->save();
        return $log;
    }
}
