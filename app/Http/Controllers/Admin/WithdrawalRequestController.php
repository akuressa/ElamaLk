<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
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

    // Withdrawal Requests
    public function index()
    {
        $withdrawal_requests = PaymentRequest::leftJoin('users', 'users.user_id', '=', 'payment_requests.user_id')
            ->select('payment_requests.*', 'users.name')
            ->orderBy('payment_requests.created_at', 'desc')
            ->get();
        return view('admin.pages.withdrawal-requests.index', compact('withdrawal_requests'));
    }

    // Update withdrawal status
    public function withdrawalStatus(Request $request, $id, $status)
    {
        // Update status
        PaymentRequest::where('payment_request_id', $id)->update([
            'status' => $status
        ]);

        // Page redirect
        return redirect()->back()->with('success', trans('Withdrawal Status Updated Successfully!'));
    }
}
