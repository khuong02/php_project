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
        return DB::insert('INSERT INTO leaderboard (user_id, quantity, time, score) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE quantity = ?, time = ?, score = ?;', [$data['userID'], $data['quantity'], $data['time'], $data['score'], $data['quantity'], $data['time'], $data['score']]);
    }

    function GetByUserID($id)
    {
        return DB::select("SELECT * FROM leaderboard WHERE user_id = ?", [$id]);
    }

    function getList()
    {
        $query = "SELECT user_id,php_project.leaderboard.time , quantity,score, username,email, avatar
        FROM php_project.leaderboard
        INNER JOIN php_project.users
        where user_id = php_project.users.id
        order by score,quantity,php_project.leaderboard.time
        limit 10;";
        return DB::select($query);
    }
}