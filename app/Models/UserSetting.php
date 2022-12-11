<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserSetting extends Model
{

    use HasFactory;

    public $table = "table_user_settings";
    protected $fillable = [
        'user_id',
        'mode'
    ];

    public function getSetting($id){
        return DB::select('select * from table_user_settings where user_id = ?', [$id]);
    }

    public function setUserSetting($user_id, $mode = 0)
    {
        DB::update("INSERT INTO table_user_settings (user_id) VALUES (?) ON DUPLICATE KEY UPDATE mode=?;", [$user_id, $mode]);
    }
}
