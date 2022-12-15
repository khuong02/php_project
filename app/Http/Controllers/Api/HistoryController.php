<?php

namespace App\Http\Controllers\Api;

use App\Models\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function getListHistory(Request $request)
    {
        try {
            $id = $request->get('user_id');
            if ($id == null) {
                throw new \Throwable;
            }
            $dataResutl = History::getByUserID($id);

            if (empty($dataResutl)) {
                return response()->json(
                    [
                        'status' => 200,
                        'data' => []
                    ]
                );
            }

            return response()->json(
                [
                    'status' => 200,
                    'data' => $dataResutl
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'data' => []
                ]
            );
        }
    }
}
