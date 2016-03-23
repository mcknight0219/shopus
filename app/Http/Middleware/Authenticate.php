<?php

namespace App\Http\Middleware;

use Log;
use Auth;
use Closure;

class Authenticate
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
        if( !Auth::user() ) {
            if ($request->ajax()) { 
                return response()->json(['errmsg' => 'Authorized ajax call is denied'], 401);
            } else {
                return redirect('/');
            }
        }
        return $next($request);
    }
}
