<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminAccount extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'phone' => '19008888',
            'password' => Hash::make(env('PASSWORD_ADMIN')),
            'status' => array_search("Active", config('app.user_status')),
        ]);

        DB::table('room_user')->insert([
            'user_id' => DB::table('users')->select('id')->where('email', '=','admin@gmail.com')->first()->id,
            'role_id' => DB::table('roles')->select('id')->where('name', '=', 'ADMIN')->first()->id,
        ]);
    }
}
