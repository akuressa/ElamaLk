<?php

namespace App\Http\Controllers;

use App\Models\BusinessPlan;
use App\Models\BusinessPlanSubscription;
use App\Models\Business;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BusinessPlanSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'business_plan_id' => 'required|exists:business_plans,business_plan_id',
            'business_id' => 'required|exists:businesses,business_id'
        ]);

        $businessPlan = BusinessPlan::where('business_plan_id', $request->business_plan_id)->first();
        
        if (!$businessPlan || !$businessPlan->is_active) {
            return response()->json(['error' => 'Business plan not available'], 400);
        }

        // Check if user already has an active subscription for this business plan
        $existingSubscription = BusinessPlanSubscription::where('user_id', Auth::user()->user_id)
            ->where('business_plan_id', $request->business_plan_id)
            ->where('business_id', $request->business_id)
            ->where('status', 'active')
            ->where('end_date', '>', Carbon::now())
            ->first();

        if ($existingSubscription) {
            return response()->json(['error' => 'You already have an active subscription for this plan'], 400);
        }

        // Create new subscription
        $subscription = BusinessPlanSubscription::create([
            'subscription_id' => uniqid(),
            'user_id' => Auth::user()->user_id,
            'business_id' => $request->business_id,
            'business_plan_id' => $request->business_plan_id,
            'subscription_price' => $businessPlan->plan_price,
            'duration_months' => $businessPlan->duration_months,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths($businessPlan->duration_months),
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to the business plan!',
            'subscription' => $subscription
        ]);
    }

    public function getUserSubscriptions($business_id)
    {
        $subscriptions = BusinessPlanSubscription::with('businessPlan')
            ->where('user_id', Auth::user()->user_id)
            ->where('business_id', $business_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($subscriptions);
    }

    public function checkout($business_id, $business_plan_id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to subscribe to business plans.');
        }

        // Get business plan
        $businessPlan = BusinessPlan::where('business_plan_id', $business_plan_id)
            ->where('business_id', $business_id)
            ->where('is_active', 1)
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('error', 'Business plan not found or not available.');
        }

        // Get business
        $business = Business::where('business_id', $business_id)->first();
        if (!$business) {
            return redirect()->back()->with('error', 'Business not found.');
        }

        // Check if user already has an active subscription for this business plan
        $existingSubscription = BusinessPlanSubscription::where('user_id', Auth::user()->user_id)
            ->where('business_plan_id', $business_plan_id)
            ->where('business_id', $business_id)
            ->where('status', 'active')
            ->where('end_date', '>', Carbon::now())
            ->first();

        if ($existingSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription for this plan.');
        }

        // Get configuration and settings
        $config = Configuration::get();
        $setting = Setting::where('status', 1)->first(); // Changed to singular to match layout expectation
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();
        $gateways = PaymentGateway::where('is_enabled', true)->where('status', 1)->get();

        // Get user billing details
        $user = Auth::user();
        $billing_details = !empty($user->billing_details) ? json_decode($user->billing_details, true) : [];

        // Calculate pricing
        $plan_price = $businessPlan->plan_price;
        // $tax = $config[25]->config_value ?? 0;
        // $payment_gateway_charge = (float)($plan_price) * (10 / 100); // 10% gateway charge
        // $tax_amount = (float)($plan_price) * ((float)($tax) / 100);
        $total = (float)($plan_price);

        return view('website.pages.business-plan-checkout', compact(
            'businessPlan',
            'business',
            'billing_details',
            'setting',
            'config',
            'currency',
            'total',
            'plan_price',
            'gateways'
        ));
    }

    public function processPayment(Request $request, $business_id, $business_plan_id)
    {
        // Validate request
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_zipcode' => 'required|string|max:20',
            'billing_country' => 'required|string|max:100',
            'payment_gateway_id' => 'required|exists:payment_gateways,payment_gateway_id',
        ]);

        // Get business plan
        $businessPlan = BusinessPlan::where('business_plan_id', $business_plan_id)
            ->where('business_id', $business_id)
            ->where('is_active', 1)
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('error', 'Business plan not found or not available.');
        }

        // Get payment gateway
        $paymentGateway = PaymentGateway::where('payment_gateway_id', $request->payment_gateway_id)->first();
        if (!$paymentGateway) {
            return redirect()->back()->with('error', 'Payment gateway not found.');
        }

        // Update user billing details
        $billing_details = [
            'billing_name' => $request->billing_name,
            'billing_email' => $request->billing_email,
            'billing_phone' => $request->billing_phone,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'billing_state' => $request->billing_state,
            'billing_zipcode' => $request->billing_zipcode,
            'billing_country' => $request->billing_country,
            'vat_number' => $request->vat_number ?? '',
        ];

        // User::where('user_id', Auth::user()->user_id)->update([
        //     'billing_details' => json_encode($billing_details)
        // ]);

        // Create subscription record (pending payment)
        $subscription = BusinessPlanSubscription::create([
            'subscription_id' => uniqid(),
            'user_id' => Auth::user()->user_id,
            'business_id' => $business_id,
            'business_plan_id' => $business_plan_id,
            'subscription_price' => $businessPlan->plan_price,
            'duration_months' => $businessPlan->duration_months,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths($businessPlan->duration_months),
            'status' => 'pending' // Will be updated to 'active' after successful payment
        ]);

        // Redirect to payment gateway based on selected gateway
        switch ($paymentGateway->payment_gateway_name) {
            case 'Paypal':
                return redirect()->route('business.plan.payment.paypal', ['business_plan_id' => $business_plan_id]);
            case 'Stripe':
                return redirect()->route('business.plan.payment.stripe', ['business_plan_id' => $business_plan_id]);
            case 'Onepay':
                return redirect()->route('business.plan.payment.onepay', ['business_plan_id' => $business_plan_id]);
            case 'Mollie':
                return redirect()->route('business.plan.payment.mollie', ['business_plan_id' => $business_plan_id]);
            case 'RazorPay':
                return redirect()->route('business.plan.payment.razorpay', ['business_plan_id' => $business_plan_id]);
            case 'Paystack':
                return redirect()->route('business.plan.payment.paystack', ['business_plan_id' => $business_plan_id]);
            case 'MercadoPago':
                return redirect()->route('business.plan.payment.mercadopago', ['business_plan_id' => $business_plan_id]);
            default:
                return redirect()->back()->with('error', 'Payment gateway not supported.');
        }
    }
}
