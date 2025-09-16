<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\BusinessPlan;
use App\Models\BusinessService;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusinessPlanController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index($business_id)
    {
        // Get the business from route parameter
        $business = Business::where('business_id', $business_id)->first();

        if (!$business) {
            return redirect()->back()->with('failed', 'Business not found');
        }

        // Get business plans for this business
        $businessPlans = BusinessPlan::where('business_id', $business_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('business-admin.pages.business-plans.index', compact('businessPlans', 'business'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($business_id)
    {
        // Get the business from route parameter
        $business = Business::where('business_id', $business_id)->first();

        if (!$business) {
            return redirect()->back()->with('failed', 'Business not found');
        }

        // Get business services for this business
        $businessServices = BusinessService::where('business_id', $business_id)
            ->where('status', 1)
            ->get();

        // Duration options
        $durationOptions = [
            ['value' => 1, 'label' => '1 Month'],
            ['value' => 3, 'label' => '3 Months'],
            ['value' => 6, 'label' => '6 Months'],
            ['value' => 12, 'label' => '1 Year']
        ];

        return view('business-admin.pages.business-plans.create', compact('businessServices', 'durationOptions', 'business'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $business_id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|string|max:255',
            'plan_description' => 'nullable|string',
            'business_service_ids' => 'required|array|min:1',
            'business_service_ids.*' => 'required|string|exists:business_services,business_service_id',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'plan_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', 'Validation failed!')->withErrors($validator)->withInput();
        }

        // Get the business from route parameter
        $business = Business::where('business_id', $business_id)->first();

        if (!$business) {
            return redirect()->back()->with('failed', 'Business not found');
        }

        // Get duration label
        $durationLabels = [
            1 => '1 Month',
            3 => '3 Months',
            6 => '6 Months',
            12 => '1 Year'
        ];

        // Create business plan
        $businessPlan = new BusinessPlan();
        $businessPlan->business_plan_id = uniqid();
        $businessPlan->business_id = $business_id;
        $businessPlan->business_service_ids = $request->business_service_ids;
        $businessPlan->plan_name = $request->plan_name;
        $businessPlan->plan_description = $request->plan_description;
        $businessPlan->plan_price = $request->plan_price;
        $businessPlan->duration_months = $request->duration_months;
        $businessPlan->duration_label = $durationLabels[$request->duration_months];
        $businessPlan->is_active = true;
        $businessPlan->save();

        return redirect()->route('business-admin.business-plans.index', ['business_id' => $business_id])->with('success', 'Business plan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($business_id, string $id)
    {
        $businessPlan = BusinessPlan::where('business_plan_id', $id)
            ->where('business_id', $business_id)
            ->with(['business'])
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('failed', 'Business plan not found');
        }

        return view('business-admin.pages.business-plans.show', compact('businessPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($business_id, string $id)
    {
        $businessPlan = BusinessPlan::where('business_plan_id', $id)
            ->where('business_id', $business_id)
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('failed', 'Business plan not found');
        }

        // Get the business from route parameter
        $business = Business::where('business_id', $business_id)->first();

        if (!$business) {
            return redirect()->back()->with('failed', 'Business not found');
        }

        // Get business services for this business
        $businessServices = BusinessService::where('business_id', $business_id)
            ->where('status', 1)
            ->get();

        // Duration options
        $durationOptions = [
            ['value' => 1, 'label' => '1 Month'],
            ['value' => 3, 'label' => '3 Months'],
            ['value' => 6, 'label' => '6 Months'],
            ['value' => 12, 'label' => '1 Year']
        ];

        return view('business-admin.pages.business-plans.edit', compact('businessPlan', 'businessServices', 'durationOptions', 'business'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $business_id, string $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|string|max:255',
            'plan_description' => 'nullable|string',
            'business_service_ids' => 'required|array|min:1',
            'business_service_ids.*' => 'required|string|exists:business_services,business_service_id',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'plan_price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', 'Validation failed!')->withErrors($validator)->withInput();
        }

        $businessPlan = BusinessPlan::where('business_plan_id', $id)
            ->where('business_id', $business_id)
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('failed', 'Business plan not found');
        }

        // Get duration label
        $durationLabels = [
            1 => '1 Month',
            3 => '3 Months',
            6 => '6 Months',
            12 => '1 Year'
        ];

        // Update business plan
        $businessPlan->business_service_ids = $request->business_service_ids;
        $businessPlan->plan_name = $request->plan_name;
        $businessPlan->plan_description = $request->plan_description;
        $businessPlan->plan_price = $request->plan_price;
        $businessPlan->duration_months = $request->duration_months;
        $businessPlan->duration_label = $durationLabels[$request->duration_months];
        $businessPlan->save();

        return redirect()->route('business-admin.business-plans.index', ['business_id' => $business_id])->with('success', 'Business plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($business_id, string $id)
    {
        $businessPlan = BusinessPlan::where('business_plan_id', $id)
            ->where('business_id', $business_id)
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('failed', 'Business plan not found');
        }

        $businessPlan->delete();

        return redirect()->route('business-admin.business-plans.index', ['business_id' => $business_id])->with('success', 'Business plan deleted successfully!');
    }

    /**
     * Toggle business plan status (activate/deactivate)
     */
    public function toggle($business_id, string $id)
    {
        $businessPlan = BusinessPlan::where('business_plan_id', $id)
            ->where('business_id', $business_id)
            ->first();

        if (!$businessPlan) {
            return redirect()->back()->with('failed', 'Business plan not found');
        }

        $status = request()->get('status', 1);
        $businessPlan->is_active = (bool)$status;
        $businessPlan->save();

        $message = $status ? 'Business plan activated successfully!' : 'Business plan deactivated successfully!';
        return redirect()->route('business-admin.business-plans.index', ['business_id' => $business_id])->with('success', $message);
    }
}
