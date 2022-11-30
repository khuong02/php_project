<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\JwtController;

class UserAdminController extends Controller
{
    private $jwt;
    private $uploadFile;
    public function __construct()
    {
        $this->jwt = new JwtController();
        $this->uploadFile = new FileUploadController();
    }

    public function createAccountAdmin(Request $request)
    {
        try {
            $validate =  $request->validate(
                [
                    'username' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'password' => 'min:3|required|string|confirmed'
                ]
            );
            UserAdmin::create(
                [
                    'username' => $validate['username'],
                    'email' => $validate['email'],
                    'password' => Hash::make($validate['password'])
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

            $Admin = new UserAdmin();
            $accountAdmin = $Admin->findAdminByEmail($request->email);

            if ($accountAdmin !== null) {
                if (Hash::check($request->password, $accountAdmin->password)) {
                    $payload = array(
                        "roles" => 1,
                        "iat" => $now_seconds,
                        "uid" => $accountAdmin->id,
                    );
                    $private_key = env("JWT_SECRET");
                    $token = $this->jwt->encodeJwt($payload, $private_key);
                    $oneday = 60 * 24;
                    $this->setCookie('token', $token, $oneday);
                    return response()->json(
                        [
                            'erro' => false,
                            'message' => 'Logged in successfully',
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'erro' => true,
                            'message' => 'Incorrect password',
                        ],
                        400
                    );
                }
            } else {
                return response()->json(
                    [
                        'erro' => true,
                        'message' => 'Not find the email',
                    ],
                    400
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'erro' => true,
                    'message' => 'server erro',
                ],
                500
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
            Cookie::queue(Cookie::forget($cookieName));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getCookie($cookieName)
    {
        return cookie::get($cookieName);
    }

    public function adminLogOut(Request $request)
    {
        try {
            $this->delCookie('token');;
            return redirect('/auth/login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function upProfile(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'string|max:255',
                    'email' => 'string|email|max:255',
                ]
            );
            $cookie = $this->getCookie('token');
            $private_key = env('JWT_SECRET');
            $decodoJwt = $this->jwt->decodedJwt($cookie, $private_key);

            $id = $decodoJwt->uid;
            $userAdmin = UserAdmin::where('id', $id)->first();
            if ($request->hasFile('avatar')) {
                $userAdmin->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'avatar' => $this->uploadFile->storeUploads($request, 'avatar')
                ]);
            }
            $userAdmin->update([
                'username' => $request->username,
                'email' => $request->email,
            ]);
            $userAdmin->save();
            return response()->json([
                'status' => 200,
                'message' => "update successfully!"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 500,
                "message" => "update failed!"
            ], 500);
        }
    }
}
