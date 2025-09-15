<?php

namespace App\Http\Controllers\Payment;

use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;

class OfflineController extends Controller
{
    // Offline checkot
    public function offlineCheckout(Request $request, $planId)
    {
        // Queries
        $config = Configuration::get();

        // Check value
        if ($config[31]->config_value == null) {
            // Page redirect
            return redirect()->route('user.checkout', $planId)->with('failed', trans('No Bank Transfer details found!'));
        } else {
            // Queries
            $settings = Setting::where('status', 1)->first();
            $plan_details = Plan::where('plan_id', $planId)->where('status', 1)->first();
            // Page view
            return view('business.pages.checkout.pay-with-offline', compact('settings', 'plan_details', 'config'));
        }
    }

    // Mark offline payment
    public function markOfflinePayment(Request $request)
    {
        // Check customer
        if (Auth::user()) {
            // Queries
            $config = Configuration::get();
            // Get customer details
            $userData = User::where('user_id', Auth::user()->user_id)->first();
            $billing_details = json_decode($userData->billing_details, true);

            // Get plan details
            $plan_details = Plan::where('plan_id', $request->plan_id)->where('status', 1)->first();

            // Check plan
            if ($plan_details == null) {
                // Page redirect
                return back();
            } else {
                // Paid amount
                $plan_features = is_string($plan_details->plan_features)
                    ? json_decode($plan_details->plan_features, true)
                    : $plan_details->plan_features;


                $payment_gateway_charge = round((float)($plan_details->plan_price) * ($plan_features['payment_gateway_charge'] / 100), 2);

                // Calculate total        
                $amountToBePaid = ((float)($plan_details->plan_price) * ((float)($config[25]->config_value) / 100)) + (float)($plan_details->plan_price) + (float)($payment_gateway_charge);
                $amountToBePaidPaise = round($amountToBePaid, 2);

            
                // Generate JSON
                $invoice_details = [];

                $invoice_details['from_billing_name'] = $config[16]->config_value;
                $invoice_details['from_billing_address'] = $config[19]->config_value;
                $invoice_details['from_billing_city'] = $config[20]->config_value;
                $invoice_details['from_billing_state'] = $config[21]->config_value;
                $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
                $invoice_details['from_billing_country'] = $config[23]->config_value;
                $invoice_details['from_vat_number'] = $config[26]->config_value;
                $invoice_details['from_billing_phone'] = $config[18]->config_value;
                $invoice_details['from_billing_email'] = $config[17]->config_value;
                $invoice_details['to_billing_name'] = $billing_details['billing_name'];
                $invoice_details['to_billing_address'] = $billing_details['billing_address'];
                $invoice_details['to_billing_city'] = $billing_details['billing_city'];
                $invoice_details['to_billing_state'] = $billing_details['billing_state'];
                $invoice_details['to_billing_zipcode'] = $billing_details['billing_zipcode'];
                $invoice_details['to_billing_country'] = $billing_details['billing_country'];
                $invoice_details['to_billing_phone'] = $billing_details['billing_phone'];
                $invoice_details['to_billing_email'] = $billing_details['billing_email'];
                $invoice_details['to_vat_number'] = $billing_details['vat_number'];
                $invoice_details['tax_name'] = $config[24]->config_value;
                $invoice_details['tax_type'] = $config[14]->config_value;
                $invoice_details['tax_value'] = (float)($config[25]->config_value);
                $invoice_details['subtotal'] = $plan_details->plan_price + $payment_gateway_charge;
                $invoice_details['tax_amount'] = round((float)($plan_details->plan_price) * (float)($config[25]->config_value) / 100, 2);
                $invoice_details['payment_gateway_charge'] = (float)($payment_gateway_charge);
                $invoice_details['invoice_amount'] = $amountToBePaidPaise;

                // Store transaction details in database before redirecting to PayPal
                $transaction = new Transaction();
                $transaction->transaction_id = $request->transaction_id;
                $transaction->transaction_date = now();
                $transaction->user_id = Auth::user()->user_id;
                $transaction->plan_id = $plan_details->plan_id;
                $transaction->description = $plan_details->plan_name . " Plan";
                $transaction->payment_gateway_name = "Offline";
                $transaction->transaction_total = $amountToBePaidPaise;
                $transaction->transaction_currency = $config[1]->config_value;
                $transaction->invoice_details = json_encode($invoice_details);
                $transaction->transaction_status = "pending";
                $transaction->save();

                // Page redirect
                return redirect()->route('business.plans.index')->with('success', trans('Bank transfer transaction pending now. Once, Transaction is done, will be implemented your plan by the admin!'));
            }
        } else {
            // Page redirect
            return redirect()->route('login');
        }
    }
}
