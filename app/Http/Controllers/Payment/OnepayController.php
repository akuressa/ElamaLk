<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Configuration;
use Illuminate\Support\Facades\Mail;
use PSpell\Config;
use App\Models\Plan;
use App\Models\User;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessService;
use App\Models\BusinessEmployee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;


class OnepayController extends Controller
{
  public function redirectToOnepay(Request $request, $planId)
{
    // dd("Onepay redirect");
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // Fetch plan & config
    $plan = Plan::where('plan_id', $planId)->where('status', 1)->first();
    $config = Configuration::get();

    // dd($plan);

    if (!$plan) {
        return redirect()->back()->with('error', 'Invalid plan selected.');
    }

    $billing_details = json_decode($user->billing_details, true) ?? [];

    // Calculate payment gateway charge & tax similar to stripeCheckout
    $plan_features = is_string($plan->plan_features) ? json_decode($plan->plan_features, true) : $plan->plan_features;
    $payment_gateway_charge = round($plan->plan_price * ($plan_features['payment_gateway_charge'] / 100), 2);

    $tax_percent = (float) $config[25]->config_value; // example index for tax
    $tax_amount = round($plan->plan_price * $tax_percent / 100, 2);

    $subtotal = $plan->plan_price;
    $totalAmount = (int) ceil($subtotal);

    $reference = strtoupper(Str::random(12));

    // Invoice details (similar structure to stripeCheckout)
    $invoice_details = [
        'from_billing_name' => $config[16]->config_value,
        'from_billing_address' => $config[19]->config_value,
        'from_billing_city' => $config[20]->config_value,
        'from_billing_state' => $config[21]->config_value,
        'from_billing_zipcode' => $config[22]->config_value,
        'from_billing_country' => $config[23]->config_value,
        'from_vat_number' => $config[26]->config_value,
        'from_billing_phone' => $config[18]->config_value,
        'from_billing_email' => $config[17]->config_value,
        'to_billing_name' => $billing_details['billing_name'] ?? $user->name,
        'to_billing_address' => $billing_details['billing_address'] ?? '',
        'to_billing_city' => $billing_details['billing_city'] ?? '',
        'to_billing_state' => $billing_details['billing_state'] ?? '',
        'to_billing_zipcode' => $billing_details['billing_zipcode'] ?? '',
        'to_billing_country' => $billing_details['billing_country'] ?? '',
        'to_billing_phone' => $billing_details['billing_phone'] ?? $user->phone,
        'to_billing_email' => $billing_details['billing_email'] ?? $user->email,
        'to_vat_number' => $billing_details['vat_number'] ?? '',
        'tax_name' => $config[24]->config_value,
        'tax_type' => $config[14]->config_value,
        'tax_value' => $tax_percent,
        'subtotal' => $subtotal,
        'tax_amount' => $tax_amount,
        'payment_gateway_charge' => $payment_gateway_charge,
        'invoice_amount' => $totalAmount,
    ];

    // OnePay Credentials (Ideally, load from config or env)
    $appId = 'WF8X118E6EDF0C075805F';
    $token = '328ff36ed3189b4a291d5f2348839fd5fc8c3c267ec8e61bf64635f17d2a13e18c5e96360cf47e19.3SI1118E6EDF0C07580A6';
    $hashSalt = '1VO6118E6EDF0C075808A';

    $redirectUrl = route('web.index');

    $body = [
        'amount' => $totalAmount,
        'app_id' => $appId,
        'reference' => $reference,
        'customer_first_name' => $user->name,
        'customer_last_name' => '', // optionally split the name
        'customer_phone_number' => $user->phone ?? '+94770000000',
        'customer_email' => $user->email,
        'transaction_redirect_url' => $redirectUrl,
        'currency' => 'LKR'
    ];

    $bodyString = json_encode($body, JSON_UNESCAPED_SLASHES);
    $bodyStringNoSpaces = preg_replace('/\s+/', '', $bodyString) . $hashSalt;
    $hash = hash('sha256', $bodyStringNoSpaces);

    $response = Http::withHeaders([
        'Authorization' => $token,
        'Content-Type' => 'application/json'
    ])->post("https://merchant-api-live-v2.onepay.lk/api/ipg/gateway/request-payment-link/?hash=$hash", $body);


    if ($response->successful() && $response->json('status') == 1000) {

        // Store transaction before redirecting
        $transaction = new Transaction();
        $transaction->transaction_id = $reference;  // use your own generated reference
        $transaction->transaction_date = now();
        $transaction->user_id = $user->user_id;
        $transaction->plan_id = $plan->plan_id;
        $transaction->description = $plan->plan_name . " Plan";
        $transaction->payment_gateway_name = "OnePay";
        $transaction->transaction_total = $totalAmount;
        $transaction->transaction_currency = 'LKR';
        $transaction->invoice_details = json_encode($invoice_details);
        $transaction->transaction_status = "pending";
        $transaction->save();

        $redirectUrl = $response->json('data.gateway.redirect_url');
        return redirect($redirectUrl);

    } else {
        return redirect()->route('business.plans.index')->with('failed', 'OnePay payment failed to initiate.');
    }
}

public function bookingOnepayNewtwo(Request $request, $booking_id)
{
    // dd("Booking found");
    // Check if the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Get the user and booking details
    $user = Auth::user();
    $booking = Booking::where('booking_id', $booking_id)->first();

    // dd($booking);


    if (!$booking) {

        return redirect()->route('user.my-bookings')->with('failed', 'Booking not found.');
    }

    $config = Configuration::get();

    // OnePay Credentials (Ideally, load these from .env or config)
    $appId = 'WF8X118E6EDF0C075805F';
    $token = '328ff36ed3189b4a291d5f2348839fd5fc8c3c267ec8e61bf64635f17d2a13e18c5e96360cf47e19.3SI1118E6EDF0C07580A6';
    $hashSalt = '1VO6118E6EDF0C075808A';

    // Prepare the redirect URL after payment (where the user should be redirected after payment completion)
    $redirectUrl = route('user.my-bookings');  // Adjust this to your needs (e.g., after payment completion)
    
    // Set the callback URL for OnePay to notify us of payment status
    $callbackUrl = route('user.booking.callback');

    // Generate unique reference for the transaction
    $reference = strtoupper(Str::random(12));

    // Prepare payment data for OnePay
    $body = [
        'amount' => $booking->total_price, // Total price from the booking
        'app_id' => $appId,
        'reference' => $reference, // Unique reference generated above
        'customer_first_name' => $user->name,
        'customer_last_name' => '',   
        'customer_phone_number' => $user->phone ?? '+94770000000',
        'customer_email' => $user->email,
        'transaction_redirect_url' => $redirectUrl,
        'callback_url' => $callbackUrl, 
        'currency' => 'LKR'
    ];

    // Generate SHA256 hash for security (used by OnePay for validation)
    $bodyString = json_encode($body, JSON_UNESCAPED_SLASHES);
    $bodyStringNoSpaces = preg_replace('/\s+/', '', $bodyString) . $hashSalt;
    $hash = hash('sha256', $bodyStringNoSpaces);

    // Send the request to OnePay API to get the payment link
    $response = Http::withHeaders([
        'Authorization' => $token,
        'Content-Type' => 'application/json'
    ])->post("https://merchant-api-live-v2.onepay.lk/api/ipg/gateway/request-payment-link/?hash=$hash", $body);

    // Check if the response is successful and the payment link was generated
    if ($response->successful() && $response->json('status') == 1000) {
        // Store the booking transaction before redirecting to OnePay payment page
        $bookingTransaction = new BookingTransaction();
        $bookingTransaction->booking_transaction_id = $reference; // Use the reference generated above
        $bookingTransaction->transaction_date = now();
        $bookingTransaction->user_id = $user->user_id;
        $bookingTransaction->booking_id = $booking->booking_id; // Store booking ID for callback
        $bookingTransaction->transaction_total = $booking->total_price;
        $bookingTransaction->transaction_currency = 'LKR';
        $bookingTransaction->transaction_status = "pending"; // Set status as pending until payment completes
        $bookingTransaction->payment_gateway_name = 'OnePay';
        $bookingTransaction->save();

        // Redirect to OnePay payment page with the generated payment link
        $redirectUrl = $response->json('data.gateway.redirect_url');
        return redirect($redirectUrl);
    }

    // If the request to OnePay failed, return to the bookings page with an error message
    return redirect()->route('user.my-bookings')->with('failed', 'OnePay payment failed to initiate.');
}


public function bookingOnepayNew(Request $request, $booking_id)
{
    // dd("Booking found");
    // Check if the user is authenticated

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Get the user and booking details
    $user = Auth::user();
    $booking = Booking::where('booking_id', $booking_id)->first();


    if (!$booking) {

        return redirect()->route('user.my-bookings')->with('failed', 'Booking not found.');
    }

    $config = Configuration::get();

    // OnePay Credentials (Ideally, load these from .env or config)
    $appId = env('ONEPAY_APP_ID');
    $token = env('ONEPAY_TOKEN');
    $hashSalt = env('ONEPAY_HASH_SALT');

    // Prepare the redirect URL after payment (where the user should be redirected after payment completion)
    $redirectUrl = route('user.my-bookings');  // Adjust this to your needs (e.g., after payment completion)

    // Generate unique reference for the transaction
    $reference = strtoupper(Str::random(12));

    // Prepare payment data for OnePay
    $body = [
        'amount' => $booking->total_price, // Total price from the booking
        'app_id' => $appId,
        'reference' => $reference, // Unique reference generated above
        'customer_first_name' => $user->name,
        'customer_phone_number' => $user->phone ?? '+94770000000',
        'customer_email' => $user->email,
        'transaction_redirect_url' => $redirectUrl,
        'currency' => 'LKR'
    ];

    // Generate SHA256 hash for security (used by OnePay for validation)
    $bodyString = json_encode($body, JSON_UNESCAPED_SLASHES);
    $bodyStringNoSpaces = preg_replace('/\s+/', '', $bodyString) . $hashSalt;
    $hash = hash('sha256', $bodyStringNoSpaces);

    // Send the request to OnePay API to get the payment link
    $response = Http::withHeaders([
        'Authorization' => $token,
        'Content-Type' => 'application/json'
    ])->post("https://merchant-api-live-v2.onepay.lk/api/ipg/gateway/request-payment-link/?hash=$hash", $body);

    // Check if the response is successful and the payment link was generated
    if ($response->successful() && $response->json('status') == 1000) {
        // Store the transaction before redirecting to OnePay payment page
        $transaction = new Transaction();
        $transaction->transaction_id = $reference; // Use the reference generated above
        $transaction->transaction_date = now();
        $transaction->user_id = $user->user_id;
        $transaction->business_id = $booking->business_id;
        $transaction->transaction_total = $booking->total_price;
        $transaction->transaction_currency = 'LKR';
        $transaction->transaction_status = "pending"; // Set status as pending until payment completes
        $transaction->save();

        // Redirect to OnePay payment page with the generated payment link
        $redirectUrl = $response->json('data.gateway.redirect_url');
        return redirect($redirectUrl);
    }

    // If the request to OnePay failed, return to the bookings page with an error message
    return redirect()->route('user.my-bookings')->with('failed', 'OnePay payment failed to initiate.');

}




    public function handleCallback(Request $request)
    {   Log::info('Callback function:');
        Log::info('OnePay Callback Response:', $request->all());
        // Get transaction ID from OnePay callback
        $transactionId = $request->input('reference');

        // Retrieve transaction
        $transaction = Transaction::where('transaction_id', $transactionId)->first();

        if (!$transaction) {
            return redirect()->route('business.plans.index')->with('failed', trans('Transaction not found'));
        }

        // In a real implementation, you should verify the payment status with OnePay API here
        // For now, we'll assume the payment was successful
        $transaction->transaction_status = 'completed';
        $transaction->save();

        // Update user plan
        $this->activateUserPlan($transaction);

        return redirect()->route('business.plans.index')->with('success', trans('Payment completed successfully'));
    }

    // Callback function for booking payments
    public function handleBookingCallback(Request $request)
    {
        Log::info('Booking Callback function:');
        Log::info('OnePay Booking Callback Response:', $request->all());
        
        // Get transaction ID from OnePay callback
        $transactionId = $request->input('reference');

        // Retrieve booking transaction
        $bookingTransaction = BookingTransaction::where('booking_transaction_id', $transactionId)->first();

        if (!$bookingTransaction) {
            return redirect()->route('user.my-bookings')->with('failed', trans('Transaction not found'));
        }

        // In a real implementation, you should verify the payment status with OnePay API here
        // For now, we'll assume the payment was successful
        $bookingTransaction->transaction_status = 'completed';
        $bookingTransaction->save();

        // Update booking status
        $this->activateBooking($bookingTransaction);

        return redirect()->route('user.my-bookings')->with('success', trans('Booking payment completed successfully'));
    }

    private function generateHash(array $data)
    {
        // Convert array to JSON string without spaces
        $jsonString = json_encode($data, JSON_UNESCAPED_SLASHES);
        $jsonString = preg_replace('/\s+/', '', $jsonString);

        // Add hash salt
        $hashSalt = '1VO6118E6EDF0C075808A';
        $stringToHash = $jsonString . $hashSalt;

        // Generate SHA256 hash
        return hash('sha256', $stringToHash);
    }

    protected function activateUserPlan($transaction)
    {
        $user = User::where('user_id', $transaction->user_id)->first();
        $plan = Plan::where('plan_id', $transaction->plan_id)->first();
        $term_days = $plan->plan_validity;

        // Get current plan details if exists
        $user_plan_details = json_decode($user->plan_details, true) ?? [];

        // Check if user has an existing plan
        if (empty($user_plan_details) || !isset($user_plan_details['plan_validity'])) {
            // New plan activation
            $planDetails = [
                'plan_id' => $plan->plan_id,
                'plan_name' => $plan->plan_name,
                'plan_description' => $plan->plan_description,
                'plan_features' => $plan->plan_features,
                'plan_price' => $plan->plan_price,
                'plan_validity' => $plan->plan_validity,
                'is_trial' => $plan->is_trial,
                'is_private' => $plan->is_private,
                'is_recommended' => $plan->is_recommended,
                'is_customer_support' => $plan->is_customer_support,
                'plan_start_date' => now()->format('Y-m-d H:i:s'),
                'plan_end_date' => now()->addDays($plan->plan_validity)->format('Y-m-d H:i:s'),
            ];

            $user->plan_details = json_encode($planDetails);
            $user->save();

        } else {
            // Plan renewal or upgrade
            $message = "";

            if ($user_plan_details['plan_id'] == $transaction->plan_id) {
                // Plan renewal
                $plan_validity = Carbon::parse($user_plan_details['plan_end_date']);
                if ($plan_validity->isPast()) {
                    $plan_validity = now();
                }
                $plan_validity->addDays($term_days);
                $message = "Plan renewed successfully!";
            } else {
                // Plan upgrade/downgrade
                $plan_validity = now()->addDays($term_days);
                $message = "Plan changed successfully!";
            }

            $planDetails = [
                'plan_id' => $plan->plan_id,
                'plan_name' => $plan->plan_name,
                'plan_description' => $plan->plan_description,
                'plan_features' => $plan->plan_features,
                'plan_price' => $plan->plan_price,
                'plan_validity' => $plan->plan_validity,
                'is_trial' => $plan->is_trial,
                'is_private' => $plan->is_private,
                'is_recommended' => $plan->is_recommended,
                'is_customer_support' => $plan->is_customer_support,
                'plan_start_date' => now()->format('Y-m-d H:i:s'),
                'plan_end_date' => $plan_validity->format('Y-m-d H:i:s'),
            ];

            $user->plan_details = json_encode($planDetails);
            $user->save();
        }

        // Generate invoice and send email (similar to your Stripe implementation)
        // You can move this to a separate method if needed
        $this->generateInvoiceAndSendEmail($transaction);
    }

    protected function activateBooking($bookingTransaction)
    {
        // Get booking details from transaction
        $booking = Booking::where('booking_id', $bookingTransaction->booking_id)->first();
        
        if (!$booking) {
            Log::error('Booking not found for transaction: ' . $bookingTransaction->booking_transaction_id);
            return;
        }

        // Update booking status to confirmed (1 = confirmed, 0 = pending)
        $booking->status = 1;
        $booking->save();

        // Update booking transaction status
        $bookingTransaction->status = 1;
        $bookingTransaction->save();
    }

}
