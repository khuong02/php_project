<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Quizz extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
    ];

    public $table = 'table_quizzes';

    public function getQuizzByID($quizz_id)
    {
        return DB::select('select * from table_quizzes where id = ?', [$quizz_id]);
    }

    public function getList($page, $limit)
    {
        return DB::select(
            'select *
            from table_quizzes
            limit ?
            offset ?',
            [$limit, $page - 1]
        );
    }
}
