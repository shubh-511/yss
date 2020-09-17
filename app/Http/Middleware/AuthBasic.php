<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class AuthBasic
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
        if(Auth::onceBasic()){
            dd("hello");
            return response()->json(['error'=>'Auth Failed'], 200); 
        }else{
            dd("helloq");
            return $next($request);
        }

    }
}
