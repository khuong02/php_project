<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\UserSetting;
use Exception;
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
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $accountModel = new Account();

            $account = $accountModel->findAccountByEmail($request->email);
            if (!Hash::check($request->password, $account->password)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Password does not match',
                ], 400);
            }

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'user_id' => +$account->user_id,
            ];

            JWTAuth::factory()->setTTL(60 * 24 * 365);

            $token = Auth::guard('account_api')->attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized',
                ], 401);
            }

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
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'min:3|required_with:cf_password|same:cf_password',
                'cf_password' => 'min:3'
            ]);



            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
            ]);

            UserSetting::create([
                'user_id' => $user->id,
            ]);

            $account = Account::create([
                'user_id' => $user->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            JWTAuth::factory()->setTTL(60 * 24 * 365);

            $token = Auth::login($account);
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
        $factory = (new Factory)->withServiceAccount(env('PATH_FIREBASE_TOKEN','D:\Workspace\laravel\php_project\serviceAccount.json'));
        $auth = $factory->createAuth();

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
        $customToken = $auth->createCustomToken($user->id, [], 'P365D');
        $customTokenString = $customToken->toString();

        return response()->json([
            'status' => 200,
            'token' => $customTokenString,
        ], 200);
    }
}