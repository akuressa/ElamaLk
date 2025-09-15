<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            "id"  => 1,
            "plan_id"  => uniqid(),
            "plan_name"  => "Free",
            "plan_description"  => "Enjoy essential booking features at no cost with our Free Plan—ideal for getting started!",
            "plan_features" => '{"no_of_businesses":2,"no_of_services":5,"no_of_employees":5,"payment_gateway_charge":0,"no_of_bookings":50}',
            "plan_price"  => 0.00,
            "plan_validity"  => 7,
            "is_trial" => 1,
            "is_private"  => 0,
            "is_recommended"  => 0,
            "is_customer_support"  => 0,
            "status"  => 1
        ]);

        DB::table('plans')->insert([
            "id"  => 2,
            "plan_id"  => uniqid(),
            "plan_name"  => "Gold",
            "plan_description"  => "Upgrade to the Gold Plan for enhanced booking features and added convenience!",
            "plan_features" => '{"no_of_businesses":2,"no_of_services":5,"no_of_employees":5,"payment_gateway_charge":0,"no_of_bookings":300}',
            "plan_price"  => 5.00,
            "plan_validity"  => 31,
            "is_trial" => 0,
            "is_private"  => 0,
            "is_recommended"  => 1,
            "is_customer_support"  => 1,
            "status"  => 1
        ]);

        DB::table('plans')->insert([
            "id"  => 3,
            "plan_id"  => uniqid(),
            "plan_name"  => "Platinum",
            "plan_description"  => "Experience premium benefits with our Platinum Plan, designed for optimal booking and convenience!",
            "plan_features" => '{"no_of_businesses":5,"no_of_services":10,"no_of_employees":10,"payment_gateway_charge":2,"no_of_bookings":500}',
            "plan_price"  => 10.00,
            "plan_validity"  => 31,
            "is_trial" => 0,
            "is_private"  => 0,
            "is_recommended"  => 0,
            "is_customer_support"  => 1,
            "status"  => 1
        ]);
    }
}
