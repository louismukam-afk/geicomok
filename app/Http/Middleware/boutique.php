<?php

namespace GEICOM\Http\Middleware;

use Auth;
use Closure;
use GEICOM\Functions;

class boutique
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
        $b=session('current_boutique');
        if($b==null){
            return redirect()->route('home');
        }
        return $next($request);
    }
}
