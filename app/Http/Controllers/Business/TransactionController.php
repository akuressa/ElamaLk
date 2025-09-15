<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
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

    // Transactions
    public function index()
    {
        // Queries
        $user = Auth::user();

        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }
        // Check active plan

        $transactions = Transaction::where('user_id', Auth::user()->user_id)->orderBy('created_at', 'desc')->get();
        $settings = Setting::where('status', 1)->first();
        $currencies = Currency::get();

        // Page view
        return view('business.pages.transactions.index', compact('transactions', 'settings', 'currencies'));
    }

    //  View Invoice
    public function viewInvoice($id)
    {
        $transaction = Transaction::where('transaction_id', $id)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();
        $currencies = Currency::get();
        $transaction['billing_details'] = json_decode($transaction['invoice_details'], true);


        return view('business.pages.transactions.view-invoice', compact('transaction', 'settings', 'config', 'currencies'));
    }
}
