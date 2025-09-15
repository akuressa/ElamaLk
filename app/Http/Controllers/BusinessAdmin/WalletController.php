<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\PaymentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
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

    // Wallet
    public function index($business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Get Payment Requests
        $payment_requests = PaymentRequest::where('business_id', $business_id)->orderBy('created_at', 'desc')->get();

        // Get exact time
        $currentDate = Carbon::now()->format('Y-m-d');

        // Get booking ids
        $booking_ids = Booking::where('business_id', $business_id)->where('booking_date', '<=', $currentDate)->pluck('booking_id');

        // Get Transaction Total
        $transaction_total = BookingTransaction::whereIn('booking_id', $booking_ids)->where('transaction_status', 'completed')->sum('transaction_total');

        // Get Payment Request Succeeded Total
        $payment_succeeded_total = PaymentRequest::where('business_id', $business_id)->where('status', '>=', 0)->sum('amount');

        if ($transaction_total - $payment_succeeded_total > 0) {
            $wallet_total = $transaction_total - $payment_succeeded_total;
        } else {
            $wallet_total = 0;
        }

        // Currency
        $config = Configuration::get();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();

        return view('business-admin.pages.wallet.index', compact('wallet_total', 'currency', 'payment_requests'));
    }

    // Withdraw Request
    public function withdrawRequest(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'message' => 'string',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }  

        // Get booking ids
        $booking_ids = Booking::where('business_id', $business_id)->pluck('booking_id');

        // Get Transaction Total
        $transaction_total = BookingTransaction::whereIn('booking_id', $booking_ids)->where('transaction_status', 'completed')->sum('transaction_total');

        // Get Payment Request Succeeded Total
        $payment_succeeded_total = PaymentRequest::where('business_id', $business_id)->where('status', '>=', 0)->sum('amount');

        $wallet_total = $transaction_total - $payment_succeeded_total;

        // Check if the amount is less than wallet total
        if ($request->amount <= $wallet_total) {

            $amount = $request->amount;
            $message = $request->message;

            // Create Payment Request
            $payment_request = new PaymentRequest();
            $payment_request->payment_request_id = uniqid();
            $payment_request->business_id = $business_id;
            $payment_request->user_id = Auth::user()->user_id;
            $payment_request->amount = $amount;
            $payment_request->status = 0;
            $payment_request->message = $message;
            $payment_request->save();

            // business
            $business = Business::where('business_id', $business_id)->first();

            $user = User::where('user_id', $business->user_id)->first();

            // Config
            $config = Configuration::get();

            $from_billing_name = $config[16]->config_value;
            $from_billing_address = $config[19]->config_value;
            $from_billing_city = $config[20]->config_value;
            $from_billing_state = $config[21]->config_value;
            $from_billing_zipcode = $config[22]->config_value;
            $from_billing_country = $config[23]->config_value;
            $from_billing_phone = $config[18]->config_value;
            $from_billing_email = $config[17]->config_value;
            $amount = $request->amount;

            $details_business = [
                'app_name' => $config[0]->config_value,
                'from_billing_name' => $from_billing_name,
                'from_billing_address' => $from_billing_address,
                'from_billing_city' => $from_billing_city,
                'from_billing_state' => $from_billing_state,
                'from_billing_zipcode' => $from_billing_zipcode,
                'from_billing_country' => $from_billing_country,
                'from_billing_phone' => $from_billing_phone,
                'from_billing_email' => $from_billing_email,
                'business_name' => $business->business_name,
                'amount' => $amount,
                'currency' => $config[1]->config_value,
            ];

            $details_admin = [
                'app_name' => $config[0]->config_value,
                'admin_billing_name' => $from_billing_name,
                'admin_billing_address' => $from_billing_address,
                'admin_billing_city' => $from_billing_city,
                'admin_billing_state' => $from_billing_state,
                'admin_billing_zipcode' => $from_billing_zipcode,
                'admin_billing_country' => $from_billing_country,
                'admin_billing_phone' => $from_billing_phone,
                'admin_billing_email' => $from_billing_email,
                'business_name' => $business->business_name,
                'amount' => $amount,
                'currency' => $config[1]->config_value,
            ];

            try {
                // Business Email
                Mail::to($user->email)->send(new \App\Mail\SendEmailWalletBusiness($details_business));

                // Admin Email
                Mail::to($details_admin['admin_billing_email'])->send(new \App\Mail\SendEmailWalletAdmin($details_admin));
            } catch (\Exception $e) {
            }

            return redirect()->route('business-admin.wallet.index', ['business_id' => $business_id])->with('success', trans('Withdraw Request Sent Successfully!'));
        } else {
            return redirect()->route('business-admin.wallet.index', ['business_id' => $business_id])->with('failed', trans('Withdraw Request Failed!'));
        }
    }
}
