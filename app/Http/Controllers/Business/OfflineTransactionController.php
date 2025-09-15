<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfflineTransactionController extends Controller
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

    // Offline Transactions
    public function index()
    {
        $user = Auth::user();
        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        } else {
            return view('business.pages.offline_transactions.index');
        }
    }
}
