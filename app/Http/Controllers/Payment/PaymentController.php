<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Models\Config;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function preparePaymentGateway(Request $request, $planId)
    {
        // Queries
        $config = Configuration::get();
        $payment_mode = PaymentGateway::where('payment_gateway_id', $request->payment_gateway_id)->first();

        if ($payment_mode == null) {
            return redirect()->back()->with('failed', trans('Please choose valid payment method!'));
        } else {
            // Validation
            $validator = Validator::make($request->all(), [
                'billing_name' => 'required',
                'billing_email' => 'required',
                'billing_phone' => 'required',
                'billing_address' => 'required',
                'billing_city' => 'required',
                'billing_state' => 'required',
                'billing_zipcode' => 'required',
                'billing_country' => 'required',
                'type' => 'required'
            ]);

            // Validation error
            if ($validator->fails()) {
                return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
            }

            $billing_details = [
                'billing_name' => $request->billing_name,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_zipcode' => $request->billing_zipcode,
                'billing_country' => $request->billing_country,
                'type' => $request->type,
                'vat_number' => $request->vat_number
            ];

            User::where('user_id', Auth::user()->user_id)->update([
                'billing_details' => json_encode($billing_details)
            ]);

            if ($payment_mode->payment_gateway_name == "Paypal") {
                // Check key and secret
                if ($config[4]->config_value != "YOUR_PAYPAL_CLIENT_ID" || $config[5]->config_value != "YOUR_PAYPAL_SECRET") {
                    return redirect()->route('paywithpaypal', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Razorpay") {
                // Check key and secret
                if ($config[6]->config_value != "YOUR_RAZORPAY_KEY" || $config[7]->config_value != "YOUR_RAZORPAY_SECRET") {
                    return redirect()->route('paywithrazorpay', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "PhonePe") {
                // Check key and secret
                if ($config[53]->config_value != "") {
                    return redirect()->route('paywithphonepe', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Stripe") {
                // Check key and secret
                if ($config[9]->config_value != "YOUR_STRIPE_PUB_KEY" || $config[10]->config_value != "YOUR_STRIPE_SECRET") {
                    return redirect()->route('paywithstripe', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Onepay") {
                    return redirect()->route('paywithonepay', $planId);
                }
                
             else if ($payment_mode->payment_gateway_name == "Paystack") {
                // Check key and secret
                if ($config[37]->config_value != "PAYSTACK_PUBLIC_KEY" || $config[38]->config_value != "PAYSTACK_SECRET_KEY") {
                    return redirect()->route('paywithpaystack', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Mollie") {
                // Check key and secret
                if ($config[41]->config_value != "mollie_key") {
                    return redirect()->route('paywithmollie', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Bank Transfer") {
                // Check key and secret
                if ($config[31]->config_value != "") {
                    return redirect()->route('paywithoffline', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Transaction Cloud") {
                // Check key and secret
                if ($config[44]->config_value != "YOUR_TRANSACTION_CLOUD_API_KEY" || $config[45]->config_value != "YOUR_TRANSACTION_CLOUD_API_PASSWORD") {
                    return redirect()->route('paywithtransactioncloud', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else if ($payment_mode->payment_gateway_name == "Mercado Pago") {
                // Check key and secret
                if ($config[55]->config_value != "YOUR_MERCADO_PAGO_PUBLIC_KEY" || $config[56]->config_value != "YOUR_MERCADO_PAGO_ACCESS_TOKEN") {
                    return redirect()->route('paywithmercadopago', $planId);
                } else {
                    return redirect()->route('business.plans.index')->with('failed', trans('Something went wrong!'));
                }
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        }
    }
}
