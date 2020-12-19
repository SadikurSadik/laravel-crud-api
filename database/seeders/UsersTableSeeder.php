<?php

namespace Database\Seeders;

use App\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           [
               'name' => 'Super Admin',
               'email' => 'superadmin@mail.com',
               'role' => Role::ADMIN_ROLE,
               'password' => bcrypt('123456'),
           ],
            [
                'name' => 'User 1',
                'email' => 'user1@mail.com',
                'role' => Role::USER_ROLE,
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@mail.com',
                'role' => Role::USER_ROLE,
                'password' => bcrypt('123456'),
            ],
        ]);
    }
}
