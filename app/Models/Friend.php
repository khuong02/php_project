<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Friend extends Model
{
    use HasFactory;

    public $table = 'table_friends';

    protected $fillable = [
        'user_id_first',
        'user_id_second',
        'status'
    ];

    static function unfriend($user_id_first, $user_id_second)
    {
        DB::update("update table_friends set status = 0 where user_id_first = ? and user_id_second = ? or user_id_first = ? and user_id_second = ?", [$user_id_first, $user_id_second, $user_id_second, $user_id_first]);
    }

    static function acceptFriend($user_id_first, $user_id_second)
    {
        DB::update("update table_friends set status = 2 where user_id_first = ? and user_id_second = ? or user_id_first = ? and user_id_second = ?", [$user_id_first, $user_id_second, $user_id_second, $user_id_first]);
    }

    static function denialFriend($user_id_first, $user_id_second)
    {
        DB::update("update table_friends set status = 0 where user_id_first = ? and user_id_second = ? or user_id_first = ? and user_id_second = ?", [$user_id_first, $user_id_second, $user_id_second, $user_id_first]);
    }

    static function getVisitFriends($user_id_first, $user_id_second)
    {
        return DB::select("select * from table_friends where user_id_first = ? and user_id_second = ? or user_id_first = ? and user_id_second = ?", [$user_id_first, $user_id_second, $user_id_second, $user_id_first]);
    }

    static function getListFriendByIdUser($user_id)
    {
        return DB::select("select * from table_friends where user_id_first = ? and status = 2 or user_id_second = ? and status = 2", [$user_id, $user_id]);
    }

    static function getListFriendByIdUserPending($user_id)
    {
        return DB::select("select * from table_friends where user_id_second = ? and status = 1", [$user_id]);
    }
}
