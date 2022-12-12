<?php

namespace App\Http\Controllers\web;

use Exception;
use App\Models\UserAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\JwtController;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Facades\Validator;



class AccountAdminManagementController extends Controller
{
    private $jwt;
    public function __construct()
    {
        $this->jwt = new JwtController();
    }

    public function index()
    {
        return view('content.account.account-management-admin');
    }

    public function getAdminList()
    {
        try {
            $private_key = env("JWT_SECRET");
            $cookie = $this->getCookie('token');
            $jwtToken = $this->jwt->decodedJwt($cookie, $private_key);
            $listUser = DB::select('select * from table_admins where id != ? and id != 1', [$jwtToken->uid]);
            return response()->json(
                [
                    'status' => 200,
                    'users' => $listUser
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 500,
                    'user' => []
                ],
                500
            );
        }
    }


    private function getCookie($cookieName)
    {
        return cookie::get($cookieName);
    }

    public  function delete(Request $request, $id)
    {
        try {
            $userAdmin = UserAdmin::where('id', $id)->first();
            if ($userAdmin->email == env("EMAIL_ADMIN")) {
                return response()->json(
                    [
                        'status' => 400,
                        'message' => 'You do not have permission to delete this account'
                    ],
                    400
                );
            }

            $cookie = $request->cookie('token');
            $private_key = env("JWT_SECRET");
            $decodoJwt = $this->jwt->decodedJwt($cookie, $private_key);
            $userAdmin = UserAdmin::where('id', $decodoJwt->uid)->first();
            if ($userAdmin->email !== env('EMAIL_ADMIN')) {
                return response()->json(
                    [
                        'status' => 400,
                        'message' => 'You do not have permission to delete this account'
                    ],
                    400
                );
            }
            UserAdmin::where('id', '=', $id)->delete();
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'delete account successfully'
                ],
                200
            );
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => 500,
                    'message' => 'server erro'
                ],
                500
            );
        }
    }


    public function store(Request $request)
    {
        try {
            $cookie = $request->token;
            $private_key = env("JWT_SECRET");
            $decodoJwt = $this->jwt->decodedJwt($cookie, $private_key);
            $userAdmin = UserAdmin::where('id', $decodoJwt->uid)->first();
            if ($userAdmin->email !== env('EMAIL_ADMIN')) {
                return response()->json(
                    [
                        'status' => 405,
                        'message' => "you do not have permission to create an admin account"
                    ],
                    405
                );
            }
            $validate = $request->validate(
                [
                    'username' => 'required|string|max:255|unique:table_admins',
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
                    'message' => 'Account create successfully!',
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


    public function getUiUpdateUser(Request $request, $id)
    {
        try {
            $userAdmin = new UserAdmin();
            $accountEdit = $userAdmin->getByID($id);
            if (empty($accountEdit)) {
                return view('content.pages.pages-misc-error');
            }
            return view('content.account.edit-account-admin', ['accountEdit' => $accountEdit]);
        } catch (\Throwable $th) {
            return view('content.pages.pages-misc-under-maintenance');
        }
    }


    public function update(Request $request)
    {
        try {
            $user = new UserAdmin();
            if ($request->status == 1) {
                UserAdmin::withTrashed()->where('id', '=', $request->idUpdate)->restore();
            } else {
                UserAdmin::where('id', '=', $request->idUpdate)->delete();
            }
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'update account successfully',
                    'data' => $user->getByID($request->idUpdate)
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'erro' => false,
                    'status' => 400,
                    'message' => 'update account failure',
                    'data' => []
                ],
                400
            );
        }
    }
}
