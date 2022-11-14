<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendMail;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;

class PasswordResetRequestController extends Controller
{
    public function sendPasswordResetEmail(Request $request)
    {
        if (!$this->validEmail($request->email)) {
            return response()->json([
                'message' => 'Email does not exist.',
            ], Response::HTTP_NOT_FOUND);
        } else {

            if ($this->checkAccountResset($request)->count() > 0) {
                return response()->json(['message' => 'The account has requested a password reset. Please check your email again'], Response::HTTP_ACCEPTED);
            } else {
                $this->sendMail($request);
                return response()->json([
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


    private function validEmail($email)
    {
        return !!User::where('email', $email)->first();
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
                "exp" => $now_seconds + (60 * 60),
                // Maximum expiration time is one hour
                "uid" => $user->id,
                "claims" => array(
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'token' => $this->generateToken($request->email)
                )
            );
            $jwtToken = JWT::encode($payload, $private_key, "HS256");
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
