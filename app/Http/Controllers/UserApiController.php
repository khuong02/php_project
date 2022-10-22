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
}