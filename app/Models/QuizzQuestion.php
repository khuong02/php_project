<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class QuizzQuestion extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'quizz_id',
        'difficulty_id',
        'question',
    ];

    public function getList($quizz_id, $difficulty_id)
    {
        return DB::select(
            'select *
            from table_quizz_questions
            inner join table_quizz_question_answers
            on table_quizz_questions.id = table_quizz_question_answers.question_id and quizz_id = ? and difficulty_id = ?',
            [$quizz_id, $difficulty_id]
        );
    }
}