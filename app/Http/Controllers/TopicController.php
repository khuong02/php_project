<?php

namespace App\Http\Controllers;

use App\Models\Quizz;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function getTopics(Request $request)
    {
        try {
            $page = $request['page'] || 1;
            $limit = $request['limit'] || 10;
            $quizz = new Quizz();


            return response()->json([
                'status' => 200,
                'data' => $quizz->getList($page, $limit),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'msg' => 'Get question failed'
            ], 500);
        }
    }
}