<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessPlanSubscription;
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

            // Check if user has active subscription for this business
            $hasActiveSubscription = false;
            $userSubscription = null;
            if (Auth::check()) {
                $userSubscription = BusinessPlanSubscription::where('user_id', Auth::user()->user_id)
                    ->where('business_id', $request->business_id)
                    ->where('status', 'active')
                    ->where('end_date', '>', Carbon::now())
                    ->with('businessPlan')
                    ->first();
                $hasActiveSubscription = $userSubscription ? true : false;
            }

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
            $returnValues = compact('setting', 'config', 'business', 'business_services', 'business_employees', 'currency', 'gateways', 'title', 'payment_gateway_percentage', 'hasActiveSubscription', 'userSubscription');

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

        // Check if user has active subscription for this business
        $activeSubscription = BusinessPlanSubscription::where('user_id', Auth::user()->user_id)
            ->where('business_id', $business_id)
            ->where('status', 'active')
            ->where('end_date', '>', Carbon::now())
            ->with('businessPlan')
            ->first();
            
        $hasActiveSubscription = $activeSubscription ? true : false;
        
        // Check if the selected service is included in the subscription plan
        $serviceIncludedInPlan = false;
        if ($hasActiveSubscription && $activeSubscription->businessPlan) {
            $planServiceIds = $activeSubscription->businessPlan->business_service_ids ?? [];
            $serviceIncludedInPlan = in_array($request->business_service_id, $planServiceIds);
        }

        // If user has active subscription AND service is included in plan, no charges
        if ($hasActiveSubscription && $serviceIncludedInPlan) {
            $amountToBePaidPaise = 0;
        } else {
            // Calculate service charge (10% as per frontend) for all other cases
            $service_charge = (float)($total_price) * (10 / 100);
            
            // Calculate total amount (service price + service charge)
            $amountToBePaid = (float)($total_price) + (float)($service_charge);
            $amountToBePaidPaise = round($amountToBePaid, 2);
        }

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


        // Payment Gateway
        if (!$request->payment_gateway_id) {
            $invoice_details = [
                'from_billing_name' => $config[16]->config_value,
                'from_billing_address' => $config[19]->config_value,
                'from_billing_city' => $config[20]->config_value,
                'from_billing_state' => $config[21]->config_value,
                'from_billing_zipcode' => $config[22]->config_value,
                'from_billing_country' => $config[23]->config_value,
                'from_vat_number' => $config[26]->config_value,
                'from_billing_phone' => $config[18]->config_value,
                'from_billing_email' => $config[17]->config_value,
                'to_billing_name' => $billing_details['billing_name'] ?? $user->name,   
                'to_billing_address' => $billing_details['billing_address'] ?? '',
                'to_billing_city' => $billing_details['billing_city'] ?? '',
                'to_billing_state' => $billing_details['billing_state'] ?? '',
                'to_billing_zipcode' => $billing_details['billing_zipcode'] ?? '',
                'to_billing_country' => $billing_details['billing_country'] ?? '',
                'to_billing_phone' => $billing_details['billing_phone'] ?? $user->phone,
                'to_billing_email' => $billing_details['billing_email'] ?? $user->email,
                'to_vat_number' => $billing_details['vat_number'] ?? '',
                'tax_name' => $config[24]->config_value,
                'tax_type' => $config[14]->config_value,
                'tax_value' => 0,
                'subtotal' => 0,
                'tax_amount' => 0,
                'payment_gateway_charge' => 0,
                'invoice_amount' => $booking->total_price,
            ];
            // User has active subscription and service is included - no payment required
            $booking->status = 1; // Mark as confirmed
            $booking->save();
            
            // Create a transaction record for subscription booking
            $transaction = new BookingTransaction();
            $transaction->booking_transaction_id = uniqid();
            $transaction->booking_id = $booking->booking_id;
            $transaction->user_id = Auth::user()->user_id;
            $transaction->payment_gateway_name = "Subscription";
            $transaction->invoice_details = json_encode($invoice_details);
            $transaction->transaction_currency = $config[1]->config_value;
            $transaction->transaction_total = 0; // No charge for subscription users
            $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
            $transaction->transaction_status = "completed";
            $transaction->save();
            
            return redirect()->route('user.my-bookings')->withInput()->with('success', trans('Appointment booked successfully! No payment required as this service is included in your subscription plan.'));
        }
        
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
        if (!$booking_details) {
            return redirect()->back()->with('failed', 'Booking not found.');
        }

        // Transactions Details
        $transactionDetails = BookingTransaction::where('booking_id', $booking_details->booking_id)->first();
        if (!$transactionDetails) {
            return redirect()->back()->with('failed', 'Transaction details not found.');
        }
        $encode = json_decode($transactionDetails['invoice_details'], true);
        if (!$encode) {
            // Provide default values if invoice_details is null or invalid JSON
            $encode = [
                'from_billing_name' => 'Elama.lk',
            ];
        }

        // Service and Employee Details
        $service = BusinessService::where('business_service_id', $booking_details->business_service_id)->first();
        if (!$service) {
            return redirect()->back()->with('failed', 'Service not found.');
        }
        $service_name = $service->business_service_name;
        
        $employee = BusinessEmployee::where('business_employee_id', $booking_details->business_employee_id)->first();
        if (!$employee) {
            return redirect()->back()->with('failed', 'Employee not found.');
        }
        $employee_name = $employee->business_employee_name;

        // Customer Email
        $user = User::where('user_id', $booking_details->user_id)->first();
        if (!$user) {
            return redirect()->back()->with('failed', 'User not found.');
        }
        $user_email = $user->email;
        $user_name = $user->name;

        // Business Email
        $business = Business::where('business_id', $booking_details->business_id)->first();
        if (!$business) {
            return redirect()->back()->with('failed', 'Business not found.');
        }
        $business_user = User::where('user_id', $business->user_id)->first();
        if (!$business_user) {
            return redirect()->back()->with('failed', 'Business user not found.');
        }
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
