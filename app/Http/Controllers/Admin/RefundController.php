<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Configuration;
use App\Models\Currency;
use Illuminate\Http\Request;

class RefundController extends Controller
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

    // Refund Requests
    public function index()
    {
        $config = Configuration::get();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();
        $refund_requests = Booking::leftJoin('users', 'users.user_id', '=', 'bookings.user_id')
            ->select('bookings.*', 'users.name', 'users.email')
            ->where('bookings.status', -1)
            ->orderBy('bookings.id', 'desc')
            ->get();

        return view('admin.pages.refund-requests.index', compact('refund_requests', 'currency'));
    }

    // Refund Request
    public function refundStatus(Request $request, $booking_id, $status)
    {
        // Update status
        Booking::where('booking_id', $booking_id)->update([
            'is_refund' => $status
        ]);

        if ($status == 0) {
            BookingTransaction::where('booking_id', $booking_id)->update([
                'transaction_status' => 'refunded'
            ]);
        } else {
            BookingTransaction::where('booking_id', $booking_id)->update([
                'transaction_status' => 'completed'
            ]);
        }

        // Page redirect
        return redirect()->back()->with('success', trans('Refund Status Updated Successfully!'));
    }
}
