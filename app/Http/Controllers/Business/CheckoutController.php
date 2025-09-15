<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    // Checkout
    public function checkout(Request $request, $plan_id)
    {
        // Choosed plan
        $selected_plan = Plan::where('plan_id', $plan_id)->where('status', 1)->first();
        // Check selected plan status
        if ($selected_plan == null) {
            return redirect()->route('business.plans.index')->with('failed', trans('Your current plan is not available. Choose another plan.'));
        } else {

            // Queries
            $config = Configuration::get();

            // Check plan status
            if ($selected_plan == null) {
                return back();
            }
            if ($selected_plan->plan_price == 0) {
                // Save transaction
                $transaction = new Transaction();
                $transaction->transaction_date = now();
                $transaction->transaction_id = uniqid();
                $transaction->user_id = Auth::user()->user_id;
                $transaction->plan_id = $selected_plan->plan_id;
                $transaction->description = $selected_plan->plan_name . " Plan";
                $transaction->payment_gateway_name = "FREE";
                $transaction->transaction_total = $selected_plan->plan_price;
                $transaction->transaction_currency = $config[1]->config_value;
                $transaction->transaction_status = "completed";
                $transaction->save();

                $planDetails = [
                    'plan_id' => $selected_plan->plan_id,
                    'plan_name' => $selected_plan->plan_name,  // Assuming plan_details is stored as JSON
                    'plan_description' => $selected_plan->plan_description,
                    'plan_features' => $selected_plan->plan_features,
                    'plan_price' => $selected_plan->plan_price,
                    'plan_validity' => $selected_plan->plan_validity,
                    'is_trial' => $selected_plan->is_trial,
                    'is_private' => $selected_plan->is_private,
                    'is_recommended' => $selected_plan->is_recommended,
                    'is_customer_support' => $selected_plan->is_customer_support,
                    'plan_start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'plan_end_date' => Carbon::now()->addDays($selected_plan->plan_validity)->format('Y-m-d H:i:s'),
                ];

                // Update validity by user
                User::where('user_id', Auth::user()->user_id)->update([
                    'plan_details' => json_encode($planDetails),
                ]);

                return redirect()->route('business.plans.index')->with('success', trans("FREE Plan activated!"));
            } else {
                // Queries
                $settings = Setting::where('status', 1)->first();
                $config = Configuration::get();
                $currency = Currency::where('iso_code', $config[1]->config_value)->first();
                $gateways = PaymentGateway::where('is_enabled', true)->where('status', 1)->get();

                // Current plan price
                $price = $selected_plan->plan_price;
                $tax = $config[25]->config_value;
                $plan_features = is_string($selected_plan->plan_features)
                    ? json_decode($selected_plan->plan_features, true)
                    : $selected_plan->plan_features;


                $payment_gateway_charge = (float)($price) * ($plan_features['payment_gateway_charge'] / 100);
                
                // Calculate total
                $total = ((float)($price) * ((float)($tax) / 100) + (float)($price)) + (float)($payment_gateway_charge);

                $user = Auth::user();
                $billing_details = !empty($user->billing_details) ? json_decode($user->billing_details, true) : null;

                if ($billing_details != null) {
                    return view('business.pages.checkout.index', compact('billing_details', 'settings', 'config', 'currency', 'selected_plan', 'gateways', 'total', 'payment_gateway_charge'));
                } else {
                    $billing_details = [];
                    return view('business.pages.checkout.index', compact('billing_details', 'settings', 'config', 'currency', 'selected_plan', 'gateways', 'total', 'payment_gateway_charge'));
                }
            }
        }
    }
}
