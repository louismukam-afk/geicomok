<?php

namespace GEICOM\Http\Middleware;

use Closure;

class AuthMiddleware
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

        if (\Auth::check()) {
            if ((int) \Auth::user()->active !== 1) {
                \Auth::logout();
                \Session::flush();
                return redirect()->route('login')->withErrors(['inactive' => 'Votre compte est désactivé']);
            }
            return $next($request);
        }

        return redirect()->route('login');
    }
}
