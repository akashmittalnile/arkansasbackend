<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentCreatorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {
            if(Auth::user()->role == 2){
                return $next($request);
            }else{
                Auth::logout();
                return redirect()->route('login')->with('success', 'Access Denied! as you are not a Content Creator');
            }
        }else{
            return redirect()->route('login')->with('success','Please Login First');
        }
    }
}
