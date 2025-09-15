<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Switch Account
    public function switchAccount(Request $request)
    {
        $user_id = $request->user_id;
        // Log out the current admin user
        Auth::logout();

        // Fetch the user associated with the selected business
        $user = User::where('user_id', $user_id)->first();

        // Check if the business user exists
        if ($user) {
            // Log in the business user
            Auth::login($user);

            if ($user->role == 2) {
                return redirect()->route('business.dashboard.index');
            } elseif ($user->role == 4) {
                return redirect()->route('user.my-bookings');
            } else {
                return redirect()->route('business-admin.dashboard.index', ['business_id' => $user->business_id]);
            }
            // Redirect to the business dashboard or the desired page

        } else {
            // Handle the error if the business user does not exist
            return redirect()->route('admin.dashboard.index')->with('error', trans('User not found.'));
        }
    }

    // Business
    public function index()
    {
        $businesses = Business::all();

        $businesses = Business::leftJoin('business_categories', 'businesses.business_category_id', '=', 'business_categories.business_category_id')
            ->where('businesses.status', '>=', 0)
            ->orderBy('businesses.created_at', 'desc')
            ->select('businesses.*', 'business_category_name')
            ->get();

        return view('admin.pages.businesses.index', compact('businesses'));
    }

    // Activate Business
    public function activationBusiness(Request $request)
    {
        // Get plan details
        $businesses = Business::where('business_id', $request->query('business_id'))->first();
        $status = ($businesses->status == 0) ? 1 : 0;

        // Update status
        Business::where('business_id', $request->query('business_id'))->update(['status' => $status]);
        return redirect()->route('admin.businesses.index')->with('success', trans('Business Status Updated Successfully!'));
    }

    // Delete Business
    public function deleteBusiness(Request $request)
    {
        $status = -1;

        User::where('business_id', $request->query('business_id'))->update(['status' => $status]);
        // Update status
        Business::where('business_id', $request->query('business_id'))->update(['status' => $status]);
        return redirect()->route('admin.businesses.index')->with('success', trans('Business Deleted Successfully!'));
    }

    // Business Index
    public function businessIndex($business_id)
    {
        $business = Business::where('business_id', $business_id)->first();

        if (!$business) {
            return back();
        }

        $service_count = BusinessService::where('business_id', $business_id)->count();
        $employee_count = BusinessEmployee::where('business_id', $business_id)->count();

        $business_services = BusinessService::leftJoin('business_categories', 'business_categories.business_category_id', '=', 'business_services.business_category_id')
            ->where('business_services.status', '>=', 0)
            ->orderBy('business_services.created_at', 'desc')
            ->select('business_services.*', 'business_category_name')
            ->get();

        $users = User::where('business_id', $business_id)->where('status', '>=', 0)->get();

        $master_business_id = $business->user_id;

        $master_business_user = User::where('user_id', $master_business_id)->first();

        return view('admin.pages.businesses.business.index', compact('business', 'service_count', 'employee_count', 'business_services', 'users', 'master_business_user'));
    }

    // Activate Business Service
    public function activationBusinessService(Request $request)
    {
        $business_service_id = $request->business_service_id;
        // Find the business service by its ID
        $business_service = BusinessService::where('business_service_id', $business_service_id)->first();

        // Toggle the status of the business service
        $status = $business_service->status == 0 ? 1 : 0;
        BusinessService::where('business_service_id', $business_service_id)->update([
            'status' => $status
        ]);

        // Get the business_id associated with the service
        $business_id = $business_service->business_id;

        // Redirect back to the business index page for the specific business
        return redirect()->route('admin.business.index', ['business_id' => $business_id])->with('success', trans('Business Service Status Updated Successfully!'));;
    }

    // Delete Business Service
    public function deleteBusinessService(Request $request)
    {
        $business_service_id = $request->business_service_id;
        // Find the business service by its ID
        $business_service = BusinessService::where('business_service_id', $business_service_id)->first();

        // Toggle the status of the business service
        $status = -1;
        BusinessService::where('business_service_id', $business_service_id)->update([
            'status' => $status
        ]);

        // Get the business_id associated with the service
        $business_id = $business_service->business_id;

        // Redirect back to the business index page for the specific business
        return redirect()->route('admin.business.index', ['business_id' => $business_id])->with('success', trans('Business Service Deleted Successfully!'));;
    }

    // Activate Business User
    public function activationBusinessUser(Request $request)
    {
        $user_id = $request->user_id;
        // Find the user by its ID
        $user = User::where('user_id', $user_id)->first();

        // Toggle the status of the user
        $status = ($user->status == 0) ? 1 : 0;
        User::where('user_id', $user_id)->update(['status' => $status]);

        // Get the business_id associated with the user
        $business_id = $user->business_id;

        // Redirect back to the business index page for the specific business
        return redirect()->route('admin.business.index', ['business_id' => $business_id])->with('success', trans('Business User Status Updated Successfully!'));;
    }

    // Delete Business User
    public function deleteBusinessUser(Request $request)
    {
        $user_id = $request->user_id;
        // Find the user by its ID
        $user = User::where('user_id', $user_id)->first();

        // Toggle the status of the user
        $status = -1;
        User::where('user_id', $user_id)->update(['status' => $status]);

        // Get the business_id associated with the user
        $business_id = $user->business_id;

        // Redirect back to the business index page for the specific business
        return redirect()->route('admin.business.index', ['business_id' => $business_id])->with('success', trans('Business User Deleted Successfully!'));;
    }
}
