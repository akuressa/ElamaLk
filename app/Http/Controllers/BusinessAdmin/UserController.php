<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    // Users
    public function index(Request $request)
    {
        $business_id = $request->business_id;

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $users = User::where('business_id', $business_id)
            ->where('status', '>=', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('business-admin.pages.users.index', compact('users'));
    }

    // Add User
    public function addUser(Request $request, $business_id)
    {

        $business_id = $request->business_id;

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        return view('business-admin.pages.users.add');
    }

    // Save User
    public function saveBusiness(Request $request, $business_id)
    {

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|max:255',
            'user_password' => ['required', Rules\Password::defaults()],
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Check if the email already exists in the users table
        $existingUser = User::where('email', $request->user_email)->first();

        if ($existingUser) {
            // Redirect with an error message if the user already exists
            return redirect()->route('business-admin.add.user', ['business_id' => $business_id])
                ->with('failed', __('The User email is already registered. Please use a different email!'));
        }

        $user = User::create([
            'user_id' => uniqid(),
            'business_id' => $business_id,
            'role' => 3,
            'name' => $request->user_name,
            'email' => $request->user_email,
            'password' => Hash::make($request->user_password),
            'status' => 1,
        ]);

        event(new Registered($user));

        return redirect()->route('business-admin.users.index', ['business_id' => $business_id])->with('success', trans('User Created Successfully!'));
    }

    // Activate User
    public function activateUser(Request $request, $business_id, $user_id)
    {

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Get user details using the user_id from the request
        $user_details = User::where('user_id', $user_id)->first();

        if (!$user_details) {
            return back();
        }

        // Toggle the status of the user
        $status = ($user_details->status == 0) ? 1 : 0;

        // Update the user's status
        User::where('user_id', $user_id)->update(['status' => $status]);

        return redirect()->route('business-admin.users.index', ['business_id' => $business_id])->with('success', trans('User Status Updated Successfully!'));
    }

    // Delete User
    public function deleteUser(Request $request, $business_id, $user_id)
    {

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Get user details using the user_id from the request
        $user_details = User::where('user_id', $user_id)->first();

        if (!$user_details) {
            return back();
        }

        // Toggle the status of the user
        $status = -1;

        // Update the user's status
        User::where('user_id', $user_id)->update(['status' => $status]);

        return redirect()->route('business-admin.users.index', ['business_id' => $business_id])->with('success', trans('User Deleted Successfully!'));
    }

    // Edit User
    public function editUser(Request $request, $business_id, $user_id)
    {

        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $user_details = User::where('user_id', $user_id)->first();

        if (!$user_details) {
            return back();
        }

        return view('business-admin.pages.users.edit', compact('user_details'));
    }

    // Update User
    public function updateUser(Request $request, $business_id, $user_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $user_details = User::where('user_id', $user_id)->first();
        if (!$user_details) {
            return back();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|max:255',
            'user_password' => ['required', Rules\Password::defaults()],
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }       

        User::where('user_id', $user_id)->update([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'password' => Hash::make($request->user_password),
        ]);

        return redirect()->route('business-admin.users.index', ['business_id' => $business_id])->with('success', trans('User Updated Successfully!'));
    }
}
