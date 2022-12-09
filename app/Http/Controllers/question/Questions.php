<?php

namespace App\Http\Controllers\question;

use Carbon\Carbon;
use App\Models\Quizz;
use App\Models\Answers;
use App\Models\Difficulty;
use Illuminate\Http\Request;
use App\Models\QuizzQuestion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Questions extends Controller
{
    public function index()
    {
        return view('content.question.questions');
    }

    public function getQuestionList()
    {
        $listQuestion = DB::select("SELECT table_quizz_questions.id,table_quizzes.name as 'topic',table_difficulties.name as 'difficult',question, table_quizz_questions.deleted_at 
                                FROM table_quizz_questions,table_quizzes,table_difficulties 
                                WHERE quizz_id = table_quizzes.id and difficulty_id = table_difficulties.id");
        $listTopic = Quizz::all();
        $listDiff = Difficulty::all();
        return response()->json([
            'questions' => $listQuestion,
            'topics' => $listTopic,
            'difficults' => $listDiff
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|unique:table_quizz_questions',
                'quizz_id' => 'required',
                'difficulty_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->messages(),
                ], 400);
            }

            $question = new QuizzQuestion();
            $question->question = $request->input('question');
            $question->quizz_id = $request->input('quizz_id');
            $question->difficulty_id = $request->input('difficulty_id');

            $question->save();

            return response()->json([
                'status' => 201,
                'message' => 'Question create successfully!',
            ], 201);
        } catch (e) {
            return response()->json([
                'status' => 400,
                'message' => e->messages(),
            ], 400);
        }
    }

    public function delete(Request $request, $id)
    {
        $question = QuizzQuestion::find($id);
        if (isset($question)) {
            $question->deleted_at = Carbon::now();
            $question->update();
            return response()->json([
                'status' => 200,
                'message' => 'Delete succesfully',
            ], 200);
        }
        return response()->json([
            'status' => 404,
            'message' => 'Question not found !!!',
        ], 404);
    }

    public function getUiUpdateQuestion(Request $request, $id)
    {
        try {
            $question = new QuizzQuestion();
            $answer = new Answers();
            $questionData = $question->getquestionById($id);
            if (empty($questionData)) {
                return view('content.pages.pages-misc-error');
            }
            $listTopic = Quizz::all()->toArray();
            $listDiff = Difficulty::all()->toArray();
            $listAnswer = $answer->getAnswersByQuestionID($id);
            return view('content.question.detail-questions', ['questionData' => $questionData, 'listTopic' => $listTopic, 'listDiff' => $listDiff, 'listAnswer' => $listAnswer]);
        } catch (\Throwable $th) {
            return view('content.pages.pages-misc-under-maintenance');
        }
    }


    public function update(Request $request)
    {
        try {
            $request->validate([
                'question' => 'required|string|min:3|max:255',
                'topic' => 'required',
                'diff' => 'required',
                'Answer1' => 'required|string|max:255',
                'Answer2' => 'required|string|max:255',
                'Answer3' => 'required|string|max:255',
                'correctAnswer' => 'required|string|max:255'
            ]);
            $answer = new Answers();
            $validatorAnswer1 = $answer->checkAnwerCurrentlyExistingByQuestionID($request->idUpdate, $request->Answer1);
            $validatorAnswer2 = $answer->checkAnwerCurrentlyExistingByQuestionID($request->idUpdate, $request->Answer2);
            $validatorAnswer3 = $answer->checkAnwerCurrentlyExistingByQuestionID($request->idUpdate, $request->Answer3);
            $validatorCorrectAnswer = $answer->checkAnwerCurrentlyExistingByQuestionID($request->idUpdate, $request->correctAnswer);
            // check xem cau hoi do da co 1 trong nhung dap an moi request
            if ($validatorAnswer1 || $validatorAnswer2 || $validatorAnswer3 || $validatorCorrectAnswer) {
                return response()->json(
                    [
                        'status' => 400,
                        'message' => 'update question fail !!!',
                    ],
                    400
                );
            }
            // dd($request);
            $question = new QuizzQuestion();
            $questionData = $question->getquestionById($request->idUpdate);
            if ($request->status == 1) {
                QuizzQuestion::where('id', '=', $request->idUpdate)->update(
                    [
                        'question' => $request->question,
                        'quizz_id' => $request->topic,
                        'difficulty_id' => $request->diff
                    ]
                );
                QuizzQuestion::withTrashed()->where('id', '=', $request->idUpdate)->restore();
            } else {
                QuizzQuestion::where('id', '=', $request->idUpdate)->update(
                    [
                        'question' => $request->question,
                        'quizz_id' => $request->topic,
                        'difficulty_id' => $request->diff
                    ]
                );
                QuizzQuestion::where('id', '=', $request->idUpdate)->delete();
            }
            $questionData = $question->getquestionById($request->idUpdate);
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'update question succesfully',
                    'questionData' => $questionData
                ],
                200
            );
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(
                [
                    'status' => 500,
                    'message' => 'serve erro'
                ],
                500
            );
        }
    }
}
