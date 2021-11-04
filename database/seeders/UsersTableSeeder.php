<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = [
            'name' => 'AdminUser',
            'email' => 'admin@admin.com',
            'password' => bcrypt('secret')
        ];
        User::create($user);
    }
}
