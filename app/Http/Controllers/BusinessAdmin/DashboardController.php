<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Setting;
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
        $this->middleware('auth');
    }

    // Dashboard
    public function index(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();
        $currency = Currency::where('iso_code', $config['1']->config_value)->first();

        $booking_ids = Booking::where('business_id', $business_id)->pluck('booking_id');
        $this_month_income = BookingTransaction::whereIn('booking_id', $booking_ids)->where('transaction_status', 'completed')->whereMonth('created_at', Carbon::now()->month)->sum('transaction_total');
        $today_income = BookingTransaction::whereIn('booking_id', $booking_ids)->where('transaction_status', 'completed')->whereDate('created_at', Carbon::today())->sum('transaction_total');
        $overall_bookings = Booking::where('business_id', $business_id)->where('status', 1)->count();
        $today_bookings = Booking::where('business_id', $business_id)->where('status', 1)->whereDate('created_at', Carbon::today())->count();

        $monthIncome = [];
        $monthBookings = [];
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create(date('Y'), $month);
            $endDate = $startDate->copy()->endOfMonth();
            $incomes = BookingTransaction::whereIn('booking_id', $booking_ids)->where('transaction_status', 'completed')->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->sum('transaction_total');
            $bookings = Booking::where('business_id', $business_id)->where('status', 1)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count();
            $monthIncome[$month] = $incomes;
            $monthBookings[$month] = $bookings;
        }
        $monthIncome = implode(',', $monthIncome);
        $monthBookings = implode(',', $monthBookings);

        $bookings = Booking::where('business_id', $business_id)
            ->where('status', 1)
            ->get()
            ->map(function ($booking) {
                // Extract start and end times from booking_time
                [$start_time, $end_time] = explode(' - ', $booking->booking_time);

                // Combine booking_date with start_time and end_time
                $start = Carbon::createFromFormat('Y-m-d H:i', $booking->booking_date . ' ' . $start_time);
                $end = Carbon::createFromFormat('Y-m-d H:i', $booking->booking_date . ' ' . $end_time);

                return [
                    'extendedProps' => [
                        'booking_id' => $booking->booking_id,
                    ],
                    'start' => $start,
                    'end' => $end,
                ];
            })
            ->toJson();

        $time_zone = Configuration::where('config_key', 'timezone')->first()->config_value;

        // Return the dashboard view with the business ID
        return view('business-admin.pages.dashboard.index', ['business_id' => $business_id], compact('time_zone', 'bookings', 'settings', 'config', 'currency', 'this_month_income', 'today_income', 'overall_bookings', 'today_bookings', 'monthIncome', 'monthBookings'));
    }
}
