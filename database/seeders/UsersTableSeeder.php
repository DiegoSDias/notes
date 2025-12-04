<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create multiple users
        DB::table('users')->insert([
            [
                'name' => 'Usuario 1',
                'email' => 'user1@gmail.com',
                'password' => bcrypt('acb123456'),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'=> 'Admin',
                'email'   => 'admin@gmail.com',
                'password'   => bcrypt('admin123'),
                'role'       => 'admin',
                'created_at' => now(),
            ],
        ]);
    }
}
