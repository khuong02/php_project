<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtController extends Controller
{
    public function decodedJwt($jwtToken, $private_key, $hasCode = "HS256")
    {
        try {
            $decoct = JWT::decode($jwtToken, new Key($private_key, $hasCode));
            return $decoct;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function encodeJwt($payload, $private_key, $hasCode = "HS256")
    {
        try {
            return JWT::encode($payload, $private_key, $hasCode);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
