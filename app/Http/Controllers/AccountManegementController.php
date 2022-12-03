<?php

namespace App\Http\Controllers;

use App\Models\UserAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\JwtController;


class AccountManegementController extends Controller
{
    private $jwt;
    public function __construct()
    {
        $this->jwt = new JwtController();
    }

    public function Index(Request $request)
    {
        try {
            $private_key = env("JWT_SECRET");
            $cookie = $this->getCookie('token');
            $jwtToken = $this->jwt->decodedJwt($cookie, $private_key);
            $userAdmin = new UserAdmin();
            $admiProfile = $userAdmin->getByID($jwtToken->uid);
            return view('content.account.account-settings-account', ['admiProfile' => $admiProfile]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getCookie($cookieName)
    {
        return cookie::get($cookieName);
    }

    public function accountManegementAdmin(Request $request)
    {
        $private_key = env("JWT_SECRET");
        $cookie = $this->getCookie('token');
        $jwtToken = $this->jwt->decodedJwt($cookie, $private_key);

        $listAccontAdmin = DB::select('select * from table_admins where id != ? and id != 1', [$jwtToken->uid]);
        return view('content.account.account-management-admin', ['listAcc' => $listAccontAdmin]);
    }

    public function accountManegementUser(Request $request)
    {
        return view('content.tables.tables-basic');
    }

    public function editAccountAdmin(Request $request, $id)
    {
        try {

            $userAdmin = new UserAdmin();
            $accountEdit = $userAdmin->getByID($id);
            if (empty($accountEdit)) {
                return view('content.pages.pages-misc-under-maintenance');
            }
            return view("content.account.edit-account", ['accountEdit' => $accountEdit]);
        } catch (\Throwable $th) {
            return view('content.pages.pages-misc-under-maintenance');
        }
    }

    public function editAccountAdminPost(Request $request)
    {
        try {
            if ($request->status == 1) {
                UserAdmin::withTrashed()->where('id', '=', $request->idUpdate)->restore();
            } else {
                UserAdmin::where('id', '=', $request->idUpdate)->delete();
            }
            return response()->json(
                [
                    'erro' => false,
                    'message' => 'update account successfully'
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'erro' => false,
                    'message' => 'update account failure'
                ],
                400
            );
        }
    }
    public function deleteAccountAdmin(Request $request)
    {
        try {
            if (UserAdmin::where('id', '=', $request->deleteIdValue)->delete()) {
                return response()->json(
                    [
                        'erro' => false,
                        'message' => 'delete account successfully'
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'erro' => true,
                        'message' => 'The account has been deleted before'
                    ],
                    400
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'erro' => true,
                    'message' => 'server erro'
                ],
                500
            );
        }
    }
}
