<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Difficulty extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = 'table_difficulties';
    protected $fillable = [
        'name',
    ];

    public function getDifficultyByID($id)
    {
        return DB::select('select * from table_difficulties where id = ?', [$id]);
    }
}
