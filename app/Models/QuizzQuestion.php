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

    public $table = 'table_quizz_questions';
    protected $fillable = [
        'question',
        'quizz_id',
        'difficulty_id',
    ];

    public function getList($page, $limit, $quizz_id, $difficulty_id)
    {
        return DB::select(
            'select *
            from table_quizz_questions
            where quizz_id = ? and difficulty_id = ?
            order by RAND()
            limit ?
            offset ?',
            [$quizz_id, $difficulty_id, $limit, $page - 1]
        );
    }
}
