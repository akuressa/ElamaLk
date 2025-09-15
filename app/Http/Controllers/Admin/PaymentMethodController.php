<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
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

    // Payment Methods
    public function index()
    {
        $payment_methods = PaymentGateway::where('status', '>=', 0)->get();
        return view('admin.pages.payment-methods.index', compact('payment_methods'));
    }

    // Activate Payment Method
    public function deletePaymentMethod(Request $request)
    {
        // Payment gateway details
        $payment_gateway_details = PaymentGateway::where('payment_gateway_id', $request->payment_gateway_id)->first();

        // Check payment gateway exists
        if ($payment_gateway_details) {
            // Check payment gateway
            if ($payment_gateway_details->status == 0) {
                $status = 1;
            } else {
                $status = 0;
            }

            // Update payment gateway
            PaymentGateway::where('payment_gateway_id', $request->query('payment_gateway_id'))->update(['status' => $status]);
            // Page redirect
            return redirect()->route('admin.payment-methods.index')->with('success', trans('Payment Method Status Updated Successfully!'));
        }

        return redirect()->route('admin.payment-methods.index')->with('failed', trans('Payment Method not found!'));
    }
}
