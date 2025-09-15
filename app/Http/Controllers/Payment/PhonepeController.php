<?php

namespace App\Http\Controllers\Payment;

use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use App\Classes\UpgradePlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PhonepeController extends Controller
{
    public function preparePhonpe($planId)
    { dd('ddfdf');
        if (Auth::user()) {

            // Queries       
            $plan_details = Plan::where('plan_id', $planId)->where('status', 1)->first();
            $config = Configuration::get();
            $userData = User::where('user_id', Auth::user()->user_id)->first();
            $billing_details = json_decode($userData->billing_details, true);

            // Check plan details
            if ($plan_details == null) {
                return back();
            } else {
                // Transaction ID
                $transactionId = uniqid();

                // Paid amount
                $plan_features = is_string($plan_details->plan_features)
                    ? json_decode($plan_details->plan_features, true)
                    : $plan_details->plan_features;

                $payment_gateway_charge = round((float)($plan_details->plan_price) * ($plan_features['payment_gateway_charge'] / 100), 2);

                // Calculate total               

                $amountToBePaid = ((float)($plan_details->plan_price) * ((float)($config[25]->config_value) / 100)) + (float)($plan_details->plan_price) + (float)($payment_gateway_charge);
                $amountToBePaidPaise = round($amountToBePaid, 2);             

                try {
                    // sta payment intent
                    $data = array(
                        'merchantId' => $config[53]->config_value,
                        'merchantTransactionId' => $transactionId,
                        'merchantUserId' => $config[53]->config_value,
                        'amount' => $amountToBePaidPaise,
                        'redirectUrl' => route('phonepe.payment.status'),
                        'redirectMode' => 'POST',
                        'callbackUrl' => route('phonepe.payment.status'),
                        'mobileNumber' => $billing_details['billing_phone'],
                        'paymentInstrument' =>
                        array(
                            'type' => 'PAY_PAGE',
                        ),
                    );

                    $encode = base64_encode(json_encode($data));

                    $saltKey = $config[54]->config_value;
                    $saltIndex = 1;

                    $string = $encode . '/pg/v1/pay' . $saltKey;
                    $sha256 = hash('sha256', $string);

                    $finalXHeader = $sha256 . '###' . $saltIndex;

                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'X-VERIFY' => $finalXHeader,
                    ])->post('https://api.phonepe.com/apis/hermes/pg/v1/pay', [
                        'request' => $encode,
                    ]);

                    $rData = json_decode($response);                

                    if (isset($rData)) {
                        if ($rData->success == true) {
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

                            // Store transaction details in database before redirecting to Phonepe
                            $transaction = new Transaction();
                            $transaction->transaction_date = now();
                            $transaction->transaction_id = $transactionId;
                            $transaction->user_id = Auth::user()->user_id;
                            $transaction->plan_id = $plan_details->plan_id;
                            $transaction->description = $plan_details->name . " Plan";
                            $transaction->payment_gateway_name = "Phonepe";
                            $transaction->transaction_total = $amountToBePaidPaise;
                            $transaction->transaction_currency = $config[1]->config_value;
                            $transaction->invoice_details = json_encode($invoice_details);
                            $transaction->transaction_status = "pending";
                            $transaction->save();

                            return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);
                        } else {
                            return redirect()->route('business.plans.index')->with('failed', trans('Payment failed!'));
                        }
                    } else {
                        return redirect()->route('business.plans.index')->with('failed', trans('Payment failed!'));
                    }
                } catch (\Exception $e) {
                    return redirect()->route('business.plans.index')->with('failed', trans('Payment failed!'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function phonepePaymentStatus(Request $request)
    {
        // Queries
        $config = Configuration::get();

        $input = $request->all();

        if (count($request->all()) > 0 && isset($input['transactionId'])) {

            $merchantId = $config[53]->config_value;
            $saltKey = $config[54]->config_value;
            $saltIndex = 1;

            $finalXHeader = hash('sha256', '/pg/v1/status/' . $merchantId . '/' . $input['transactionId'] . $saltKey) . '###' . $saltIndex;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
                'X-VERIFY' => $finalXHeader,
                'X-MERCHANT-ID' => $merchantId
            ])->get('https://api.phonepe.com/apis/hermes/pg/v1/status/' . $merchantId . '/' . $input['transactionId']);

            $res = json_decode($response);

            if ($res->code == "PAYMENT_SUCCESS") {
                // Plan upgrade
                $upgradePlan = new UpgradePlan;
                $upgradePlan->upgrade($input['transactionId'], $res);

                // Redirect
                return redirect()->route('business.plans.index')->with('failed', trans('Plan activation successfully!'));
            } else {
                return redirect()->route('business.plans.index')->with('failed', trans('Payment failed!'));
            }
        } else {
            return redirect()->route('business.plans.index')->with('failed', trans('Payment failed!'));
        }
    }
}
