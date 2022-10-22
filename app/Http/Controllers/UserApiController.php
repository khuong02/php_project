<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = new User();
        $id = $request->get('user_id');
        return response()->json([
            'status' => 200,
            'data' => $user->getById($id)[0]
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = new User();
            $id = $request->get('user_id');
            $data = $user->getById($id)[0];

            $data->username = $request->get('username');
            $data->cost = $request->get('cost');

            $user->updateUserName($data);

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