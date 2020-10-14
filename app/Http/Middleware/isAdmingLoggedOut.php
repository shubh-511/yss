<?php

namespace App\Http\Middleware;

use Closure;

class isAdmingLoggedOut
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
        $admin = $request->session()->get('admin_session');

        if(!$admin){

            return $next($request);

        }

        return redirect('admin/home');
        
    }
}
