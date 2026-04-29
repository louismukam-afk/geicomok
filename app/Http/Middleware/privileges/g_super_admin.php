<?php

namespace GEICOM\Http\Middleware\privileges;

use Auth;
use Closure;
use GEICOM\Functions;

class g_super_admin
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
        if(!Functions::pp_exists($r,0)){
            return redirect()->route('not_auth');
        }
        return $next($request);
    }
}
