<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answers extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = 'table_quizz_question_answers';

    protected $fillable = [
        'question_id',
        'answer',
        'correct_answer'
    ];

    public function getAnswersByQuestionID($question_id)
    {
        return DB::table('table_quizz_question_answers')->where('question_id', '=', $question_id)->get();
    }

    public function checkAnwerCurrentlyExistingByQuestionID($question_id, $answer_check)
    {
        $count = DB::select("select count(*) as 'count' from table_quizz_question_answers 
        where table_quizz_question_answers.question_id = ? 
        and answer = ?", [$question_id, $answer_check]);
        if ($count[0]->count) {
            return true;
        }
        return false;
    }
}
