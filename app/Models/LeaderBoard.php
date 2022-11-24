<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeaderBoard extends Model
{
    use HasFactory;
    private $quantity, $time, $userID, $score;

    protected $fillable = [
        'user_id',
        'quantity',
        'time',
        'score',
    ];

    function Set($data)
    {
        return DB::insert('insert into leaderboard (user_id, quantity, time, score) values (?, ?, ?, ?)', [$data['userID'], $data['quantity'], $data['time'], $data['score']]);
    }
}