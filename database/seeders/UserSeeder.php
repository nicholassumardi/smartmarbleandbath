<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'         => 'Calvin Dito Pratama',
            'email'        => 'calvindito7@gmail.com',
            'password'     => bcrypt('123456'),
            'branch'       => 1,
            'verification' => date('Y-m-d H:i:s'),
            'status'       => 1
        ]);

        User::create([
            'name'         => 'SMB',
            'email'        => 'smartmarbleandbath@gmail.com',
            'password'     => bcrypt('123456'),
            'branch'       => 1,
            'verification' => date('Y-m-d H:i:s'),
            'status'       => 1
        ]);

        UserRole::create([
            'user_id' => 1,
            'role'    => 1
        ]);

        UserRole::create([
            'user_id' => 2,
            'role'    => 1
        ]);
    }
}
