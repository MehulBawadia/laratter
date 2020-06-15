<?php

namespace Laratter\Http\Middleware;

use Closure;

class AdministratorLoggedIn
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
        if (auth()->id() == 1) {
            return $next($request);
        }

        return route('homePage');
    }
}
