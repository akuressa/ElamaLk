<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert(array(
            array(
                "user_id" => uniqid(),
                "name" => "Admin",
                "email" => "admin@admin.com",
                "password" => bcrypt('admin@admin'),
                "role" => "1",
                "choosed_theme" => "light",
                "status" => "1",
            )
        ));
    }
}
