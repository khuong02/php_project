<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeaderBoard;
use App\Models\Account;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = new User();
        $leaderboard = new LeaderBoard();
        $setting = new UserSetting();

        $id = $request->get('user_id');

        $myLB = $leaderboard->GetByUserID($id);
        $myST = $setting->getSetting($id);
        if(!empty($myLB)){
            return response()->json([
                'status' => 200,
                'data' => [
                    'user'=> $user->getById($id)[0],
                    'score'=>$myLB[0]->score,
                    'mode'=>$myST[0]->mode
                ],
            ], 200);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'user'=> $user->getById($id)[0],
                'score'=> 0,
                'mode'=>$myST[0]->mode,
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
 public function changePassword(Request $request){
    try{
        $validator = Validator::make($request->all(), [
            'oldpassword'=>"required|min:6",
            'newpassword' => 'required|min:6|required_with:cf_password|same:cf_password',
            'cf_password' => 'required|min:6'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->messages(),
            ], 400);
        }
        $account = new Account();


        $myAccount = $account->findAccountByEmail($request->email);

        if(empty($myAccount)){
            return response()->json([
                "status"=>404,
                "message"=>"You can't change password for this account!",
            ], 404);
        }

        if (!Hash::check($request->oldpassword, $myAccount->password)) {
            return response()->json([
                'status' => 400,
                'message' => 'Password does not match',
            ], 400);
        }

        $account->updatePassword($request->email,Hash::make($request->newpassword));

        return response()->json([
            'status'=>200,
            'message'=>"Change password successfully!",
        ], 200);
    }catch(e){
        return response()->json([
            'status'=>404,
            'message'=>e->messages(),
        ], 404);
    }
 }
}
