<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\LeaderBoard;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    public function SetLeaderBoard(Request $request)
    {
        try {
            $id = $request->get('user_id');
            if ($id == null) {
                throw new \Throwable;
            }

            $time = $request['time'];
            $score = $request['score'];
            $quantity = $request['quantity'];

            $leaderboard = new LeaderBoard();

            $old_data = $leaderboard->GetByUserID($id);

            $data = [
                "userID" => $id,
                "score" => $score,
                "quantity" => $quantity,
                "time" => $time
            ];
            History::create(
                [
                    'user_id' => $data['userID'],
                    'time' => $data['time'],
                    'quantity' => $data['quantity'],
                    'score' => $data['score']
                ]
            );
            if (count($old_data) > 0 && $old_data[0]->score > $score) {
                return response()->json([
                    'status' => 200,
                    'message' => "successfully!!",
                ], 200);
            }

            if (count($old_data) > 0 && $old_data[0]->score == $score && $old_data[0]->quantity < $quantity) {
                return response()->json([
                    'status' => 200,
                    'message' => "successfully!!",
                ], 200);
            }

            if (count($old_data) > 0 && $old_data[0]->score == $score && $old_data[0]->quantity == $quantity && $old_data[0]->time <= $time) {
                return response()->json([
                    'status' => 200,
                    'message' => "successfully!!",
                ], 200);
            }

            $leaderboard->Set($data);

            return response()->json([
                'status' => 200,
                'message' => "successfully!!",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getList(Request $request)
    {
        try {
            $id = $request->get('user_id');
            if ($id == null) {
                throw new \Throwable;
            }

            $leaderboard = new LeaderBoard();
            return response()->json(
                [
                    'status' => 200,
                    'data' => $leaderboard->getList(),
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => "Get failed!",
            ], 500);
        }
    }
}
