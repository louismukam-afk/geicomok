<?php

namespace GEICOM\Http\Middleware;

use Closure;
use GEICOM\Log;
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
        $action = isset($params['action']) ? $params['action'] : null;
        $user = \Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ((int) $user->active !== 1) {
            \Auth::logout();
            \Session::flush();
            return redirect()->route('login')->withErrors(['inactive' => 'Votre compte est désactivé']);
        }

        if (!$action) {
            return redirect()->route('not_auth');
        }

        if (!$user->canDoAction($action)) {
            if($request->ajax()) {
                return response('not_auth', 403);
            }
            return redirect()->route('not_auth');
        }
        $response=$next($request);

        $operation=$this->operationFromRequest($request);
        $routeName=$request->route() ? $request->route()->getName() : $request->path();
        Log::enregistrer(
            $operation,
            $user->name.' a effectue une operation de '.$operation.' sur '.$routeName,
            $request,
            $user
        );

        return $response;
    }

    public static function hasAction(User $user, $action) {
        return true;
    }

    private function operationFromRequest($request)
    {
        $name=$request->route() ? strtolower((string)$request->route()->getName()) : '';
        $path=strtolower($request->path());
        $method=strtoupper($request->method());
        $target=$name.' '.$path;

        if (strpos($target, 'delete') !== false
            || strpos($target, 'destroy') !== false
            || strpos($target, 'supprimer') !== false
            || strpos($target, 'mdelete') !== false) {
            return 'suppression';
        }

        if (strpos($target, 'update') !== false
            || strpos($target, 'edit') !== false
            || strpos($target, 'modifier') !== false
            || strpos($target, 'sync') !== false
            || strpos($target, 'consolider') !== false
            || $method === 'PUT'
            || $method === 'PATCH') {
            return 'modification';
        }

        if (strpos($target, 'store') !== false
            || strpos($target, 'create') !== false
            || strpos($target, 'nouvel') !== false
            || $method === 'POST') {
            return 'creation';
        }

        return 'visualisation';
    }


}
