<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;

class Studiocheck
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
        if (strstr(Session::get('hak'),'ADMIN')!='')
            return $next($request);
        else return redirect('/login');
    }
}
