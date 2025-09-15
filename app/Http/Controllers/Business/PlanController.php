<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanController extends Controller
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

    // Plans
    public function index()
    {
        // Plans & templates
        $plans = Plan::where('status', 1)->where('is_private', '0')->get();

        // Queries
        $config = Configuration::get();
        $settings = Setting::where('status', 1)->first();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();

        // Current user plan details
        $free_plan = Transaction::where('user_id', Auth::user()->user_id)->where('transaction_total', '0')->count();

        $plan = User::where('user_id', Auth::user()->user_id)->first();
        $active_plan = json_decode($plan->plan_details);

        // Initial remaining days
        $remaining_days = 0;


        // Check plan
        if (isset($active_plan)) {
            $current_date = Carbon::now();

            // Assuming you have the plan start date in your active_plan object
            // If not, replace with the actual field where the plan start date is stored
            $plan_start_date = Carbon::parse($active_plan->plan_start_date);

            $plan_end_date = Carbon::parse($active_plan->plan_end_date);

            // Calculate remaining days until the plan expires
            $remaining_days = round($current_date->diffInDays($plan_end_date, false));
        }

        return view('business.pages.plans.index', compact('plans', 'settings', 'currency', 'active_plan', 'remaining_days', 'config', 'free_plan'));
    }
}
