<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Plan;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
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

    // Business
    public function index()
    {
        $user = Auth::user();

        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }

        $businesses =  Business::leftJoin('business_categories', 'businesses.business_category_id', '=', 'business_categories.business_category_id')
            ->select('businesses.*', 'business_categories.business_category_name')
            ->where('businesses.status', '>=', 0)
            ->get();

        $planDetailsJson = $user->plan_details;

        // Decode the JSON string into an associative array
        $planDetails = json_decode($planDetailsJson, true); // The second parameter `true` converts it to an array

        // Now you can access specific elements from the array
        $plan_name = $planDetails['plan_name'] ?? null;

        $business_categories = BusinessCategory::where('status', '>=', 0)->get();
        $plan = Plan::where('status', 1)->first();
        return view('business.pages.businesses.index', compact('businesses', 'user', 'plan', 'business_categories', 'plan_name'));
    }


    // Add Business
    public function add()
    {
        $user = Auth::user();

        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }


        $business = Business::where('user_id', $user->user_id)->where('status', '>=', 0)->get();
        $business_categories = BusinessCategory::where('status', '>=', 0)->get();

        $count = count($business);

        // Step 1: Decode plan_details
        $planDetails = json_decode($user->plan_details, true); // Decoded as array

        // Step 2: Decode plan_features since it's a nested JSON string
        $planFeatures = is_string($planDetails['plan_features'])
            ? json_decode($planDetails['plan_features'], true)
            : $planDetails['plan_features'];
        // Decode plan_features

        // Step 3: Access the individual elements from plan_features
        $noOfBusinesses = (int) $planFeatures['no_of_businesses'];

        if ($count < $noOfBusinesses) {
            $config = Configuration::get();
            $states = State::where('country_id', $config[34]->config_value)->get();

            return view('business.pages.businesses.add', compact('states', 'business_categories'));
        } else {
            return redirect()->route('business.businesses.index')->with('failed', trans('You have reached the maximum number of businesses allowed by your plan!'));
        }
    }

    // Save Business
    public function saveBusiness(Request $request)
    {

        $user = Auth::user();
        $config = Configuration::get();

        // Validation
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'business_description' => 'required|string',
            'business_category_id' => 'required|string|max:255',
            'business_website_url' => 'required|string|max:255',
            'business_email' => 'required|string',
            'business_phone' => 'required|string|max:15',
            'business_address' => 'required|string|max:255',
            'business_state' => 'required|string|max:255',
            'business_city' => 'required|string|max:255',
            'business_cover_image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
            'business_logo_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Save Business
        $business = new Business();
        $business->business_id = uniqid();
        $business->user_id = $user->user_id;
        $business->business_name = ucfirst($request->business_name);
        $business->business_description = ucfirst($request->business_description);
        $business->business_category_id = $request->business_category_id;

        // Check if a cover image is uploaded
        if ($request->hasFile('business_cover_image_url')) {
            $file = $request->file('business_cover_image_url');
            $originalName = $file->getClientOriginalName();
            $uploadCoverImage = pathinfo($originalName, PATHINFO_FILENAME);
            $uploadExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $uploadPath = 'images/business/cover_images/';
            $newCoverImage = $uploadPath . $uploadCoverImage . '_' . uniqid() . '.' . $uploadExtension;

            $business->business_cover_image_url = $newCoverImage;
            // Move the uploaded file to the destination path
            $file->move(public_path($uploadPath), $newCoverImage);
        }

        // Handle the business logo upload
        if ($request->hasFile('business_logo_url')) {
            $file = $request->file('business_logo_url');
            $originalName = $file->getClientOriginalName();
            $uploadLogoImage = pathinfo($originalName, PATHINFO_FILENAME);
            $uploadExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $uploadPath = 'images/business/logo_images/';
            $newLogoImage = $uploadPath . $uploadLogoImage . '_' . uniqid() . '.' . $uploadExtension;

            $business->business_logo_url = $newLogoImage;
            // Move the uploaded file to the destination path
            $file->move(public_path($uploadPath), $newLogoImage);
        }

        $business->business_website_url = $request->business_website_url;

        // Personal Details
        $business->business_email = $request->business_email;
        $business->business_phone = $request->business_phone;
        $business->business_address = $request->business_address;

        $business->business_country = $config[34]->config_value;
        $business->business_state = $request->business_state;
        $business->business_city = $request->business_city;

        $business->tax_number = $request->business_tax_number;

        $business->status = 1;

        $business->save();

        // After saving, set a session message
        session()->flash('success', trans('New business Created Successfully!'));


        return redirect()->route('business.businesses.index')->with('success', trans('New business Created Successfully!'));
    }

    // Activate Business
    public function activationBusiness(Request $request)
    {
        $user = Auth::user();
        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }
        // Get business details
        $business_details = Business::where('business_id', $request->query('business_id'))->first();

        if ($business_details->status == 0) {
            $user_id = $business_details->user_id;
            $user = User::where('user_id', $user_id)->first();

            $planDetails = json_decode($user->plan_details, true); // Decoded as array

            // Step 2: Decode plan_features since it's a nested JSON string
            $plan_features = is_string($planDetails['plan_features'])
                ? json_decode($planDetails['plan_features'], true)
                : $planDetails['plan_features'];

            // Check if the plan_details array contains the 'no_of_businesses' key
            if (isset($plan_features['no_of_businesses'])) {
                $active_businesses = Business::where('user_id', $business_details->user_id)
                    ->where('status', 1)
                    ->count();

                if ($active_businesses < $plan_features['no_of_businesses']) {
                    $status = ($business_details->status == 0) ? 1 : 0;

                    // Update status
                    Business::where('business_id', $request->query('business_id'))->update(['status' => $status]);
                    return redirect()->route('business.businesses.index')->with('success', trans('Business Status Updated Successfully!'));
                } else {
                    return redirect()->route('business.businesses.index')->with('failed', trans('You have reached the maximum number of businesses allowed by your plan.'));
                }
            }
        } else {
            $status = ($business_details->status == 0) ? 1 : 0;

            // Update status
            Business::where('business_id', $request->query('business_id'))->update(['status' => $status]);
            return redirect()->route('business.businesses.index')->with('success', trans('Business Status Updated Successfully!'));
        }
    }

    // Delete Business
    public function deleteBusiness(Request $request)
    {
        $user = Auth::user();
        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }

        $status = -1;

        // Update status
        Business::where('business_id', $request->query('business_id'))->update(['status' => $status]);
        return redirect()->route('business.businesses.index')->with('success', trans('Business Deleted Successfully!'));
    }

    // Edit Business
    public function editBusiness(Request $request, $business_id)
    {
        $user = Auth::user();
        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }

        $business_details = Business::where('business_id', $business_id)->first();

        if ($business_details == null) {
            return back();
        }

        $business_categories = BusinessCategory::where('status', '>=', 0)->get();
        $countries = Country::all();

        $country_id = $business_details->business_country;
        $state_id = $business_details->business_state;
        $city_id = $business_details->business_city;
        $business_category_id = BusinessCategory::where('business_category_id', $business_details->business_category_id)->first()->business_category_id;

        $default_states = State::where('country_id', $country_id)->get();
        $default_cities = City::where('state_id', $state_id)->get();

        return view('business.pages.businesses.edit', compact('business_details', 'countries', 'country_id', 'state_id', 'city_id', 'default_states', 'default_cities', 'business_categories', 'business_category_id'));
    }

    // Update Business
    public function updateBusiness(Request $request, $business_id)
    {
        $user = Auth::user();
        if (is_null($user->plan_details)) {
            // Redirect to the plans page if plan_details are empty
            return redirect()->route('business.plans.index');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'business_description' => 'required|string',
            'business_cover_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
            'business_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
            'business_website_url' => 'required|string|max:255',
            'business_category_id' => 'required|string|max:255',
            'business_email' => 'required|string',
            'business_phone' => 'required|string|max:15',
            'business_address' => 'required|string|max:255',
            'business_state' => 'required|string|max:255',
            'business_city' => 'required|string|max:255',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Check if the business exists
        $business = Business::where('business_id', $business_id)->first();
        $config = Configuration::get();

        // Update basic fields
        $business->business_name = ucfirst($request->business_name);
        $business->business_description = ucfirst($request->business_description);
        $business->business_website_url = $request->business_website_url;
        $business->business_category_id = $request->business_category_id;
        $business->business_email = $request->business_email;
        $business->business_phone = $request->business_phone;
        $business->business_address = $request->business_address;
        $business->business_country = $config[34]->config_value;
        $business->business_state = $request->business_state;
        $business->business_city = $request->business_city;
        $business->tax_number = $request->business_tax_number;

        // Handle cover image upload
        if ($request->hasFile('business_cover_image_url')) {
            $file = $request->file('business_cover_image_url');
            $originalName = $file->getClientOriginalName();
            $uploadPath = 'images/business/cover_images/';
            $uploadFileName = pathinfo($originalName, PATHINFO_FILENAME);
            $uploadExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newFileName = $uploadPath . $uploadFileName . '_' . uniqid() . '.' . $uploadExtension;

            // Delete the old cover image if it exists
            if (!empty($business->business_cover_image_url) && file_exists(public_path($business->business_cover_image_url))) {
                unlink(public_path($business->business_cover_image_url));
            }

            // Move the uploaded file to the destination path
            $file->move(public_path($uploadPath), $newFileName);
            $business->business_cover_image_url = $newFileName;
        }

        // Handle logo upload
        if ($request->hasFile('business_logo_url')) {
            $file = $request->file('business_logo_url');
            $originalName = $file->getClientOriginalName();
            $uploadPath = 'images/business/logo_images/';
            $uploadFileName = pathinfo($originalName, PATHINFO_FILENAME);
            $uploadExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newFileName = $uploadPath . $uploadFileName . '_' . uniqid() . '.' . $uploadExtension;

            // Delete the old logo if it exists
            if (!empty($business->business_logo_url) && file_exists(public_path($business->business_logo_url))) {
                unlink(public_path($business->business_logo_url));
            }

            // Move the uploaded file to the destination path
            $file->move(public_path($uploadPath), $newFileName);
            $business->business_logo_url = $newFileName;
        }

        $business->save();

        return redirect()->route('business.businesses.index')->with('success', 'Business Details Updated Successfully!');
    }
}
