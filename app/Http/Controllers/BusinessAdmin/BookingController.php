<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
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

    // Booking
    public function index(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $bookings = Booking::where('bookings.business_id', $business_id)
            ->leftJoin('users', 'users.user_id', '=', 'bookings.user_id')
            ->leftJoin('business_services', 'business_services.business_service_id', '=', 'bookings.business_service_id')
            ->leftJoin('business_employees', 'business_employees.business_employee_id', '=', 'bookings.business_employee_id')
            ->leftJoin('booking_transactions', 'booking_transactions.booking_id', '=', 'bookings.booking_id')
            ->select('bookings.*', 'users.name', 'users.email', 'business_services.business_service_name', 'business_employees.business_employee_name', 'booking_transactions.booking_transaction_id', 'booking_transactions.transaction_total', 'booking_transactions.transaction_status', 'booking_transactions.payment_gateway_name')
            ->orderBy('bookings.id', 'desc')
            ->get();

        return view('business-admin.pages.bookings.index', compact('bookings'));
    }

    // Reschedule Booking
    public function rescheduleBooking(Request $request, $business_id, $booking_id)
    {

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        if ($request->date <= Carbon::now()->format('Y-m-d')) {
            return redirect()->back()->with('failed', trans('Please select a valid date!'));
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time_slot' => 'required',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Update Booking
        Booking::where('booking_id', $booking_id)->update([
            'booking_date' => $request->date,
            'booking_time' => $request->time_slot,
        ]);

        // Booking Details
        $booking_details = Booking::where('booking_id', $booking_id)->first();

        // Transactions Details
        $transactionDetails = BookingTransaction::where('booking_id', $booking_details->booking_id)->first();
        $encode = json_decode($transactionDetails['invoice_details'], true);

        // Service and Employee Details
        $service_name = BusinessService::where('business_service_id', $booking_details->business_service_id)->first()->business_service_name;
        $employee_name = BusinessEmployee::where('business_employee_id', $booking_details->business_employee_id)->first()->business_employee_name;

        // Customer Email
        $user = User::where('user_id', $booking_details->user_id)->first();
        $user_email = $user->email;

        // Business Email
        $business = Business::where('business_id', $booking_details->business_id)->first();

        // Booking Details
        $booking_details = Booking::where('booking_id', $booking_id)->first();

        // Transactions Details
        $transactionDetails = BookingTransaction::where('booking_id', $booking_details->booking_id)->first();
        $encode = json_decode($transactionDetails['invoice_details'], true);

        // Customer Details
        $details_customer = [
            'app_name' => $encode['from_billing_name'],
            'business_name' => $business->business_name,
            'service_name' => $service_name,
            'employee_name' => $employee_name,
            'booking_date' => $request->date,
            'booking_time' => $request->time_slot,
            'from_billing_name' => $encode['from_billing_name'],
            'from_billing_email' => $encode['from_billing_email'],
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
            'from_billing_phone' => $encode['from_billing_phone'],
            'to_billing_name' => $user_email,
        ];

        try {
            // Customer Email
            Mail::to($user_email)->send(new \App\Mail\SendEmailBookingRescheduleCustomer($details_customer));
        } catch (\Exception $e) {
        }

        return redirect()->route('business-admin.bookings.index', ['business_id' => $business_id])->with('success', trans('Booking rescheduled successfully!'));
    }

    // Cancel Booking
    public function cancelBooking(Request $request, $business_id, $booking_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        Booking::where('booking_id', $booking_id)->update([
            'status' => -1,
            'is_refund' => 1,
            'refund_message' => "Cancelled by Business",
        ]);

        $config = Configuration::get();

        // Booking Details
        $booking_details = Booking::where('booking_id', $booking_id)->first();

        // Transactions Details
        $transactionDetails = BookingTransaction::where('booking_id', $booking_details->booking_id)->first();
        $encode = json_decode($transactionDetails['invoice_details'], true);

        // Service and Employee Details
        $service_name = BusinessService::where('business_service_id', $booking_details->business_service_id)->first()->business_service_name;
        $employee_name = BusinessEmployee::where('business_employee_id', $booking_details->business_employee_id)->first()->business_employee_name;

        // Customer Email
        $user = User::where('user_id', $booking_details->user_id)->first();
        $user_email = $user->email;
        $user_name = $user->name;

        // Business Email
        $business = Business::where('business_id', $booking_details->business_id)->first();
        $business_user = User::where('user_id', $business->user_id)->first();
        $business_email = $business_user->email;
        $business_name = $business_user->name;

        // Customer Details
        $details_customer = [
            'app_name' => $encode['from_billing_name'],
            'business_name' => $business_name,
            'service_name' => $service_name,
            'employee_name' => $employee_name,
            'booking_date' => $booking_details->booking_date,
            'booking_time' => $booking_details->booking_time,
            'from_billing_name' => $encode['from_billing_name'],
            'from_billing_email' => $encode['from_billing_email'],
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
            'from_billing_phone' => $encode['from_billing_phone'],
            'to_billing_name' => $user_email,
            'amount' => $booking_details->total_price,
        ];

        // Business Details
        $details_business = [
            'app_name' => $encode['from_billing_name'],
            'business_name' => $business_name,
            'from_billing_name' => $user_name,
            'from_billing_email' => $user_email,
            'service_name' => $service_name,
            'employee_name' => $employee_name,
            'booking_date' => $booking_details->booking_date,
            'booking_time' => $booking_details->booking_time,
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
        ];

        // Admin Username
        $admin = User::where('role', 1)->first();
        $admin_username = $admin->name;
        $admin_email = $details_business['from_billing_email'];

        // Admin Details
        $details_admin = [
            'app_name' => $config[16]->config_value,
            'admin_username' => $admin_username,
            'business_name' => $business->business_name,
            'customer_username' => $user_name,
            'from_billing_name' => $business_name,
            'to_billing_name' => $encode['from_billing_name'],
            'service_name' => $service_name,
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

        try {
            // Customer Email
            Mail::to($user_email)->send(new \App\Mail\SendEmailBookingCancelCustomer($details_customer));

            // Admin Email
            Mail::to($admin_email)->send(new \App\Mail\SendEmailBookingCancelAdmin($details_admin));

            // Business Email
            Mail::to($business_email)->send(new \App\Mail\SendEmailBookingCancelBusiness($details_business));
        } catch (\Exception $e) {
        }

        return redirect()->route('business-admin.bookings.index', ['business_id' => $business_id])->with('success', trans('Booking cancelled successfully!'));
    }
}
