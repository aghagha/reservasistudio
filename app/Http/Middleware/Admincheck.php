<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;

class Admincheck
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
        // dd(Session::all());
        if (strstr(Session::get('hak'),'ADMIN')!='' && Session::get('hak') == 'ADMIN_ZUPER')
            return $next($request);
        elseif (strstr(Session::get('hak'),'ADMIN')!='' && Session::get('hak') == 'ADMIN_STUDIO')
            return redirect('studio/list');
        else
            return redirect('/login');
    }
}
