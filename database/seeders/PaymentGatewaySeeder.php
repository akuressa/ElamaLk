<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_gateways')->insert([
            "id" => 1,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/paypal.png",
            "payment_gateway_name"  => "Paypal",
            "is_enabled"  => true
        ]);

        DB::table('payment_gateways')->insert([
            "id" => 2,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/razorpay.png",
            "payment_gateway_name"  => "Razorpay",
            "is_enabled"  => true
        ]);

        DB::table('payment_gateways')->insert([
            "id" => 3,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/phonepe.png",
            "payment_gateway_name"  => "PhonePe",
            "is_enabled"  => true
        ]);

        DB::table('payment_gateways')->insert([
            "id" => 4,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/stripe.png",
            "payment_gateway_name"  => "Stripe",
            "is_enabled"  => true
        ]);

        DB::table('payment_gateways')->insert([
            "id" => 5,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/paystack.png",
            "payment_gateway_name"  => "Paystack",
            "is_enabled"  => true
        ]);

        DB::table('payment_gateways')->insert([
            "id" => 6,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/mollie.webp",
            "payment_gateway_name"  => "Mollie",
            "is_enabled"  => true
        ]);

        DB::table('payment_gateways')->insert([
            "id" => 7,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/bank-transfer.png",
            "payment_gateway_name"  => "Bank Transfer",
            "is_enabled"  => true
        ]);       

        DB::table('payment_gateways')->insert([
            "id" => 8,
            "payment_gateway_id"  => uniqid(),
            "payment_gateway_logo_url"  => "img/payments/mercado-pago.png",
            "payment_gateway_name"  => "Mercado Pago",
            "is_enabled"  => true
        ]);
    }
}
