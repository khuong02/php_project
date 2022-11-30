<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAdmin;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\JwtController;


class AccountManegementController extends Controller
{
    private $jwt;
    public function __construct()
    {
        $this->jwt = new JwtController();
    }

    public function Index(Request $request)
    {
        try {
            $private_key = env("JWT_SECRET");
            $cookie = $this->getCookie('token');
            $jwtToken = $this->jwt->decodedJwt($cookie, $private_key);
            $userAdmin = new UserAdmin();
            $admiProfile = $userAdmin->getByID($jwtToken->uid);
            return view('content.account.account-settings-account', ['admiProfile' => $admiProfile]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getCookie($cookieName)
    {
        return cookie::get($cookieName);
    }
}
