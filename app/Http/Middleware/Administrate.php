<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class Administrate
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
        $user = Auth::user();
        if( !$this->isAdministrator($user) ) {
            return redirect('/');
        }

        return $next($request);
    }

    private function isAdministrator(User $user)
    {
        if( $user !== null && $user->email === "mcknight0219@gmail.com") {
            return true;
        }

        return false;
    }
}
