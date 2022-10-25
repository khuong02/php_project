<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class QuizzQuestionAnswer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'question_id',
        'answer',
        'correct_answer',
    ];

    public function getAnswerByQuestionId($question_id)
    {
        return DB::select('select * from table_quizz_question_answers where question_id = ?', [$question_id]);
    }
}