<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
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

    // My Transactions
    public function myTransactions()
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {

            // Setting
            $setting = Setting::where('status', 1)->first();

            $title = "My Transactions";

            // Booking Transactions
            $booking_transactions = BookingTransaction::where('user_id', Auth::user()->user_id)
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Return values
            $returnValues = compact('setting', 'config', 'title', 'booking_transactions');

            return view("user.pages.my-transactions.index", $returnValues);
        } else {
            return back();
        }
    }

    //  View Invoice
    public function viewInvoice($id)
    {
        $title = "Invoice";
        $transaction = BookingTransaction::where('booking_transaction_id', $id)->first();
        $setting = Setting::where('status', 1)->first();
        $config = Configuration::get();
        $currencies = Currency::get();
        $transaction['billing_details'] = json_decode($transaction['invoice_details'], true);

        // Get service name
        $booking = Booking::where('booking_id', $transaction->booking_id)->first()->business_service_id;
        $service_name = BusinessService::where('business_service_id', $booking)->first()->business_service_name;


        return view('user.pages.my-transactions.view-invoice', compact('transaction', 'setting', 'config', 'currencies', 'title', 'service_name'));
    }
}
