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
            if ($accountAdmin !== null && $accountAdmin->deleted_at === null) {
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
                    'current_password' => 'string|max:255',
                    'password' => 'string|max:255',
                    'cf_password' => 'same:password'
                ]
            );
            $cookie = $this->getCookie('token');
            $private_key = env('JWT_SECRET');
            $decodoJwt = $this->jwt->decodedJwt($cookie, $private_key);

            $id = $decodoJwt->uid;
            $userAdmin = UserAdmin::where('id', $id)->first();
            if ($request->hasFile('avatar')) {
                if ($request->current_password !== '') {
                    if ($this->validPassword($id, $request->current_password)) {
                        $userAdmin->update([
                            'username' => $request->username,
                            'email' => $request->email,
                            'avatar' => $this->uploadFile->storeUploads($request, 'avatar'),
                            'password' => Hash::make($request->password)
                        ]);
                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => "password current Incorrect"
                        ], 400);
                    }
                } else {
                    $userAdmin->update([
                        'username' => $request->username,
                        'email' => $request->email,
                        'avatar' => $this->uploadFile->storeUploads($request, 'avatar')
                    ]);
                }
            } else {
                if ($request->current_password !== '') {
                    if ($this->validPassword($id, $request->current_password)) {
                        $userAdmin->update([
                            'username' => $request->username,
                            'email' => $request->email,
                            'password' => Hash::make($request->password)
                        ]);
                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => "password current Incorrect"
                        ], 400);
                    }
                } else {
                    $userAdmin->update([
                        'username' => $request->username,
                        'email' => $request->email,
                    ]);
                }
            }
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


    private function validPassword($id_user, $password)
    {
        $user = UserAdmin::where('id', $id_user)->first();
        if (Hash::check($password, $user->password)) {
            return true;
        }
        return false;
    }
}
