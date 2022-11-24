<?php

namespace App\Http\Controllers;

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
            $data = [
                "userID" => $id,
                "score" => $score,
                "quantity" => $quantity,
                "time" => $time
            ];
            $leaderboard->Set($data);

            return response()->json([
                'status' => 200,
                'message' => "successfully!!",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => "Set failed!",
            ], 200);
        }
    }
}