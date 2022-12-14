<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Account;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use App\Http\Controllers\JwtController;

class AuthController extends Controller
{
    private $jwt;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verify']]);
        $this->jwt = new JwtController();
    }

    public function login(Request $request)
    {
        $now_seconds = time();

        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $accountModel = new Account();

            $account = $accountModel->findAccountByEmail($request->email);
            if (!Hash::check($request->password, $account->password)) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Password does not match',
                ], 500);
            }

            $payload = array(
                "iat" => $now_seconds,
                "exp" => $now_seconds + (60 * 60 * 24 * 365),  // Maximum expiration time is one year
                "uid" => $account->user_id,
            );

            $private_key = env("JWT_SECRET");
            $token = $this->jwt->encodeJwt($payload, $private_key);


            return response()->json([
                'status' => 200,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'status' => 500,
                'message' => $err->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $now_seconds = time();
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'min:3|required_with:cf_password|same:cf_password',
                'cf_password' => 'min:3'
            ]);

            $setting = new UserSetting();

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
            ]);

            $setting->setUserSetting($user->id);

            Account::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $payload = array(
                "iat" => $now_seconds,
                "exp" => $now_seconds + (60 * 60 * 24 * 365),  // Maximum expiration time is one hour
                "uid" => $user->id,
            );

            $private_key = env("JWT_SECRET");
            $token = $this->jwt->encodeJwt($payload, $private_key);

            return response()->json([
                'status' => 200,
                'message' => 'Account created successfully',
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'status' => 400,
                'message' => $err->getMessage(),
            ], 400);
        }
    }

    public function verify(Request $request)
    {
        $factory = (new Factory)->withServiceAccount(env('PATH_FIREBASE_TOKEN'));
        $auth = $factory->createAuth();
        $now_seconds = time();
        $setting = new UserSetting();

        $idToken = $request->header('authorization');

        try {
            $verifiedIdToken = $auth->verifyIdToken($idToken);
        } catch (FailedToVerifyToken $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }

        $uname = $verifiedIdToken->claims()->get('name');
        $email = $verifiedIdToken->claims()->get('email');
        $image = $verifiedIdToken->claims()->get('picture');

        $userModel = new User();
        $user = $userModel->findUserByEmail($email);
        if (!isset($user->email)) {
            $user = User::create([
                "username" => $uname,
                "email" => $email,
                "avatar" => $image,
            ]);

            $setting->setUserSetting($user->id);
        }

        $payload = array(
            "iat" => $now_seconds,
            "exp" => $now_seconds + (60 * 60 * 24 * 365),  // Maximum expiration time is one hour
            "uid" => $user->id,
        );

        $private_key = env("JWT_SECRET");
        $token = $this->jwt->encodeJwt($payload, $private_key);

        return response()->json([
            'status' => 200,
            'token' => $token,
        ], 200);
    }
}
