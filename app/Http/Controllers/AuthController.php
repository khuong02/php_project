<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\UserSetting;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Kreait\Firebase\Factory;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verify']]);
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
                "exp" => $now_seconds + (60 * 60 * 24 * 365),  // Maximum expiration time is one hour
                "uid" => $account->user_id,
            );

            $token = JWT::encode($payload, env("JWT_SECRET"), "HS256");

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

            $token = JWT::encode($payload, env("JWT_SECRET"), "HS256");
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
        $factory = (new Factory)->withServiceAccount(env('PATH_FIREBASE_TOKEN', 'D:\Workspace\laravel\php_project\serviceAccount.json'));
        $auth = $factory->createAuth();
        $now_seconds = time();

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
        }

        $payload = array(
            "iat" => $now_seconds,
            "exp" => $now_seconds + (60 * 60 * 24 * 365),  // Maximum expiration time is one hour
            "uid" => $user->id,
        );

        $token = JWT::encode($payload, env("JWT_SECRET"), "HS256");

        return response()->json([
            'status' => 200,
            'token' => $token,
        ], 200);
    }
}