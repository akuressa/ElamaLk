<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $plans = Plan::where('status', '>=', 0)->orderBy('created_at', 'desc')->get();
        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();
        $currency = Currency::where('iso_code', $config[1]->config_value)->first();

        return view('admin.pages.plans.index', compact('plans', 'currency', 'settings', 'config'));
    }

    // Add Plan
    public function add()
    {
        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();
        return view('admin.pages.plans.add', compact('settings', 'config'));
    }

    // Save Plan
    public function savePlan(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required',
            'plan_description' => 'required',
            'no_of_businesses' => 'required|integer',
            'no_of_services' => 'required|integer',
            'no_of_employees' => 'required|integer',
            'payment_gateway_charge' => 'required|numeric',
            'no_of_bookings' => 'required|integer',
            'plan_price' => 'required|numeric',
            'plan_validity' => 'required|integer',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Set flags
        $is_trial = $request->has('is_trial') ? 1 : 0;
        $is_recommended = $request->has('is_recommended') ? 1 : 0;
        $is_customer_support = $request->has('is_customer_support') ? 1 : 0;
        $is_private = $request->has('is_private') ? 1 : 0;

        // Save plan
        $plan = new Plan();
        $plan->plan_id = uniqid();
        $plan->plan_name = ucfirst($request->plan_name);
        $plan->plan_description = ucfirst($request->plan_description);

        // Prepare plan features as JSON
        $plan_features = [
            'no_of_businesses' => $request->no_of_businesses,
            'no_of_services' => $request->no_of_services,
            'no_of_employees' => $request->no_of_employees,
            'payment_gateway_charge' => $request->payment_gateway_charge,
            'no_of_bookings' => $request->no_of_bookings,
        ];
        $plan->plan_features = json_encode($plan_features);

        $plan->plan_price = $request->plan_price;
        $plan->plan_validity = $request->plan_validity;
        $plan->is_trial = $is_trial;
        $plan->is_recommended = $is_recommended;
        $plan->is_customer_support = $is_customer_support;
        $plan->is_private = $is_private;
        $plan->save();

        return redirect()->route('admin.plans.index')->with('success', trans('New Plan Created Successfully!'));
    }

    //Edit Plan
    public function editPlan(Request $request, $plan_id)
    {
        // Use the route parameter $plan_id directly without overwriting it
        $plan_details = Plan::where('plan_id', $plan_id)->first();

        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();

        // Check if the plan exists
        if ($plan_details == null) {
            return back();
        } else {
            // If any fields in the plan are stored as JSON, decode them before passing to the view
            if (is_string($plan_details->plan_features)) {  // Replace json_field_name with your actual field name
                $decoded_details = json_decode($plan_details->plan_features, true); // Decoding JSON to array
                $plan_details->plan_features = $decoded_details;
            }

            // Pass the decoded plan details to the view
            return view('admin.pages.plans.edit', compact('plan_details', 'settings', 'config'));
        }
    }

    // Update Plan
    public function updatePlan(Request $request, $plan_id)
    {
         // Validation
         $validator = Validator::make($request->all(), [
            'plan_name' => 'required',
            'plan_description' => 'required',
            'no_of_businesses' => 'required|integer',
            'no_of_services' => 'required|integer',
            'no_of_employees' => 'required|integer',
            'payment_gateway_charge' => 'required|numeric',
            'no_of_bookings' => 'required|integer',
            'plan_price' => 'required|numeric',
            'plan_validity' => 'required|integer',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Set flags
        $is_trial = $request->has('is_trial') ? 1 : 0;
        $is_recommended = $request->has('is_recommended') ? 1 : 0;
        $is_customer_support = $request->has('is_customer_support') ? 1 : 0;
        $is_private = $request->has('is_private') ? 1 : 0;


        // Prepare plan features as JSON
        $plan_features = [
            'no_of_businesses' => $request->no_of_businesses,
            'no_of_services' => $request->no_of_services,
            'no_of_employees' => $request->no_of_employees,
            'payment_gateway_charge' => $request->payment_gateway_charge,
            'no_of_bookings' => $request->no_of_bookings,
        ];

        // Update plan
        Plan::where('plan_id', $plan_id)->update([
            'plan_id' => $plan_id,
            'plan_name' => ucfirst($request->plan_name),
            'plan_description' => ucfirst($request->plan_description),
            'plan_price' => $request->plan_price,
            'plan_validity' => $request->plan_validity,
            'plan_features' => json_encode($plan_features),
            'is_private' => $is_private,
            'is_trial' => $is_trial,
            'is_recommended' => $is_recommended,
            'is_customer_support' => $is_customer_support,
        ]);

        return redirect()->route('admin.plans.index')->with('success', trans('Plan Details Updated Successfully!'));
    }

    // Activate Plan
    public function activationPlan(Request $request)
    {
        // Get plan details
        $plan_details = Plan::where('plan_id', $request->query('plan_id'))->first();

        $status = ($plan_details->status == 0) ? 1 : 0;

        // Update status
        Plan::where('plan_id', $request->query('plan_id'))->update(['status' => $status]);
        return redirect()->route('admin.plans.index')->with('success', trans('Plan Status Updated Successfully!'));
    }

    // Delete Plan
    public function deletePlan(Request $request)
    {
        $status = -1;

        // Update status
        Plan::where('plan_id', $request->query('plan_id'))->update(['status' => $status]);
        return redirect()->route('admin.plans.index')->with('success', trans('Plan Deleted Successfully!'));
    }
}
