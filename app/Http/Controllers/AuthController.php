<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $accountModel = new Account();

        $account = $accountModel->findAccountByEmail($request->email);
        if (!Hash::check($request->password, $account->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password does not match',
            ], 400);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'user_id' => +$account->user_id,
        ];

        $token = Auth::guard('account_api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'status' => 200,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
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

        $token = Auth::login($account);
        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
}