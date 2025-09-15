<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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

    // Customer
    public function index()
    {
        $customers = User::where('role', '=', 2)->where('status', '>=', '0')->orderBy('created_at', 'desc')->get();
        return view('admin.pages.customers.index', compact('customers'));
    }

    // Activate Customer
    public function activationCustomer(Request $request)
    {
        $customer_id = $request->customer_id;
        // Find the customer by its ID
        $customer = User::where('user_id', $customer_id)->first();

        // Toggle the status of the customer
        $status = ($customer->status == 0) ? 1 : 0;
        User::where('user_id', $customer_id)->update(['status' => $status]);
        return redirect()->route('admin.customers.index')->with('success', trans('Customer Status Updated Successfully!'));
    }

    // Delete Customer
    public function deleteCustomer(Request $request)
    {
        $customer_id = $request->customer_id;
        // Find the customer by its ID
        $customer = User::where('user_id', $customer_id)->first();

        // Toggle the status of the customer
        $status = -1;
        User::where('user_id', $customer_id)->update(['status' => $status]);
        return redirect()->route('admin.customers.index')->with('success', trans('Customer Deleted Successfully!'));
    }

    // Switch Account
    public function switchAccount(Request $request)
    {
        $user_id = $request->user_id;
        // Log out the current admin user
        Auth::logout();

        // Fetch the user associated with the selected business
        $user = User::where('user_id', $user_id)->first();

        // Check if the customer exists
        if ($user) {
            // Log in the business user
            Auth::login($user);

            return redirect()->route('business.dashboard.index');
        } else {
            // Handle the error if the business user does not exist
            return redirect()->route('admin.customers.index')->with('error', trans('Customer not found.'));
        }
    }
}
