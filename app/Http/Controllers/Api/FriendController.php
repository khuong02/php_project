<?php

namespace App\Http\Controllers\Api;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Cloud\Storage\Connection\Rest;

class FriendController extends Controller
{
    public function addFriend(Request $request)
    {
        try {
            $request->validate([
                'user_id_second' => 'required'
            ]);
            $user_id_first = $request->get('user_id');
            $user_id_second = $request->get('user_id_second');
            // kiểm tra xem đã là bạn bè của nhau chưa
            $checkAlreadyFriend = Friend::getVisitFriends($user_id_first, $user_id_second);

            // status 0 = đã xóa kết bạn
            // status 1 = đang đề nghị kết bạn
            // status 2 = bạn bè của nhau

            // dáng lẽ sẽ có thêm status 3 = bị từ chối ^_^
            if (empty($checkAlreadyFriend)) {
                Friend::create(
                    [
                        'user_id_first' => $request->get('user_id'),
                        'user_id_second' => $request->get('user_id_second'),
                        'status' => 1
                    ]
                );
            }
            if ($checkAlreadyFriend[0]->status == 0) {
                DB::update("update table_friends set status = 1 where user_id_first = ? and user_id_second = ? or user_id_first = ? and user_id_second = ?", [$user_id_first, $user_id_second, $user_id_second, $user_id_first]);
            }
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'friend request successfully!'
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'serve erro'
                ],
                500
            );
        }
    }

    public function respond2FriendRequest(Request $request)
    {
        try {
            $request->validate([
                'user_id_second' => 'required',
                'accept' => 'required'
            ]);
            $user_id_first = $request->get('user_id');
            $user_id_second = $request->get('user_id_second');

            if ($request->get('accept')) {
                Friend::acceptFriend($user_id_first, $user_id_second);
            }

            if (!$request->get('accept')) {
                Friend::denialFriend($user_id_first, $user_id_second);
            }

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'invitation response successful!'
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => 'invitation response fail!'
                ],
                400
            );
        }
    }


    public function unFriend(Request $request)
    {
        try {
            $request->validate([
                'user_id_second' => 'required'
            ]);
            $user_id_first = $request->get('user_id');
            $user_id_second = $request->get('user_id_second');
            $checkAlreadyFriend = Friend::getVisitFriends($user_id_first, $user_id_second);

            if (empty($checkAlreadyFriend)) {
                return response()->json(
                    [
                        'status' => 400,
                        'message' => 'what is it to each other that unfriend each other !!!'
                    ],
                    400
                );
            }

            Friend::unfriend($user_id_first, $user_id_second);
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'unfriend friend successfully!'
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => 'unfriend fail !!!'
                ],
                400
            );
        }
    }

    public function getListFriend(Request $request)
    {
        try {
            $user_req = $request->get('user_id');
            $listIdFriend = Friend::getListFriendByIdUser($user_req);
            $arrayIdFriend = [];
            for ($i = 0; $i < count($listIdFriend); $i++) {
                $check  = ($listIdFriend[$i]->user_id_second == $user_req);
                if (!$check) {
                    array_push($arrayIdFriend, $listIdFriend[$i]->user_id_second);
                }
            }
            for ($j = 0; $j < count($listIdFriend); $j++) {
                $check2  = ($listIdFriend[$j]->user_id_first == $user_req);
                if (!$check2) {
                    array_push($arrayIdFriend, $listIdFriend[$j]->user_id_first);
                }
            }
            if (empty($arrayIdFriend)) {
                return response()->json(
                    [
                        'status' => 200,
                        'listUser' => []
                    ],
                    200
                );
            }
            $listUser = [];
            $user = new User();
            foreach ($arrayIdFriend as $id_user) {
                $userItem = $user->getById($id_user);
                array_push($listUser, $userItem[0]);
            }

            return response()->json(
                [
                    'status' => 200,
                    'listUser' => $listUser
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'listUser' => []
                ],
                400
            );
        }
    }

    public function getListFriendPending(Request $request)
    {
        try {
            $user_req = $request->get('user_id');
            $listIdFriend = Friend::getListFriendByIdUserPending($user_req);
            $arrayIdFriend = [];
            for ($j = 0; $j < count($listIdFriend); $j++) {
                $check2  = ($listIdFriend[$j]->user_id_first == $user_req);
                if (!$check2) {
                    array_push($arrayIdFriend, $listIdFriend[$j]->user_id_first);
                }
            }
            if (empty($arrayIdFriend)) {
                return response()->json(
                    [
                        'status' => 200,
                        'listUser' => []
                    ],
                    200
                );
            }
            $listUser = [];
            $user = new User();
            foreach ($arrayIdFriend as $id_user) {
                $userItem = $user->getById($id_user);
                array_push($listUser, $userItem[0]);
            }

            return response()->json(
                [
                    'status' => 200,
                    'listUser' => $listUser
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'listUser' => []
                ],
                400
            );
        }
    }

    public function getUserStranger(Request $request)
    {
        try {
            $user_req = $request->get('user_id');
            $listUser = DB::select(
                'select *
                from users where id  != ?
                order by RAND()
                limit ?
                offset ?',
                [$user_req, 5, 0]
            );
            $listResutl = [];

            foreach ($listUser as $itemUser) {
                $check = Friend::getVisitFriends($user_req, $itemUser->id);
                $itemResutl = [];
                if (empty($check)) {
                    $itemResutl = [
                        'id' => $itemUser->id,
                        'name' => $itemUser->username,
                        'avatar' => $itemUser->avatar,
                        'email' => $itemUser->email,
                        'status' => 0
                    ];
                    array_push($listResutl, $itemResutl);
                }
                if (!empty($check)) {
                    if ($check[0]->status == 0) {
                        $itemResutl = [
                            'id' => $itemUser->id,
                            'name' => $itemUser->username,
                            'avatar' => $itemUser->avatar,
                            'email' => $itemUser->email,
                            'status' => 0
                        ];
                        array_push($listResutl, $itemResutl);
                    }
                    // if ($check[0]->status == 1) {
                    //     $itemResutl = [
                    //         'id' => $itemUser->id,
                    //         'name' => $itemUser->username,
                    //         'avatar' => $itemUser->avatar,
                    //         'email' => $itemUser->email,
                    //         'status' => 1
                    //     ];
                    //     array_push($listResutl, $itemResutl);
                    // }
                    // if ($check[0]->status == 2) {
                    //     $itemResutl = [
                    //         'id' => $itemUser->id,
                    //         'name' => $itemUser->username,
                    //         'avatar' => $itemUser->avatar,
                    //         'email' => $itemUser->email,
                    //         'status' => 2
                    //     ];
                    //     array_push($listResutl, $itemResutl);
                    // }
                }
            }
            return response()->json(
                [
                    'status' => 200,
                    'listusers' => $listResutl
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'listusers' => []
                ],
                400
            );
        }
    }
}
