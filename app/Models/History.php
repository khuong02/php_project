<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    public $table = 'table_histories';

    protected $fillable = [
        'user_id',
        'time',
        'quantity',
        'score'
    ];

    public function getByUserID($id)
    {
        return DB::select("SELECT * FROM table_histories WHERE user_id = ?", [$id]);
    }

    static function set($data)
    {
        return DB::insert('INSERT INTO table_histories (user_id, quantity, time, score) VALUES (?,?,?,?);', [$data['userID'], $data['quantity'], $data['time'], $data['score']]);
    }
}
