<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAdmin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        UserAdmin::create(
            [
                'username' => 'AdminKLD',
                'email' => 'AdminKDL@gmail.com',
                'password' => Hash::make('password')
            ]
        );
    }
}
