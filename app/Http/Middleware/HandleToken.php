<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserAdmin;


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
            }
            $cookie = Cookie::get('token');
            $decoded = JWT::decode($cookie, new Key(env('JWT_SECRET'), 'HS256'));
            $userAdmin = new UserAdmin();
            $userLogin = $userAdmin->getByID($decoded->uid);
            if ($userLogin === null || $userLogin->deleted_at !== null) {
                return redirect('/auth/login');
            }
            return $next($request);
        } catch (\Throwable $th) {
            return redirect('/pages/misc-under-maintenance');
        }
    }
}
