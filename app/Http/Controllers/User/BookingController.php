<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Models\Setting;
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
    public function booking(Request $request)
    {
        // Queries
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {

            // Setting
            $setting = Setting::where('status', 1)->first();

            // Currency
            $currency = Currency::where('iso_code', $config['1']->config_value)->first();

            // Gateways
            $gateways = PaymentGateway::where('is_enabled', true)->where('status', 1)->get();

            // Business Details
            $business = Business::where('business_id', $request->business_id)->first();

            $business_user_id = $business->user_id;

            $user = User::where('user_id', $business_user_id)->first();

            $planDetails = json_decode($user->plan_details, true); // Decoded as array

            // Step 2: Decode plan_features since it's a nested JSON string
            $planFeatures = is_string($planDetails['plan_features'])
                ? json_decode($planDetails['plan_features'], true)
                : $planDetails['plan_features'];

            $payment_gateway_percentage = $planFeatures['payment_gateway_charge'];

            $business_services = BusinessService::where('business_id', $request->business_id)
                ->where('status', 1)
                ->get()
                ->map(function ($service) {
                    $service->business_employee_ids = json_decode($service->business_employee_ids, true); // Decode as needed
                    return $service;
                });

            // title
            $title = $business->business_name;

            //Employees
            $business_employees = BusinessEmployee::where('business_id', $request->business_id)->where('status', 1)->get();

            // Return values
            $returnValues = compact('setting', 'config', 'business', 'business_services', 'business_employees', 'currency', 'gateways', 'title', 'payment_gateway_percentage');

            return view("user.pages.book-appointment.index", $returnValues);
        } else {
            return back();
        }
    }

    // Appointment Booking
    public function appointmentBooking(Request $request)
    {

        // dd($request->all());

         // Validation
         $validator = Validator::make($request->all(), [
            'business_id' => 'required|string|max:255',
            'business_service_id' => 'required|string|max:255',
            'business_employee_id' => 'required|string|max:255',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'phone_number' => 'required',
        ]);



        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $config = Configuration::all();
        $total_price = BusinessService::where('business_service_id', $request->business_service_id)->first()->amount;

        $business_id = BusinessService::where('business_service_id', $request->business_service_id)->first()->business_id;

        $business_user_id = Business::where('business_id', $business_id)->first()->user_id;

        $user = User::where('user_id', $business_user_id)->first();

        $planDetails = json_decode($user->plan_details, true); // Decoded as array

        // Step 2: Decode plan_features since it's a nested JSON string
        $planFeatures = is_string($planDetails['plan_features']) ? json_decode($planDetails['plan_features'], true) : $planDetails['plan_features'];

        $payment_gateway_percentage = $planFeatures['payment_gateway_charge'];

        $payment_gateway_charge = (float)($total_price) * ($payment_gateway_percentage / 100);

        $amountToBePaid = ((float)($total_price) * (float)($config[25]->config_value) / 100) + (float)($total_price) + (float)($payment_gateway_charge);
        $amountToBePaidPaise = round($amountToBePaid, 2);

        $booking = new Booking();
        $booking->booking_id = uniqid();
        $booking->user_id = Auth::user()->user_id;
        $booking->business_id = $request->business_id;
        $booking->business_service_id = $request->business_service_id;
        $booking->business_employee_id = $request->business_employee_id;
        $booking->booking_date = $request->date;
        $booking->booking_time = $request->time_slot;
        $booking->total_price = $amountToBePaidPaise;
        $booking->phone_number = $request->phone_number;
        $booking->notes = $request->notes;
        $booking->status = 0;
        $booking->save();

        // dd($booking);



        // Payment Gateway
        $payment_mode = PaymentGateway::where('payment_gateway_id', $request->payment_gateway_id)->first();
        //   dd($payment_mode->payment_gateway_name);
        if ($payment_mode->payment_gateway_name == "Paypal") {
            // dd('Check key and secretaaddff');
            // Check key and secret
            if ($config[4]->config_value != "YOUR_PAYPAL_CLIENT_ID" || $config[5]->config_value != "YOUR_PAYPAL_SECRET") {
                return redirect()->route('bookingPaymentWithPaypal', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else if ($payment_mode->payment_gateway_name == "Razorpay") {
            // dd('Check key and secretaaddssdf');
            // Check key and secret
            if ($config[6]->config_value != "YOUR_RAZORPAY_KEY" || $config[7]->config_value != "YOUR_RAZORPAY_SECRET") {
                return redirect()->route('bookingPaymentWithRazorpay', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else if ($payment_mode->payment_gateway_name == "Onepay") {
            // dd($config[53]->config_value);
            // dd('Check key and secret');
            // Check key and secret
            if ($config[53]->config_value != "") {
                // dd($config[53]->config_value);
                // return redirect()->route('admin.bookingOnepay', ['bookingId' => $booking->booking_id]);
                return redirect()->route('user.appointment.booktest', ['bookingId' => $booking->booking_id]);
                // return redirect()->route('admin.paywithonepay', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else if ($payment_mode->payment_gateway_name == "Stripe") {
            // dd('Check key and secretaaaaa');
            // Check key and secret
            if ($config[9]->config_value != "YOUR_STRIPE_PUB_KEY" || $config[10]->config_value != "YOUR_STRIPE_SECRET") {
                return redirect()->route('bookingPaymentWithStripe', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else if ($payment_mode->payment_gateway_name == "Paystack") {
            // dd('Check key and Paystack');
            // Check key and secret
            if ($config[37]->config_value != "PAYSTACK_PUBLIC_KEY" || $config[38]->config_value != "PAYSTACK_SECRET_KEY") {
                return redirect()->route('bookingPaymentWithPaystack', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else if ($payment_mode->payment_gateway_name == "Mollie") {
            // dd('Check key and Mollie');
            // Check key and secret
            if ($config[41]->config_value != "mollie_key") {
                return redirect()->route('bookingPaymentWithMollie', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else if ($payment_mode->payment_gateway_name == "Bank Transfer") {
            // dd('Check key and Bank Transfer');
            // Check key and secret
            if ($config[31]->config_value != "") {
                return redirect()->route('bookingPaymentWithOffline', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        }else if ($payment_mode->payment_gateway_name == "Mercado Pago") {
            // dd('Check key and Mercado Pago');
            // Check key and secret
            if ($config[55]->config_value != "YOUR_MERCADO_PAGO_PUBLIC_KEY" || $config[56]->config_value != "YOUR_MERCADO_PAGO_ACCESS_TOKEN") {
                return redirect()->route('bookingPaymentWithMercadoPago', $booking->booking_id);
            } else {
                return redirect()->back()->with('failed', trans('Something went wrong!'));
            }
        } else {
            // dd('Check key and secretaaddffggg');
            return redirect()->back()->with('failed', trans('Something went wrongddd!'));
        }
    }

    // My Bookings
    public function myBookings(Request $request)
    {
        $config = Configuration::get();

        // Check website
        if ($config[43]->config_value == "yes") {

            // Setting
            $setting = Setting::where('status', 1)->first();

            $my_bookings =  Booking::where('bookings.user_id', Auth::user()->user_id)
                ->leftJoin('business_services', 'bookings.business_service_id', '=', 'business_services.business_service_id')
                ->leftJoin('business_employees', 'bookings.business_employee_id', '=', 'business_employees.business_employee_id')
                ->select('bookings.*', 'business_services.business_service_name', 'business_employees.business_employee_name')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $title = "My Bookings";

            // Return values
            $returnValues = compact('setting', 'config', 'my_bookings', 'title');

            return view("user.pages.my-bookings.index", $returnValues);
        } else {
            return back();
        }
    }

    // Cancel Booking
    public function cancelBooking(Request $request, $booking_id)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'bank_details' => 'required|string',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        if ($request->bank_details == '') {
            return redirect()->route('user.my-bookings')->with('failed', trans('Please enter bank details!'));
        }

        Booking::where('booking_id', $booking_id)->update([
            'status' => -1,
            'is_refund' => 1,
            'refund_message' => $request->bank_details,
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

        return redirect()->route('user.my-bookings')->with('success', trans('Booking cancelled successfully!'));
    }
}
