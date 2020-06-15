<?php

namespace Laratter\Http\Middleware;

use Closure;

class AdministratorExist
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
        if (\Laratter\User::withTrashed()->first() != null) {
            return $next($request);
        }

        return route('homePage');
    }
}
