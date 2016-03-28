<?php
namespace App\Http\Middleware;

use Closure;

class Api
{
    /**
     * Handle an incoming request
     *  
     * @param  Illuminate\Http\Request  $request 
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            return $next($request);            
        }

        return response('Forbidden for non-ajax request', 403);
    }
}
