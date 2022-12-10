<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAdmin;
use App\Models\Difficulty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\LeaderBoard;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        UserAdmin::create(
            [
                'username' => 'AdminKLD',
                'email' => env('EMAIL_ADMIN'),
                'password' => Hash::make('password')
            ]
        );

        Difficulty::create(
            [
                'name' => 'Easy'
            ]
        );
        Difficulty::create(
            [
                'name' => 'Medium'
            ]
        );
        Difficulty::create(
            [
                'name' => 'Hard'
            ]
        );

        User::create([
            "username" => 'Adelaide',
            "email" => 'Adelaide@gmail.com'
        ]);
        User::create([
            "username" => 'Celina',
            "email" => 'Celina@gmail.com'
        ]);
        User::create([
            "username" => 'Fidelia',
            "email" => 'Fidelia@gmail.com'
        ]);

        DB::insert('INSERT INTO leaderboard (user_id, quantity, time, score,created_at,updated_at) VALUES (?,?,?,?,?,?)', [1, 50, 300, 7500, Carbon::now(), Carbon::now()]);
        DB::insert('INSERT INTO leaderboard (user_id, quantity, time, score,created_at,updated_at) VALUES (?,?,?,?,?,?)', [2, 65, 330, 8500, Carbon::now(), Carbon::now()]);
        DB::insert('INSERT INTO leaderboard (user_id, quantity, time, score,created_at,updated_at) VALUES (?,?,?,?,?,?)', [3, 60, 300, 8250, Carbon::now(), Carbon::now()]);
    }
}
