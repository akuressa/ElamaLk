<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use App\Classes\AvailableVersion;
use App\Models\Booking;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();
        $currency = Currency::where('iso_code', $config['1']->config_value)->first();
        $this_month_income = Transaction::where('transaction_status', 'completed')->whereMonth('created_at', Carbon::now()->month)->sum('transaction_total');
        $today_income = Transaction::where('transaction_status', 'completed')->whereDate('created_at', Carbon::today())->sum('transaction_total');
        $overall_users = User::where('role', 2)->where('status', 1)->count();
        $today_users = User::where('role', 2)->where('status', 1)->whereDate('created_at', Carbon::today())->count();

        $monthIncome = [];
        $monthUsers = [];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create(date('Y'), $month);
            $endDate = $startDate->copy()->endOfMonth();
            $sales = Transaction::where('transaction_status', 'completed')->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->sum('transaction_total');
            $users = User::where('role', 2)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count();
            $monthIncome[$month] = $sales;
            $monthUsers[$month] = $users;
        }
        $monthIncome = implode(',', $monthIncome);
        $monthUsers = implode(',', $monthUsers);

        //  booking transactions
        $booking_transactions = Booking::where('bookings.status', 1)
            ->leftJoin('booking_transactions', 'bookings.booking_id', '=', 'booking_transactions.booking_id')
            ->where('booking_transactions.transaction_status', 'completed')
            ->sum('booking_transactions.transaction_total');

        $this_month_booking_transactions = Booking::where('bookings.status', 1)
            ->leftJoin('booking_transactions', 'bookings.booking_id', '=', 'booking_transactions.booking_id')
            ->where('booking_transactions.transaction_status', 'completed')
            ->whereMonth('booking_transactions.created_at', Carbon::now()->month)
            ->sum('booking_transactions.transaction_total');

        $wallet_transactions = PaymentRequest::where('status', 1)
            ->sum('amount');

        $this_month_wallet_transactions = PaymentRequest::where('status', 1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');


        // Default message
        $available = new AvailableVersion;
        $resp_data = $available->availableVersion();

        if ($resp_data) {
            if ($resp_data['status'] == true && $resp_data['update'] == true) {
                // Store success message in session
                session()->flash('message', trans('<a href="' . route("admin.check") . '" class="text-white">A new version is available! <span style="position: absolute; right: 7.5vh;">' . trans("Install") . '</span></a>'));
            }
        }

        return view('admin.pages.dashboard.index', compact('booking_transactions', 'this_month_booking_transactions', 'wallet_transactions', 'this_month_wallet_transactions', 'this_month_income', 'today_income', 'overall_users', 'today_users', 'currency', 'settings', 'monthIncome', 'monthUsers'));
    }
}
