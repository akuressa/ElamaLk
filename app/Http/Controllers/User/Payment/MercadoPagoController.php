<?php

namespace App\Http\Controllers\User\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class MercadoPagoController extends Controller
{
    // Mercado Pago
    public function prepareMercadoPago(Request $request, $bookingId)
    {
        // Queries
        $config = Configuration::get();

        // Validate Mercado Pago access token
        if ($config[56]->config_value == null || $config[56]->config_value == "YOUR_MERCADO_PAGO_ACCESS_TOKEN") {
            return redirect()->route('user.my-bookings')->with('failed', trans('Something went wrong!'));
        } else {
            $mercado_pago_access_token = $config[56]->config_value;
        }

        // Get booking details
        $bookingDetails = Booking::where('booking_id', $bookingId)->first();
        if (!$bookingDetails) {
            return back();
        }

        // Paid amount
        $service_amount = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->amount;
        $service_name = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->business_service_name;

        $business_id = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->business_id;
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

        // Paid amount
        $amountToBePaid = $bookingDetails->total_price;
        $amountToBePaidMercadoPago = $bookingDetails->total_price * 100;

        // Get user details
        $userData = Auth::user();

        // Set Mercado Pago API endpoint for creating payments
        $url = 'https://api.mercadopago.com/checkout/preferences';

        // Prepare the payload
        $payload = [
            'items' => [
                [
                    'title' => $service_name,
                    'quantity' => 1,
                    'unit_price' => $amountToBePaidMercadoPago, // Set the plan price
                    'currency_id' => $config[1]->config_value
                ]
            ],
            'back_urls' => [
                'success' => route('payment.success'),
                'failure' => route('payment.failure'),
                'pending' => route('payment.pending')
            ],
            'auto_return' => 'approved',
        ];

        // Make the request to Mercado Pago's API
        $response = Http::withToken($mercado_pago_access_token)->post($url, $payload);

        // Check for success and retrieve the preference ID
        if ($response->successful()) {
            $preferenceId = $response['id']; // This is the Mercado Pago transaction ID

            // Prepare invoice details
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
            $invoice_details['invoice_amount'] = $bookingDetails->total_price;

            // Create a new transaction entry in the database
            $booking_transaction = new BookingTransaction();
            $booking_transaction->booking_transaction_id = $preferenceId;
            $booking_transaction->user_id = Auth::user()->user_id;
            $booking_transaction->booking_id = $bookingDetails->booking_id;
            $booking_transaction->payment_gateway_name = "MercadoPago";
            $booking_transaction->transaction_currency = $config[1]->config_value;
            $booking_transaction->transaction_total = $bookingDetails->total_price;
            $booking_transaction->description = $service_name . " Service";
            $booking_transaction->transaction_date = now();
            $booking_transaction->invoice_details = json_encode($invoice_details);
            $booking_transaction->transaction_status = "pending";
            $booking_transaction->save();

            // Redirect to Mercado Pago payment page
            return redirect($response['init_point']);
        } else {
            // Handle API error
            return redirect()->route('user.my-bookings')->with('failed', trans('Unable to create payment'));
        }
    }


    // Success
    public function success(Request $request)
    {
        // Retrieve necessary inputs from the query parameters
        $preferenceId = $request->query('preference_id');       

        // Config
        $config = Configuration::get();

        // Get transaction details based on the preference_id
        $transaction_details = BookingTransaction::where('booking_transaction_id', $preferenceId)->first();

        if (!$transaction_details) {
            return redirect()->route('user.my-bookings')->with('failed', trans('Transaction not found or already processed!'));
        }

        // Transactions count
        $invoice_count = BookingTransaction::where("invoice_prefix", $config[15]->config_value)->count();
        $invoice_number = $invoice_count + 1;

        // Update transaction details
        BookingTransaction::where('booking_transaction_id', $preferenceId)->update([
            'booking_transaction_id' => $preferenceId,
            'invoice_prefix' => $config[15]->config_value,
            'invoice_number' => $invoice_number,
            'transaction_status' => 'completed',
        ]);

        // Update booking status
        Booking::where('booking_id', $transaction_details->booking_id)->update([
            'status' => 1,
        ]);

        // Generate JSON
        $encode = json_decode($transaction_details['invoice_details'], true);
        $details = [
            'from_billing_name' => $encode['from_billing_name'],
            'from_billing_email' => $encode['from_billing_email'],
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
            'from_billing_phone' => $encode['from_billing_phone'],
            'booking_transaction_id' => $preferenceId,
            'to_billing_name' => $encode['to_billing_name'],
            'to_billing_email' => $encode['to_billing_email'],
            'invoice_currency' => $transaction_details->transaction_currency,
            'service_amount' => $encode['service_amount'],
            'payment_gateway_charge' => $encode['payment_gateway_charge'],
            'subtotal' => $encode['subtotal'],
            'tax_amount' => $encode['tax_amount'],
            'invoice_amount' => $encode['invoice_amount'],
            'invoice_id' => $config[15]->config_value . $invoice_number,
            'invoice_date' => $transaction_details->created_at,
            'description' => $transaction_details->description,
            'email_heading' => $config[27]->config_value,
            'email_footer' => $config[28]->config_value,
        ];

        // Booking Details
        $booking_details = Booking::where('booking_id', $transaction_details->booking_id)->first();

        $service = BusinessService::where('business_service_id', $booking_details->business_service_id)->first();
        $business = Business::where('business_id', $service->business_id)->first();
        $employee_name = BusinessEmployee::where('business_employee_id', $booking_details->business_employee_id)->first()->business_employee_name;

        // Admin Username
        $admin_username = User::where('role', 1)->first()->name;

        $details_business = [
            'app_name' => $encode['from_billing_name'],
            'business_name' => $business->business_name,
            'from_billing_name' => $encode['to_billing_name'],
            'service_name' => $service->business_service_name,
            'employee_name' => $employee_name,
            'booking_date' => $booking_details->booking_date,
            'booking_time' => $booking_details->booking_time,
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
        ];

        $details_admin = [
            'admin_username' => $admin_username,
            'business_username' => $business->business_name,
            'app_name' => $encode['from_billing_name'],
            'from_billing_name' => $config[16]->config_value,
            'from_billing_email' => $config[17]->config_value,
            'to_billing_name' => $business->business_name,
            'service_name' => $service->business_service_name,
            'employee_name' => $employee_name,
            'total' => $booking_details->total_price,
            'booking_date' => $booking_details->booking_date,
            'booking_time' => $booking_details->booking_time,
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
            'invoice_currency' => $transaction_details->transaction_currency,
        ];

        // Send email
        try {
            // Customer Email
            Mail::to($encode['to_billing_email'])->send(new \App\Mail\SendEmailInvoice($details));

            // Business Email
            Mail::to($business->business_email)->send(new \App\Mail\SendEmailBookingBusiness($details_business));

            // Admin Email
            Mail::to($encode['from_billing_email'])->send(new \App\Mail\SendEmailBookingAdmin($details_admin));
        } catch (\Exception $e) {
        }

        // Page redirect
        return redirect()->route('user.my-bookings')->with('success', trans('Booked Successfully!'));
    }

    // Failure
    public function failure(Request $request)
    {
        // Get the preference_id from the request
        $preferenceId = $request->query('preference_id');

        // Update the transaction status to FAILED
        BookingTransaction::where('booking_transaction_id', $preferenceId)->update([
            'booking_transaction_id' => $preferenceId,
            'transaction_status' => 'failed',
        ]);

        return redirect()->route('user.my-bookings')->with('failed', trans("Payment failed!"));
    }

    // Pending
    public function pending(Request $request)
    {
        // Get the preference_id from the request
        $preferenceId = $request->query('preference_id');

        // Update the transaction status to PENDING
        BookingTransaction::where('booking_transaction_id', $preferenceId)->update([
            'booking_transaction_id' => $preferenceId,
            'transaction_status' => 'pending',
        ]);

        return redirect()->route('user.my-bookings')->with('failed', trans("Payment is pending!"));
    }
}
