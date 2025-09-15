<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            "setting_id" => uniqid(),
            "analytics_id" => "UA-144200805-4",
            "adsense_code" => "DISABLE",
            "site_name" => "Bookin",
            "site_logo" => "images/web/logo/logo.png",
            "favicon" => "images/web/logo/favicon.png",
            "tawk_chat_key" => ""
        ]);
    }
}
