<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use App\Models\Quizz;
use App\Models\QuizzQuestion;
use App\Models\QuizzQuestionAnswer;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function getQuestionAndAnswer(Request $request)
    {
        try {
            $page = $request['page'] ?: 1;
            $limit = (int)$request['limit'] ?: 10;
            $quizz_id = $request['quizz_id'];
            $difficulty_id = $request['difficulty_id'];

            $quizz = new Quizz();
            $question = new QuizzQuestion();
            $answer = new QuizzQuestionAnswer();
            $difficulty = new Difficulty();

            $quizzData = $quizz->getQuizzByID($quizz_id);
            $questions = $question->getList($page, $limit, $quizz_id, $difficulty_id);
            $difficultyData = $difficulty->getDifficultyByID($difficulty_id);
            $result = [];

            for ($i = 0; $i < count($questions); $i++) {
                $data = [];
                $answerData = $answer->getAnswerByQuestionId($questions[$i]->id);

                $data['category'] = $quizzData[0]->name;
                $data['id'] = $questions[$i]->id;
                $data['incorrectAnswers'] = [];
                $data['question'] = $questions[$i]->question;
                $data['difficulty'] = $difficultyData[0]->name;

                for ($j = 0; $j < count($answerData); $j++) {
                    if ($answerData[$j]->correct_answer == 1) {
                        $data['correctAnswer'] = $answerData[$j]->answer;
                    } else {
                        array_push($data['incorrectAnswers'], $answerData[$j]->answer);
                    }
                }

                array_push($result, $data);
            }

            return response()->json([
                'status' => 200,
                'data' => $result
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'msg' => 'Get question failed'
            ], 500);
        }
    }
}