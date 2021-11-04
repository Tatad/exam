<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

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
            'password' => bcrypt('secret'),
            'pin_code' => 'test12',
            'user_role' => 'admin',
            'registered_at' => Carbon::now()
        ];
        User::create($user);
    }
}
