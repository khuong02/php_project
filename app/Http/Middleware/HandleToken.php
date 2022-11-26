<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class HandleToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $cookie = Cookie::get('token');
            if ($cookie === null) {
                return redirect('/auth/login');
            } else {
                return $next($request);
            }
        } catch (\Throwable $th) {
            return redirect('/pages/misc-under-maintenance');
        }
    }
}
