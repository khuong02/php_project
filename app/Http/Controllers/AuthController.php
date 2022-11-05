<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
}