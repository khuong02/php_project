<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\QuizzQuestion;
use Illuminate\Support\Facades\Validator;
use App\Models\Quizz;
use App\Models\Difficulty;
use Carbon\Carbon;

class Questions extends Controller
{
    public function index()
    {
        // $listQuestion = DB::select("SELECT table_quizz_questions.id,table_quizzes.name as 'topic',table_difficulties.name as 'difficult',question FROM php_project.table_quizz_questions,php_project.table_quizzes,php_project.table_difficulties WHERE quizz_id = table_quizzes.id and difficulty_id = php_project.table_difficulties.id")->paginate(5);
        // $listQuestion = DB::table('table_quizz_questions')->paginate(10);
        // return view('content.question.questions',['listQuestions'=>$listQuestion]);
        return view('content.question.questions');
    }

    public function getQuestionList()
    {
        // $listQuestion = DB::select("SELECT table_quizz_questions.id,table_quizzes.name as 'topic',table_difficulties.name as 'difficult',question, table_quizz_questions.deleted_at FROM php_project.table_quizz_questions,php_project.table_quizzes,php_project.table_difficulties WHERE quizz_id = table_quizzes.id and difficulty_id = php_project.table_difficulties.id");
        $listQuestion = DB::select("SELECT php_project_test.table_quizz_questions.id,table_quizzes.name as 'topic',table_difficulties.name as 'difficult',question, table_quizz_questions.deleted_at 
                                FROM php_project_test.table_quizz_questions,php_project_test.table_quizzes,php_project_test.table_difficulties 
                                WHERE quizz_id = table_quizzes.id and difficulty_id = php_project_test.table_difficulties.id");
        $listTopic = Quizz::all();
        $listDiff = Difficulty::all();
        return response()->json([
            'questions' => $listQuestion,
            'topics' => $listTopic,
            'difficults' => $listDiff,
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
            'status' => 403,
            'message' => 'Không thể tìm thấy câu hỏi',
        ], 403);
    }
}
