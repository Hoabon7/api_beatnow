<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class customer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'nguyenhoabon@gmail.com',
            'password' => bcrypt(12345678),
            'provider' => User::GOOGLE,
            'provider_id' => '11111111',
            'active' => User::ACTIVE,
            'role' => User::ADMIN

        ]);
    }
}
