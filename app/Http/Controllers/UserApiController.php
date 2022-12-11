<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeaderBoard;
use App\Models\UserSetting;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = new User();
        $leaderboard = new LeaderBoard();

        $id = $request->get('user_id');

        $myLB = $leaderboard->GetByUserID($id);
        if(!empty($myLB)){
            return response()->json([
                'status' => 200,
                'data' => [
                    'user'=> $user->getById($id)[0],
                    'score'=>$myLB[0]->score,
                ],
            ], 200);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'user'=> $user->getById($id)[0],
                'score'=> 0,
            ],
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = new User();
            $id = $request->get('user_id');
            $data = $user->getById($id)[0];

            if($request->get('username') !== null){
                $data->username = $request->get('username');
            }
            $data->cost = $request->get('cost');

            if($request->get('avatar')!==null){
                $data->avatar = $request->get('avatar');
            }

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

    public function updateSetting(Request $request)
    {
        try {
            $setting = new UserSetting();
            $id = $request->get('user_id');
            $mode = $request->get('mode') ?: 0;

            $setting->setUserSetting($id, $mode);

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

    public function buyCredit(Request $request){
        try{
        $id = $request->get('user_id');
        $cost = $request->get('cost');

        $user = new User();
        $user->addCredit($id,$cost);

        return response()->json([
            'status' => 200,
            'message' => "Buy credit successfully!",
        ], 200);
        }catch(e)
        {
            return response()->json([
                'status' => 400,
                'message' => "Buy credit fail!"
            ], 400);
        }
 }
}
