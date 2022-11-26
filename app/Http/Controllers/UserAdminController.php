<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\UserAdmin;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\validator;
use Illuminate\Support\Facades\Redirect;

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
                    'status' => 201,
                    'message' => 'create account successful',
                ],
                201
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

    public function loginAdmin(Request $request)
    {
        $now_seconds = time();
        try {
            $request->validate(
                [
                    'email' => 'required|string|email|max:255',
                    'password' => 'min:3|required|string|max:255',
                ]
            );
            $accountAdmin = $this->selectAccountAdmin($request->email);
            if ($accountAdmin !== null) {
                if (Hash::check($request->password, $accountAdmin->password)) {
                    $payload = array(
                        "roles" => 1,
                        "iat" => $now_seconds,
                        "uid" => $accountAdmin->id,
                    );
                    $token = JWT::encode($payload, env("JWT_SECRET"), "HS256");
                    $oneday = 60 * 24;
                    $this->setCookie('token', $token, $oneday);
                    return redirect('/');
                } else {
                    return response()->json(
                        [
                            'msg' => 'sai mat khau',
                        ],
                        400
                    );
                }
            } else {
                return response()->json(
                    [
                        'msg' => 'khong tim thay email',
                    ],
                    400
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'msg' => 'login erro',
                ],
                403
            );
        }
    }


    private function setCookie($cookieName, $cookie, $timeExp)
    {
        try {
            Cookie::queue($cookieName, $cookie, $timeExp);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function delCookie($cookieName)
    {
        try {
            Cookie::forget($cookieName);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function adminLogOut()
    {
        $this->delCookie('token');
        return redirect('/auth/login');
    }

    private function selectAccountAdmin($email)
    {
        return DB::table('table_admins')->where('email', $email)->first();
    }
}
