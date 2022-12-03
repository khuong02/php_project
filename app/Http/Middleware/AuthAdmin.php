<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserAdmin;

class AuthAdmin
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
            if ($cookie !== null) {
                $decoded = JWT::decode($cookie, new Key(env('JWT_SECRET'), 'HS256'));
                $userAdmin = new UserAdmin();
                $userLogin = $userAdmin->getByID($decoded->uid);
                // dd($userLogin->deleted_at == null);
                if ($userLogin === null || $userLogin->deleted_at !== null) {
                    Cookie::queue(Cookie::forget('token'));
                    return redirect('/auth/login');
                } else {
                    $request->attributes->add(['user_id' => $decoded->uid]);
                    return redirect('/');
                }
            } else {
                return $next($request);
            }
        } catch (\Throwable $th) {
            return redirect('/pages/misc-under-maintenance');
        }
    }
}
