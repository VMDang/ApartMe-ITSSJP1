<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => "ADMIN",
                'status' => array_search("Active", config('app.role_status')),
            ],
            [
                'name' => "OWNER",
                'status' => array_search("Active", config('app.role_status')),
            ],
            [
                'name' => "TENANT",
                'status' => array_search("Active", config('app.role_status')),
            ],
        ]);
    }
}
