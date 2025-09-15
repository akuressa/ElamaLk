<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class BookingTransactionController extends Controller
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

    public function index()
    {
        // Queries
        $config = Configuration::get();
        $transactions = BookingTransaction::where('payment_gateway_name', '!=', 'Offline')
            ->orderBy('id', 'desc')
            ->get();
        $settings = Setting::where('status', 1)->first();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();

        // Get user transactions
        for ($i = 0; $i < count($transactions); $i++) {
            $user_details = User::where('user_id', $transactions[$i]->user_id)->first();

            // Check user details
            if ($user_details) {
                $transactions[$i]['name'] = $user_details->name;
                $transactions[$i]['userId'] = $user_details->user_id;
            } else {
                $transactions[$i]['name'] = 'User not found';
                $transactions[$i]['userId'] = '#';
            }
        }

        // View page
        return view('admin.pages.booking-transactions.index', compact('transactions', 'settings', 'currency'));
    }

    // Update transaction status
    public function bookingTransactionStatus($id, $status)
    {
        // Update status
        BookingTransaction::where('booking_transaction_id', $id)->update([
            'transaction_status' => $status
        ]);

        // Page redirect
        return redirect()->back()->with('success', trans('Transaction Status Updated Successfully!'));
    }

    // View transaction invoice
    public function viewInvoiceBooking($id)
    {
        // Get transaction details
        $transaction = BookingTransaction::where('booking_transaction_id', $id)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();
        $currencies = Currency::get();
        if (!empty($transaction['invoice_details'])) {
            $transaction['billing_details'] = json_decode($transaction['invoice_details'], true);
        } else {
            $transaction['billing_details'] = []; // Or handle the case accordingly
        }


        // View invoice page
        return view('admin.pages.booking-transactions.view-invoice', compact('transaction', 'settings', 'config', 'currencies'));
    }

    // Offline transactions
    public function offlineBookingTransactions()
    {
        // All offline transactions
        $transactions = BookingTransaction::where('payment_gateway_name', 'Offline')->get();
        $settings = Setting::where('status', 1)->first();
        $currencies = Currency::get();

        // Get customer transactions
        for ($i = 0; $i < count($transactions); $i++) {
            $user_details = User::where('user_id', $transactions[$i]->user_id)->first();

            // Check user details
            if ($user_details) {
                $transactions[$i]['name'] = $user_details->name;
                $transactions[$i]['userId'] = $user_details->user_id;
            } else {
                $transactions[$i]['name'] = 'User not found';
                $transactions[$i]['userId'] = '#';
            }
        }

        // View offline page
        return view('admin.pages.booking-transactions.offline', compact('transactions', 'settings', 'currencies'));
    }

    // Offline transaction status
    public function offlineBookingTransactionStatus(Request $request, $id, $status)
    {
        // Check status
        if ($status == "completed") {
            // Config
            $config = Configuration::get();

            // transaction details
            $transactionDetails = BookingTransaction::where('booking_transaction_id', $id)->first();

            // Transactions count
            $invoice_count = BookingTransaction::where("invoice_prefix", $config[15]->config_value)->count();
            $invoice_number = $invoice_count + 1;

            // Update transaction details
            BookingTransaction::where('booking_transaction_id', $id)->update([
                'booking_transaction_id' => $id,
                'invoice_prefix' => $config[15]->config_value,
                'invoice_number' => $invoice_number,
                'transaction_status' => 'completed',
            ]);

            // Update booking status
            Booking::where('booking_id', $transactionDetails->booking_id)->update([
                'status' => 1,
            ]);

            // Generate JSON
            $encode = json_decode($transactionDetails['invoice_details'], true);
            $details = [
                'from_billing_name' => $encode['from_billing_name'],
                'from_billing_email' => $encode['from_billing_email'],
                'from_billing_address' => $encode['from_billing_address'],
                'from_billing_city' => $encode['from_billing_city'],
                'from_billing_state' => $encode['from_billing_state'],
                'from_billing_country' => $encode['from_billing_country'],
                'from_billing_zipcode' => $encode['from_billing_zipcode'],
                'from_billing_phone' => $encode['from_billing_phone'],
                'booking_transaction_id' => $id,
                'to_billing_name' => $encode['to_billing_name'],
                'to_billing_email' => $encode['to_billing_email'],
                'invoice_currency' => $transactionDetails->transaction_currency,
                'service_amount' => $encode['service_amount'],
                'payment_gateway_charge' => $encode['payment_gateway_charge'],
                'subtotal' => $encode['subtotal'],
                'tax_amount' => $encode['tax_amount'],
                'invoice_amount' => $encode['invoice_amount'],
                'invoice_id' => $config[15]->config_value . $invoice_number,
                'invoice_date' => $transactionDetails->created_at,
                'description' => $transactionDetails->description,
                'email_heading' => $config[27]->config_value,
                'email_footer' => $config[28]->config_value,
            ];

            // Booking Details
            $booking_details = Booking::where('booking_id', $transactionDetails->booking_id)->first();

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
                'invoice_currency' => $transactionDetails->transaction_currency,
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
            return redirect()->route('admin.booking.offline.transactions')->with('success', trans('Status Updated Successfully!'));
        } elseif ($status == "pending") {
            // Update transaction status details
            BookingTransaction::where('booking_transaction_id', $id)->update([
                'booking_transaction_id' => $id,
                'transaction_status' => 'pending',
            ]);

            // Page redirect
            return redirect()->route('admin.booking.offline.transactions')->with('success', trans("Status Updated Successfully"));
        } else {
            // Update transaction status details
            BookingTransaction::where('booking_transaction_id', $id)->update([
                'booking_transaction_id' => $id,
                'transaction_status' => 'failed',
            ]);

            // Page redirect
            return redirect()->route('admin.booking.offline.transactions')->with('success', trans("Status Updated Successfully"));
        }
    }
}
