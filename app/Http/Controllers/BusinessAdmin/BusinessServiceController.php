<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\Configuration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BusinessServiceController extends Controller
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

    // Business Service
    public function index(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $config = Configuration::get();
        $currency = $config[1]->config_value;

        $business_services = BusinessService::where('business_id', $business_id)->where('status', '>=', 0)->orderBy('created_at', 'desc')->get();

        return view('business-admin.pages.services.index', compact('business_services', 'currency'));
    }

    // Add Service
    public function addService(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $business_services = BusinessService::where('business_id', $business_id)->where('status', '>=', 0)->get();
        $count = count($business_services);

        $business_user_id = Business::where('business_id', $business_id)->first()->user_id;

        $user = User::where('user_id', $business_user_id)->first();

        // Step 1: Decode plan_details
        $planDetails = json_decode($user->plan_details, true); // Decoded as array

        // Step 2: Decode plan_features since it's a nested JSON string
        $planFeatures = is_string($planDetails['plan_features'])
            ? json_decode($planDetails['plan_features'], true)
            : $planDetails['plan_features'];
        // Decode plan_features

        // Step 3: Access the individual elements from plan_features
        $noOfServices = (int) $planFeatures['no_of_services'];

        if ($count < $noOfServices) {
            $business_employees = BusinessEmployee::where('business_id', $business_id)->where('status', '>=', 0)->get();
            return view('business-admin.pages.services.add', compact('business_employees'));
        } else {
            return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('failed', trans('You have reached the maximum number of services allowed by your plan!'));
        }
    }

    // Save Service
    public function saveService(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'business_service_name' => 'required|string|max:255',
            'business_service_description' => 'required|string',
            'business_employee_ids' => 'required|array',
            'time_duration' => 'required|integer',
            'service_slots' => 'required|array',
            'amount' => 'required'
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $serviceName = request('business_service_name');
        $old_serviceNames = BusinessService::where('business_id', '==', $business_id)->pluck('business_service_name')->toArray();

        if (in_array($serviceName, $old_serviceNames)) {
            return back()->with('failed', trans('This Service already exists!'))->withInput();
        }

        $business_service = new BusinessService();
        $business_service->business_id = $business_id;
        $business_service->business_service_id = uniqid();
        $business_service->business_category_id = Business::where('business_id', $business_id)->first()->business_category_id;
        $business_service->business_service_name = $request->business_service_name;
        $business_service->business_service_slug = Str::slug($request->business_service_name);
        $business_service->business_service_description = $request->business_service_description;
        $business_service->time_duration = $request->time_duration;
        $business_service->service_slots = json_encode($request->service_slots);
        $business_service->business_employee_ids = json_encode($request->business_employee_ids);
        $business_service->amount = $request->amount;
        $business_service->status = 1;

        $business_service->save();

        return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('success', trans('Service Created Successfully!'));
    }

    // Edit Service
    public function editService($business_id, $business_service_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Retrieve the business service
        $business_service = BusinessService::where('business_service_id', $business_service_id)->firstOrFail();

        // Decode the JSON array of employee IDs
        $business_employee_ids = json_decode($business_service->business_employee_ids, true); // true for associative array

        // Fetch all business employees for the dropdown
        $all_business_employees = BusinessEmployee::where('business_id', $business_id)->where('status', '>=', 0)->get();

        $old_slots = json_decode($business_service->service_slots, true);

        return view('business-admin.pages.services.edit', compact('business_service', 'business_employee_ids', 'all_business_employees', 'old_slots'));
    }

    // Update Service
    public function updateService(Request $request, $business_id, $business_service_id)
    {
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }
       
        // Validation
        $validator = Validator::make($request->all(), [
            'business_service_name' => 'required|string|max:255',
            'business_service_description' => 'required|string',
            'business_employee_ids' => 'required|array',
            'time_duration' => 'required|integer',
            'service_slots' => 'required|array',
            'amount' => 'required'
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        BusinessService::where('business_service_id', $business_service_id)->update([
            'business_service_name' => $request->business_service_name,
            'business_service_description' => $request->business_service_description,
            'business_employee_ids' => json_encode($request->business_employee_ids),
            'time_duration' => $request->time_duration,
            'service_slots' => json_encode($request->service_slots),
            'amount' => $request->amount
        ]);

        return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('success', trans('Service Updated Successfully!'));
    }

    // Activate Service
    public function activationService($business_id, $business_service_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Get service details
        $business_service_details = BusinessService::where('business_service_id', $business_service_id)->first();

        if ($business_service_details->status == 0) {
            $user_id = Business::where('business_id', $business_id)->first()->user_id;
            $user = User::where('user_id', $user_id)->first();

            // Decode the plan_details JSON field
            $planDetails = json_decode($user->plan_details, true); // Decoded as array

            // Step 2: Decode plan_features since it's a nested JSON string
            $plan_features = is_string($planDetails['plan_features'])
                ? json_decode($planDetails['plan_features'], true)
                : $planDetails['plan_features'];

            // Check if the plan_details array contains the 'no_of_businesses' key
            if (isset($plan_features['no_of_services'])) {
                $active_services = BusinessService::where('business_id', $business_id)
                    ->where('status', 1)
                    ->count();

                if ($active_services < $plan_features['no_of_services']) {
                    $status = ($business_service_details->status == 0) ? 1 : 0;

                    // Update status
                    BusinessService::where('business_service_id', $business_service_id)->update(['status' => $status]);

                    return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('success', trans('Service Status Updated Successfully!'));
                } else {
                    return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('failed', trans('You have reached the maximum number of services allowed by your plan!'));
                }
            }
        } else {
            $status = ($business_service_details->status == 0) ? 1 : 0;

            // Update status
            BusinessService::where('business_service_id', $business_service_id)->update(['status' => $status]);

            return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('success', trans('Service Status Updated Successfully!'));
        }
    }

    // Delete Service
    public function deleteService($business_id, $business_service_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $status = -1;

        // Update status
        BusinessService::where('business_service_id', $business_service_id)->update(['status' => $status]);

        return redirect()->route('business-admin.services.index', ['business_id' => $business_id])->with('success', trans('Service Deleted Successfully!'));
    }
}
