<?php

namespace App\Classes;

use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PSpell\Config;

class AppointmentBook
{
    public function upgrade($transactionId, $res)
    {
        // Queries
        $config = Configuration::get();

        $orderId = $transactionId;

        // Queries
        $transaction_details = BookingTransaction::where('booking_transaction_id', $orderId)->where('status', 1)->first();        

        // Transactions count
        $invoice_count = BookingTransaction::where("invoice_prefix", $config[15]->config_value)->count();
        $invoice_number = $invoice_count + 1;

        // Update transaction details
        BookingTransaction::where('booking_transaction_id', $orderId)->update([
            'booking_transaction_id' => $orderId,
            'invoice_prefix' => $config[15]->config_value,
            'invoice_number' => $invoice_number,
            'transaction_status' => 'completed',
        ]);

        // Update booking status
        Booking::where('booking_id', $transaction_details->booking_id)->update([
            'status' => 1,
        ]);

        // Generate JSON
        $encode = json_decode($transaction_details['invoice_details'], true);
        $details = [
            'from_billing_name' => $encode['from_billing_name'],
            'from_billing_email' => $encode['from_billing_email'],
            'from_billing_address' => $encode['from_billing_address'],
            'from_billing_city' => $encode['from_billing_city'],
            'from_billing_state' => $encode['from_billing_state'],
            'from_billing_country' => $encode['from_billing_country'],
            'from_billing_zipcode' => $encode['from_billing_zipcode'],
            'from_billing_phone' => $encode['from_billing_phone'],
            'booking_transaction_id' => $orderId,
            'to_billing_name' => $encode['to_billing_name'],
            'to_billing_email' => $encode['to_billing_email'],
            'invoice_currency' => $transaction_details->transaction_currency,
            'service_amount' => $encode['service_amount'],
            'payment_gateway_charge' => $encode['payment_gateway_charge'],
            'subtotal' => $encode['subtotal'],
            'tax_amount' => $encode['tax_amount'],
            'invoice_amount' => $encode['invoice_amount'],
            'invoice_id' => $config[15]->config_value . $invoice_number,
            'invoice_date' => $transaction_details->created_at,
            'description' => $transaction_details->description,
            'email_heading' => $config[27]->config_value,
            'email_footer' => $config[28]->config_value,
        ];

         // Booking Details
         $booking_details = Booking::where('booking_id', $transaction_details->booking_id)->first();

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
             'invoice_currency' => $transaction_details->transaction_currency,
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
    }
}
