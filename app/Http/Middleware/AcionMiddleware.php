<?php

namespace GEICOM\Http\Middleware;

use Closure;
use GEICOM\User;

class AcionMiddleware
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
        $params = $request->route()->getAction();
        // section is your key in route, of course it could have any name
        $action = $params['action'];
        $user = \Auth::user();

        if (!$user->canDoAction($action)) {
            if($request->ajax()) {
                return response('not_auth', 403);
            }
            return redirect()->to(url('/not-authorized'));
        }
        return $next($request);
    }

    public static function hasAction(User $user, $action) {
        return true;
    }


}
