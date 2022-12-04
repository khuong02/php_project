<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;
    private $email, $password, $userID;

    protected $fillable = [
        'user_id',
        'email',
        'password',
    ];

    public function setAccount($req)
    {
        return DB::insert('insert into accounts (user_id, email,password) values (?, ?)', [$req["user_id"], $req["email"], $req["password"]]);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function findAccountByEmail($email)
    {
        return DB::table('accounts')->where('email', $email)->first();
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
            'uid' => $this->user_id, // my custom claim, add as many as you like
        ];
    }
}
