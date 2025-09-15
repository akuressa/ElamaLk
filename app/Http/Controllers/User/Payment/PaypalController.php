<?php

namespace App\Http\Controllers\User\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{
    protected $apiContext;

    public function __construct()
    {
        // Fetch PayPal configuration from database
        $paypalConfiguration = Configuration::get();

        // Set up PayPal environment
        $clientId = $paypalConfiguration[4]->config_value;
        $clientSecret = $paypalConfiguration[5]->config_value;
        $mode = $paypalConfiguration[3]->config_value;

        if ($mode == "sandbox") {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $this->apiContext = new PayPalHttpClient($environment);
    }

    public function paywithpaypal(Request $request, $bookingId)
    {
        if (Auth::check()) {
            $bookingDetails = Booking::where('booking_id', $bookingId)->first();
            $service_name = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->business_service_name;
            $config = Configuration::get();

            if ($bookingDetails == null) {
                return back();
            } else {


                // Construct PayPal order request
                $request = new OrdersCreateRequest();
                $request->prefer('return=representation');
                $request->body = [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => $config[1]->config_value,
                            'value' => $bookingDetails->total_price,
                        ]
                    ]],
                    'application_context' => [
                        'cancel_url' => route('bookingPaymentPaypalStatus'),
                        'return_url' => route('bookingPaymentPaypalStatus'),
                    ]
                ];

                try {
                    // Create PayPal order
                    $response = $this->apiContext->execute($request);
                    foreach ($response->result->links as $link) {
                        if ($link->rel == 'approve') {
                            $redirectUrl = $link->href;
                            break;
                        }
                    }

                    $service_amount = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->amount;

                    $business_id = BusinessService::where('business_service_id', $bookingDetails->business_service_id)->first()->business_id;
                    $business_user_id = Business::where('business_id', $business_id)->first()->user_id;
                    $user = User::where('user_id', $business_user_id)->first();

                    $planDetails = json_decode($user->plan_details, true); // Decoded as array            
                    // Step 2: Decode plan_features since it's a nested JSON string
                    $planFeatures = is_string($planDetails['plan_features'])
                        ? json_decode($planDetails['plan_features'], true)
                        : $planDetails['plan_features'];

                    $payment_gateway_percentage = $planFeatures['payment_gateway_charge'];

                    $payment_gateway_charge = round((float)($service_amount) * ($payment_gateway_percentage / 100), 2);

                    $sub_total = (float)($service_amount) + (float)($payment_gateway_charge);

                    // Generate JSON
                    $invoice_details = [];

                    $invoice_details['from_billing_name'] = $config[16]->config_value;
                    $invoice_details['from_billing_address'] = $config[19]->config_value;
                    $invoice_details['from_billing_city'] = $config[20]->config_value;
                    $invoice_details['from_billing_state'] = $config[21]->config_value;
                    $invoice_details['from_billing_zipcode'] = $config[22]->config_value;
                    $invoice_details['from_billing_country'] = $config[23]->config_value;
                    $invoice_details['from_vat_number'] = $config[26]->config_value;
                    $invoice_details['from_billing_email'] = $config[17]->config_value;
                    $invoice_details['from_billing_phone'] = $config[18]->config_value;
                    $invoice_details['to_billing_name'] = Auth::user()->name;
                    $invoice_details['to_billing_email'] = Auth::user()->email;
                    $invoice_details['tax_name'] = $config[24]->config_value;
                    $invoice_details['tax_type'] = $config[14]->config_value;
                    $invoice_details['tax_value'] = (float)($config[25]->config_value);
                    $invoice_details['service_amount'] = $service_amount;
                    $invoice_details['payment_gateway_charge'] = (float)($payment_gateway_charge);
                    $invoice_details['subtotal'] = $sub_total;
                    $invoice_details['tax_amount'] = round((float)($service_amount) * (float)($config[25]->config_value) / 100, 2);
                    $invoice_details['invoice_amount'] = $bookingDetails->total_price;

                    // Store transaction details in database before redirecting to PayPal
                    $booking_transaction = new BookingTransaction();
                    $booking_transaction->booking_transaction_id = $response->result->id;
                    $booking_transaction->user_id = Auth::user()->user_id;
                    $booking_transaction->booking_id = $bookingDetails->booking_id;
                    $booking_transaction->payment_gateway_name = "Paypal";
                    $booking_transaction->transaction_currency = $config[1]->config_value;
                    $booking_transaction->transaction_total = $bookingDetails->total_price;
                    $booking_transaction->description = $service_name . " Service";
                    $booking_transaction->transaction_date = now();
                    $booking_transaction->invoice_details = json_encode($invoice_details);
                    $booking_transaction->transaction_status = "pending";
                    $booking_transaction->save();

                    // Redirect to PayPal for payment
                    return Redirect::away($redirectUrl);
                } catch (\Exception $ex) {
                    if (config('app.debug')) {
                        Session::put('error', 'Connection timeout');
                        return redirect()->route('user.my-bookings')->with('failed', trans('Something went wrong!'));
                    } else {
                        Session::put('error', 'Some error occur, sorry for the inconvenience');
                        return redirect()->route('user.my-bookings')->with('failed', trans('Something went wrong!'));
                    }
                    return redirect()->route('user.my-bookings')->with('failed', trans('Something went wrong!'));
                }
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function bookingPaypalPaymentStatus(Request $request)
    {

        if (empty($request->PayerID) || empty($request->token)) {
            Session::put('error', 'Payment cancelled!');
            return redirect()->route('user.my-bookings')->with('failed', trans('Something went wrong!'));
        }

        try {
            // Get the payment ID from the request
            $paymentId = $request->token;
            $orderId = $paymentId;

            // request
            $request = new OrdersCaptureRequest($paymentId);
            $response = $this->apiContext->execute($request);

            if ($response->statusCode == 201) {

                // Config
                $config = Configuration::get();

                // transaction details
                $transactionDetails = BookingTransaction::where('booking_transaction_id', $paymentId)->first();

                // Transactions count
                $invoice_count = BookingTransaction::where("invoice_prefix", $config[15]->config_value)->count();
                $invoice_number = $invoice_count + 1;

                // Update transaction details
                BookingTransaction::where('booking_transaction_id', $orderId)->update([
                    'booking_transaction_id' => $paymentId,
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
                    'booking_transaction_id' => $paymentId,
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
                return redirect()->route('user.my-bookings')->with('success', trans('Booked Successfully!'));
            } else {
                BookingTransaction::where('booking_transaction_id', $orderId)->update([
                    'booking_transaction_id' => $orderId,
                    'transaction_status' => 'failed',
                ]);

                return redirect()->route('user.my-bookings')->with('failed', trans("Payment cancelled!"));
            }
        } catch (HttpException $e) { // Corrected class name

            // Handle the HTTP exception
            // Log the error or display an error message
            // Example: Log::error('PayPal HTTP Exception: ' . $e->getMessage());

            // Set an error message for the user
            // Session::flash('error', 'An error occurred while communicating with PayPal. Please try again later.');

            // Redirect back to the user plans page or any other appropriate page
            return redirect()->route('user.my-bookings')->with('failed', trans("An error occurred while communicating with PayPal. Please try again later!"));
        }
    }
}
