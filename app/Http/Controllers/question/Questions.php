<?php

namespace App\Http\Controllers\question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Questions extends Controller
{
  public function index()
  {
    // $listQuestion = DB::select("SELECT table_quizz_questions.id,table_quizzes.name as 'topic',table_difficulties.name as 'difficult',question FROM php_project.table_quizz_questions,php_project.table_quizzes,php_project.table_difficulties WHERE quizz_id = table_quizzes.id and difficulty_id = php_project.table_difficulties.id")->paginate(5);
    $listQuestion = DB::table('table_quizz_questions')->paginate(10);
    return view('content.question.questions',['listQuestions'=>$listQuestion]);
  }
}
