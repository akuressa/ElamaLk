<?php

namespace App\Http\Controllers\User\Payment;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessService;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;

class OfflineController extends Controller
{
    // Offline checkot
    public function offlineCheckout(Request $request, $bookingId)
    {
        // Queries
        $config = Configuration::get();
        $title = "Offline Transaction";

        // Check value
        if ($config[31]->config_value == null) {
            // Page redirect
            return redirect()->back()->with('failed', trans('No Bank Transfer details found!'));
        } else {
            // Queries
            $setting = Setting::where('status', 1)->first();
            $booking_details = Booking::where('booking_id', $bookingId)->first();

            $service_name = BusinessService::where('business_service_id', $booking_details->business_service_id)->first()->business_service_name;
            // Page view
            return view('user.pages.book-appointment.pay-with-offline', compact('setting', 'booking_details', 'config', 'title', 'service_name'));
        }
    }

    // Mark offline payment
    public function markOfflinePayment(Request $request)
    {
        // Check customer
        if (Auth::user()) {
            // Queries
            $config = Configuration::get();

            // Get booking details
            $booking_details = Booking::where('booking_id', $request->booking_id)->first();
            $service_name = BusinessService::where('business_service_id', $booking_details->business_service_id)->first()->business_service_name;

            // Check plan
            if ($booking_details == null) {
                // Page redirect
                return back();
            } else {
                // Paid amount
                $service_amount = BusinessService::where('business_service_id', $booking_details->business_service_id)->first()->amount;

                $business_id = BusinessService::where('business_service_id', $booking_details->business_service_id)->first()->business_id;
                $business_user_id = Business::where('business_id', $business_id)->first()->user_id;
                $user = User::where('user_id', $business_user_id)->first();

                $planDetails = json_decode($user->plan_details, true); // Decoded as array            
                // Step 2: Decode plan_features since it's a nested JSON string
                $planFeatures = is_string($planDetails['plan_features'])
                    ? json_decode($planDetails['plan_features'], true)
                    : $planDetails['plan_features'];

                $payment_gateway_percentage = $planFeatures['payment_gateway_charge'];

                $payment_gateway_charge = round((float)($service_amount) * ($payment_gateway_percentage / 100), 2);

                $sub_total = (float)($service_amount) + (float)($payment_gateway_charge);

                // Generate JSON
                $invoice_details = [];

                $invoice_details['from_billing_name'] = $config[16]->config_value;
                $invoice_details['from_billing_address'] = $config[19]->config_value;
                $invoice_details['from_billing_city'] = $config[20]->config_value;
                $invoice_details['from_billing_state'] = $config[21]->config_value;
                $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
                $invoice_details['from_billing_country'] = $config[23]->config_value;
                $invoice_details['from_vat_number'] = $config[26]->config_value;
                $invoice_details['from_billing_email'] = $config[17]->config_value;
                $invoice_details['from_billing_phone'] = $config[18]->config_value;
                $invoice_details['to_billing_name'] = Auth::user()->name;
                $invoice_details['to_billing_email'] = Auth::user()->email;
                $invoice_details['tax_name'] = $config[24]->config_value;
                $invoice_details['tax_type'] = $config[14]->config_value;
                $invoice_details['tax_value'] = (float)($config[25]->config_value);
                $invoice_details['service_amount'] = $service_amount;
                $invoice_details['payment_gateway_charge'] = (float)($payment_gateway_charge);
                $invoice_details['subtotal'] = $sub_total;
                $invoice_details['tax_amount'] = round((float)($service_amount) * (float)($config[25]->config_value) / 100, 2);
                $invoice_details['invoice_amount'] = $booking_details->total_price;

                // Store transaction details in database before redirecting to PayPal
                $booking_transaction = new BookingTransaction();
                $booking_transaction->booking_transaction_id = $request->transaction_id;
                $booking_transaction->user_id = Auth::user()->user_id;
                $booking_transaction->booking_id = $booking_details->booking_id;
                $booking_transaction->payment_gateway_name = "Offline";
                $booking_transaction->transaction_currency = $config[1]->config_value;
                $booking_transaction->transaction_total = $booking_details->total_price;
                $booking_transaction->description = $service_name . " Service";
                $booking_transaction->transaction_date = now();
                $booking_transaction->invoice_details = json_encode($invoice_details);
                $booking_transaction->transaction_status = "pending";
                $booking_transaction->save();

                // Page redirect
                return redirect()->route('user.my-bookings')->with('success', trans('Your bank transfer transaction is currently pending. Once the transaction is completed, the admin will process your appointment booking!'));
            }
        } else {
            // Page redirect
            return redirect()->route('login');
        }
    }
}
