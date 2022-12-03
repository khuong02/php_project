<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAdmin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    private $id, $username, $email, $password, $avatar;

    public $table = 'table_admins';

    protected $fillable = [
        'username',
        'email',
        'avatar',
        'password'
    ];

    public function setProfile($req)
    {
        return DB::insert('insert into table_admins (username, email, avatar) values (?, ?, ?)', [$req["username"], $req["email"], $req["avatar"]]);
    }

    public function findAdminByEmail($email)
    {
        return DB::table('table_admins')->where('email', $email)->first();
    }

    public function getByID($id)
    {
        return DB::table('table_admins')->where('id', $id)->first();
    }

    public function updateAdminProfile($data)
    {

        return DB::update('update table_admins set username = ?,avatar = ? where id = ?', [$data->username, $data->avatar, $data->id]);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            "uid" => $this->id,
        ];
    }
}
