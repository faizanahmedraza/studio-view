<?php

namespace App\Http\Middleware;

use Closure;

class CheckToken
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
        if ($request->header('Api-Token') != \Config::get('app.api_token')) {
            return \Response::json(['error' => 'Invalid or missing api token.']);
        }
        return $next($request);
    }
}
