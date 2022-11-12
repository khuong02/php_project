<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Http\Request;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    private $id, $username, $cost, $email, $avatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'avatar',
    ];

    public function setProfile($req)
    {
        return DB::insert('insert into users (username, email, avatar) values (?, ?, ?)', [$req["username"], $req["email"], $req["avatar"]]);
    }

    public function findUserByEmail($email)
    {
        return DB::table('users')->where('email', $email)->first();
    }

    public function getByID($id)
    {
        return DB::select('select * from users where id = ?', [$id]);
    }

    public function updateUserName($data)
    {

        return DB::update('update users set username = ?, cost = ?,avatar = ? where id = ?', [$data->username, $data->cost, $data->avatar, $data->id]);
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