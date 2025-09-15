<?php

namespace App\Http\Controllers\User\Payment;

use App\Classes\AppointmentBook;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Transaction;
use App\Classes\UpgradePlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PhonepeController extends Controller
{
    public function prepareOnepay($bookingId)
{         dd($bookingId);

    if (Auth::check()) {
        // Fetch booking details
        $bookingDetails = Booking::where('booking_id', $bookingId)->first();
        $service_name = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->business_service_name;
        $config = Configuration::get();
        $setting = Setting::where('status', 1)->first();


        // Ensure booking exists
        if ($bookingDetails == null) {
            return back();
        } else {
            $transactionId = uniqid();

            // Get service amount
            $service_amount = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->amount;

            $business_id = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->business_id;
            $business_user_id = Business::where('business_id', $business_id)->first()->user_id;
            $user = User::where('user_id', $business_user_id)->first();

            // Decode plan features
            $planDetails = json_decode($user->plan_details, true); // Decoded as array            
            $planFeatures = is_string($planDetails['plan_features']) ? json_decode($planDetails['plan_features'], true) : $planDetails['plan_features'];
            $payment_gateway_percentage = $planFeatures['payment_gateway_charge'];

            // Calculate payment gateway charge 
            $payment_gateway_charge = round((float)($service_amount) * ($payment_gateway_percentage / 100), 2);
            $sub_total = (float)($service_amount) + (float)($payment_gateway_charge);

            // Total amount to be paid
            $amountToBePaid = $bookingDetails->total_price;

            try {
                // Prepare data for OnePay
                $data = array(
                    'amount' => $amountToBePaid,
                    'app_id' => 'WF8X118E6EDF0C075805F', // OnePay App ID
                    'reference' => $transactionId,
                    'customer_first_name' => Auth::user()->name,
                    'customer_phone_number' => Auth::user()->phone ?? '+94770000000',
                    'customer_email' => Auth::user()->email,
                    'transaction_redirect_url' => route('booking.payment.onepay.status'),
                    'redirectMode' => 'POST',
                    'callbackUrl' => route('booking.payment.onepay.status'),
                    'currency' => 'LKR'
                );

                // Encode request data
                $encode = base64_encode(json_encode($data));

                // Create hash for security
                $hashSalt = '1VO6118E6EDF0C075808A';  // OnePay hash salt
                $string = $encode . '/pg/v1/pay' . $hashSalt;
                $sha256 = hash('sha256', $string);
                $finalXHeader = $sha256 . '###' . 1;

                // Send request to OnePay API
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $finalXHeader,
                ])->post('https://merchant-api-live-v2.onepay.lk/api/ipg/gateway/request-payment-link', [
                    'request' => $encode,
                ]);

                $rData = json_decode($response);

                if (isset($rData) && $rData->success == true) {
                    // Generate invoice details
                    $invoice_details = [
                        'from_billing_name' => $config[16]->config_value,
                        'from_billing_address' => $config[19]->config_value,
                        'from_billing_city' => $config[20]->config_value,
                        'from_billing_state' => $config[21]->config_value,
                        'from_billing_zipcode' => $config[22]->config_value,
                        'from_billing_country' => $config[23]->config_value,
                        'from_vat_number' => $config[26]->config_value,
                        'from_billing_email' => $config[17]->config_value,
                        'from_billing_phone' => $config[18]->config_value,
                        'to_billing_name' => Auth::user()->name,
                        'to_billing_email' => Auth::user()->email,
                        'tax_name' => $config[24]->config_value,
                        'tax_type' => $config[14]->config_value,
                        'tax_value' => (float)($config[25]->config_value),
                        'service_amount' => $service_amount,
                        'payment_gateway_charge' => (float)($payment_gateway_charge),
                        'subtotal' => $sub_total,
                        'tax_amount' => round((float)($service_amount) * (float)($config[25]->config_value) / 100, 2),
                        'invoice_amount' => $bookingDetails->total_price
                    ];

                    // Store transaction details in database
                    $booking_transaction = new BookingTransaction();
                    $booking_transaction->booking_transaction_id = $transactionId;
                    $booking_transaction->user_id = Auth::user()->user_id;
                    $booking_transaction->booking_id = $bookingDetails->booking_id;
                    $booking_transaction->payment_gateway_name = "OnePay";
                    $booking_transaction->transaction_currency = $config[1]->config_value;
                    $booking_transaction->transaction_total = $bookingDetails->total_price;
                    $booking_transaction->description = $service_name . " Service";
                    $booking_transaction->transaction_date = now();
                    $booking_transaction->invoice_details = json_encode($invoice_details);
                    $booking_transaction->transaction_status = "pending";
                    $booking_transaction->save();

                    // Redirect user to OnePay payment page
                    return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);
                } else {
                    return redirect()->route('user.my-bookings')->with('failed', trans('Payment failed!'));
                }
            } catch (\Exception $e) {
                return redirect()->route('user.my-bookings')->with('failed', trans('Payment failed!'));
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
                $appointmentBook = new AppointmentBook;
                $appointmentBook->upgrade($input['transactionId'], $res);              

                // Redirect
                return redirect()->route('user.my-bookings')->with('failed', trans('Appointment Booked successfully!'));
            } else {
                return redirect()->route('user.my-bookings')->with('failed', trans('Payment failed!'));
            }
        } else {
            return redirect()->route('user.my-bookings')->with('failed', trans('Payment failed!'));
        }
    }
}
