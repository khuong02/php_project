<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendMail;
use App\Models\User;
use App\Models\UserAdmin;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\JwtController;

class PasswordResetRequestController extends Controller
{
    private $jwt;
    public function __construct()
    {
        $this->jwt = new JwtController();
    }

    public function sendPasswordResetEmailUser(Request $request)
    {
        if (!$this->validEmail($request->email)) {
            return response()->json([
                'erro' => true,
                'message' => 'Email does not exist.',
            ], Response::HTTP_NOT_FOUND);
        } else {

            if ($this->checkAccountResset($request)->count() > 0) {
                return response()->json(
                    [
                        'erro' => false,
                        'message' => 'The account has requested a password reset. Please check your email again'
                    ],
                    Response::HTTP_ACCEPTED
                );
            } else {
                $this->sendMail($request);
                return response()->json([
                    'erro' => false,
                    'message' => 'Check your inbox, we have sent a link to reset email.',
                ], Response::HTTP_OK);
            }
        }
    }

    public function sendEmailPasswordResetAdmin(Request $request)
    {
        if (!$this->validEmailAdmin($request->email)) {
            return response()->json([
                'erro' => true,
                'message' => 'Email does not exist.',
            ], Response::HTTP_NOT_FOUND);
        } else {

            if ($this->checkAccountResset($request)->count() > 0) {
                return response()->json(
                    [
                        'erro' => false,
                        'message' => 'The account has requested a password reset. Please check your email again'
                    ],
                    Response::HTTP_ACCEPTED
                );
            } else {
                $this->sendMailAdmin($request);
                return response()->json([
                    'erro' => false,
                    'message' => 'Check your inbox, we have sent a link to reset email.',
                ], Response::HTTP_OK);
            }
        }
    }

    public function sendMail($request)
    {
        $token = $this->generateJwtToken($request);
        Mail::to($request->email)->send(new SendMail($token));
    }


    public function sendMailAdmin($request)
    {
        $token = $this->generateJwtTokenAdmin($request);
        Mail::to($request->email)->send(new SendMail($token));
    }

    private function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    private function validEmailAdmin($email)
    {
        return !!UserAdmin::where('email', $email)->first();
    }

    private function checkAccountResset($request)
    {
        return DB::table('password_resets')->where([
            'email' => $request->email,
        ]);
    }

    private function generateJwtToken($request)
    {
        try {
            $private_key = env("JWT_SECRET");
            $now_seconds = time();
            $user = User::where(['email' => $request->email])->first();

            $payload = array(
                "iat" => $now_seconds,
                "exp" => $now_seconds + (60 * 60), // Maximum expiration time is one hour
                "uid" => $user->id,
                "claims" => array(
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'token' => $this->generateToken($request->email)
                )
            );
            $jwtToken = $this->jwt->encodeJwt($payload, $private_key);
            return $jwtToken;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function generateJwtTokenAdmin($request)
    {
        try {
            $private_key = env("JWT_SECRET");
            $now_seconds = time();
            $user = UserAdmin::where(['email' => $request->email])->first();

            $payload = array(
                "iat" => $now_seconds,
                "exp" => $now_seconds + (60 * 60), // Maximum expiration time is one hour
                "uid" => $user->id,
                "claims" => array(
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'token' => $this->generateToken($request->email)
                )
            );
            $jwtToken = $this->jwt->encodeJwt($payload, $private_key);
            return $jwtToken;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function generateToken($email)
    {
        try {
            $isOtherToken = DB::table('password_resets')->where('email', $email)->first();
            if ($isOtherToken) {
                return $isOtherToken->token;
            }
            $token = Str::random(80);
            $this->storeToken($token, $email);
            return $token;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function storeToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    }
}
