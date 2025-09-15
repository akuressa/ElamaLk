<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
       
        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        } else {
            $user_id = $user->user_id;

            $overall_businesses = Business::where('user_id', $user_id)->count();
            $business_ids = Business::where('user_id', $user_id)->pluck('business_id');
            $overall_services = BusinessService::whereIn('business_id', $business_ids)->count();
            $overall_employees = BusinessEmployee::whereIn('business_id', $business_ids)->count();
            $booking_ids = Booking::whereIn('business_id', $business_ids)->pluck('booking_id');

            $overall_bookings = [];
            $overall_transactions = [];
            for ($month = 1; $month <= 12; $month++) {
                $startDate = Carbon::create(date('Y'), $month);
                $endDate = $startDate->copy()->endOfMonth();
                $bookings = Booking::whereIn('business_id', $business_ids)->where('status', 1)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count();
                $transactions = BookingTransaction::whereIn('booking_id', $booking_ids)->where('transaction_status', 'completed')->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->sum('transaction_total');
                $overall_bookings[$month] = $bookings;
                $overall_transactions[$month] = $transactions;
            }
            $overall_bookings = implode(',', $overall_bookings);
            $overall_transactions = implode(',', $overall_transactions);

            return view('business.pages.dashboard.index', compact('overall_businesses', 'overall_services', 'overall_employees', 'overall_bookings', 'overall_transactions'));
        }
    }
}
