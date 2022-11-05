<?php
namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRequestController extends Controller
{

    public function sendPasswordResetEmail(Request $request)
    {
        // If email does not exist
        if (!$this->validEmail($request->email)) {
            return response()->json([
                'message' => 'Email does not exist.',
            ], Response::HTTP_NOT_FOUND);
        } else {
            // If email exists
            if ($this->checkAccountResset($request)->count() > 0) {
                return response()->json(['message' => 'The account has requested a password reset. Please check your email again'], Response::HTTP_OK);
            } else {
                $this->sendMail($request->email);
                return response()->json([
                    'message' => 'Check your inbox, we have sent a link to reset email.',
                ], Response::HTTP_OK);
            }
        }
    }

    public function sendMail($email)
    {
        $token = $this->generateToken($email);
        Mail::to($email)->send(new SendMail($token, $email));
    }
    public function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    private function checkAccountResset($request)
    {
        return DB::table('password_resets')->where([
            'email' => $request->email,
        ]);
    }

    // public function generateJwtToken($email, $token)
    // {
    //     $customClaims = ['email' => $email, '$token' => $this->generateToken($email)];
    //     $payload = JWTFactory::make($customClaims);
    //     return JWTAuth::encode($payload);
    // }

    public function generateToken($email)
    {
        $isOtherToken = DB::table('password_resets')->where('email', $email)->first();
        if ($isOtherToken) {
            return $isOtherToken->token;
        }
        $token = Str::random(80);
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    }

}
