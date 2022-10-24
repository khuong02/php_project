<?php

namespace App\Http\Controllers;

use App\Models\QuizzQuestion;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function getQuestionAndAnswer(Request $request)
    {
        $question = new QuizzQuestion();

        $result = $question->getList(1, 1);

        return response()->json([
            'status' => 200,
            'data' => $result
        ]);
    }
}