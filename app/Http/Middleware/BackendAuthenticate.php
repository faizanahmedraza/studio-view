<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BackendAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
            if (Auth::guard($guard)->check() ) {
                if (Auth::user()->role_id != User::ROLE_ID) {
                        Auth::logout();
                        return redirect()->guest('reset/success');
                }
                if(Auth::user()->is_active == 0){
                    Auth::logout();
                    return redirect()->guest('login')->withErrors('Your account has been In-active by the admin, please contact.');
                }
                return $next($request);
            }
        return redirect()->guest('login');
    }
}
