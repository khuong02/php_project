<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class QuizzQuestionAnswer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'question_id',
        'answer',
        'correct_answer',
    ];
}