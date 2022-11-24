<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\UserAdmin;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public function createAccountAdmin(Request $request)
    {
        $avatarDF = 'https://res.cloudinary.com/didqd2uyc/image/upload/v1668469798/hluc0oca3d2kke3ifcvr.jpg';
        try {
            $validate =  $request->validate(
                [
                    'username' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'min:3|required|string|confirmed'
                ]
            );
            $userAdmin = UserAdmin::create(
                [
                    'username' => $validate['username'],
                    'email' => $validate['email'],
                    'password' => Hash::make($validate['password']),
                    'avatar' => $avatarDF
                ]
            );

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'create account successful',
                ],
                200
            );
        } catch (Exception $err) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => $err->getMessage(),
                ],
                400
            );
        }
    }
}
