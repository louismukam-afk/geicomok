<?php

namespace GEICOM\Http\Middleware;

use Closure;
use GEICOM\Parametre;

class StartMiddleware
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
        $fs=session('first_start');
        if(!$fs)
        {
            $p=Parametre::where('nom','=','started')->first();
            if ($p){
                session(['first_start'=>true]);

            }
            else
                return redirect()->route('start_step1');
        }

        return $next($request);

    }
}
