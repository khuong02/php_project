<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
use Facade\Ignition\DumpRecorder\Dump;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Support\Facades\DB;

class AccountUserManagementController extends Controller
{
    public function index(Request $request)
    {
        return view('content.account.account-management-user');
    }

    public function getUserList(Request $request)
    {
        $listUser = DB::table('users')->join('accounts', 'users.id', '=', 'accounts.user_id')
            ->select('users.*', 'accounts.deleted_at')->get()->toArray();
        return response()->json(
            [
                'status' => 200,
                'users' => $listUser
            ],
            200
        );
    }

    public function getUiUpdateUser(Request $request, $id)
    {
        try {
            $user = new User();
            $accountEdit = $user->getByIdv2($id);
            if (empty($accountEdit)) {
                return view('content.pages.pages-misc-error');
            }
            return view("content.account.edit-account-user", ['accountEdit' => $accountEdit]);
        } catch (\Throwable $th) {
            return view('content.pages.pages-misc-under-maintenance');
        }
    }

    public function getUser(Request $request, $id)
    {
        try {
            $user = new User();
            $accountEdit = $user->getByIdv2($id);
            if (empty($accountEdit)) {
                return response()->json(
                    [
                        'status' => 404,
                        'message' => 'user not found !!!',
                        'data' => []
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'get user successfully',
                    'data' => $accountEdit
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'serve erro !!!',
                    'data' => []
                ],
                500
            );
        }
    }


    public function update(Request $request)
    {
        try {
            $user = new User();
            if ($request->status == 1) {
                Account::withTrashed()->where('user_id', '=', $request->idUpdate)->restore();
                $userUpdate = $user->where('id', '=', $request->idUpdate);
                $userUpdate->update(
                    [
                        'cost' => $request->cost
                    ]
                );
            } else {
                $userUpdate = $user->where('id', '=', $request->idUpdate);
                $userUpdate->update(
                    [
                        'cost' => $request->cost
                    ]
                );
                Account::where('user_id', '=', $request->idUpdate)->delete();
            }
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'update account successfully',
                    'data' => $user->getByIdv2($request->idUpdate)
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => 'update account failure',
                    'data' => []
                ],
                400
            );
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $account = Account::find($id);
            if (isset($account)) {
                Account::where('user_id', '=', $id)->delete();
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'delete account successfully'
                    ],
                    200
                );
            };
            return response()->json([
                'status' => 403,
                'message' => 'account not found',
            ], 403);
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
