<?php

namespace GEICOM\Http\Middleware\privileges;

use Auth;
use Closure;
use GEICOM\Functions;

class g_vente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $r=Auth::user()->roles->pluck('value')->toArray();
        $routeAction = $request->route()->getAction();
        $action = isset($routeAction['action']) ? $routeAction['action'] : null;

        if(!Functions::pp_exists($r,2) && !Functions::contain($r,16) && (!$action || !Auth::user()->canDoAction($action))){
            return redirect()->route('not_auth');
        }
        /*   if(!Functions::pp_exists($r,16)){
               return redirect()->route('not_auth');
           }*/
        return $next($request);
    }
}
