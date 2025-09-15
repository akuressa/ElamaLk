<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessEmployee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
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

    // Employee
    public function index($business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $business_employees = BusinessEmployee::where('business_id', $business_id)
            ->where('status', '>=', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('business-admin.pages.employees.index', compact('business_employees'));
    }

    // Add Employee
    public function addEmployee(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $business_employees = BusinessEmployee::where('business_id', $business_id)->where('status', '>=', 0)->orderBy('created_at', 'desc')->get();
        $count = count($business_employees);

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
        $noOfEmployees = (int) $planFeatures['no_of_employees'];

        if ($count < $noOfEmployees) {
            return view('business-admin.pages.employees.add');
        } else {
            return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('failed', trans('You have reached the maximum number of services allowed by your plan!'));
        }
    }

    // Save Employee
    public function saveEmployee(Request $request, $business_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'business_employee_name' => 'required',
            'business_employee_email' => 'required|email',
            'business_employee_phone' => 'required|numeric',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        $emp_email = request('business_employee_email');
        $old_employees = BusinessEmployee::where('status', '>=', 0)->pluck('business_employee_email')->toArray();   

        if (in_array($emp_email, $old_employees)) {
            return back()->with('failed', trans('Employee Email already exists!'))->withInput();
        }
        

        $business_employee = new BusinessEmployee();
        $business_employee->business_employee_id = uniqid();
        $business_employee->business_id = $business_id;
        $business_employee->business_employee_name = request('business_employee_name');
        $business_employee->business_employee_email = request('business_employee_email');
        $business_employee->business_employee_phone = request('business_employee_phone');
        $business_employee->is_login = $request->has('is_login') ? 1 : 0;
        $business_employee->status = 1;

        if ($request->has('is_login')) {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255',
                'user_password' => ['required', Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return redirect()->route('business-admin.add.employee', ['business_id' => $business_id])->with('failed', trans('Please fill Credentials if you enables login!'));
            }

            // Check if the email already exists in the users table
            $existingUser = User::where('email', $request->user_email)->first();

            if ($existingUser) {
                // Redirect with an error message if the user already exists
                return redirect()->route('business-admin.add.employee', ['business_id' => $business_id])
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

            $business_employee->user_id = $user->user_id;

            event(new Registered($user));
        }

        $business_employee->save();

        return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('success', __('Employee added successfully!'));
    }

    // Activate Employee
    public function activationEmployee(Request $request, $business_id, $business_employee_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $business_employees = BusinessEmployee::where('business_employee_id', $business_employee_id)->first();

        if ($business_employees->status == 0) {
            $user_id = Business::where('business_id', $business_id)->first()->user_id;
            $user = User::where('user_id', $user_id)->first();

            // Decode the plan_details JSON field
            $planDetails = json_decode($user->plan_details, true); // Decoded as array

            // Step 2: Decode plan_features since it's a nested JSON string
            $plan_features = is_string($planDetails['plan_features'])
                ? json_decode($planDetails['plan_features'], true)
                : $planDetails['plan_features'];


            // Check if the plan_details array contains the 'no_of_employees' key
            if (isset($plan_features['no_of_employees'])) {
                $active_employees = BusinessEmployee::where('business_id', $business_id)
                    ->where('status', 1)
                    ->count();

                if ($active_employees < $plan_features['no_of_employees']) {
                    $status = ($business_employees->status == 0) ? 1 : 0;

                    // Update status
                    BusinessEmployee::where('business_employee_id', $business_employee_id)->update(['status' => $status]);

                    return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('success', trans('Employee Status Updated Successfully!'));
                } else {
                    return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('failed', trans('You have reached the maximum number of employees allowed by your plan!'));
                }
            }
        } else {
            $status = ($business_employees->status == 0) ? 1 : 0;

            // Update status
            BusinessEmployee::where('business_employee_id', $business_employee_id)->update(['status' => $status]);

            return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('success', trans('Employee Status Updated Successfully!'));
        }
    }

    // Delete Employee
    public function deleteEmployee(Request $request, $business_id, $business_employee_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $status = -1;

        // Update status
        BusinessEmployee::where('business_employee_id', $business_employee_id)->update(['status' => $status]);

        $user_id = BusinessEmployee::where('business_employee_id', $business_employee_id)->first()->user_id;

        User::where('user_id', $user_id)->update(['status' => $status]);

        return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('success', trans('Employee Deleted Successfully!'));
    }

    // Edit Employee
    public function editEmployee($business_id, $business_employee_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        $business_employee = BusinessEmployee::where('business_employee_id', $business_employee_id)->first();

        $user = User::where('user_id', $business_employee->user_id)->first();

        return view('business-admin.pages.employees.edit', compact('business_employee', 'user'));
    }

    // Update Employee
    public function updateEmployee(Request $request, $business_id, $business_employee_id)
    {
        // Retrieve all business IDs associated with the authenticated user
        $businessIds = Business::where('user_id', Auth::user()->user_id)->pluck('business_id')->toArray();

        // Check if the business ID is valid
        if (!in_array($business_id, $businessIds) && ($business_id != Auth::user()->business_id)) {
            return back();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'business_employee_name' => 'required',
            'business_employee_email' => 'required|email',
            'business_employee_phone' => 'required|numeric',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        if ($request->has('is_login')) {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255',
                'user_password' => ['required', Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return redirect()->route('business-admin.update.employee', ['business_id' => $business_id])
                    ->with('failed', trans('Please fill in Credentials if you enable login!'));
            }

            // Update Business Employee Information
            BusinessEmployee::where('business_employee_id', $business_employee_id)->update([
                'business_employee_name' => $request->business_employee_name,
                'business_employee_email' => $request->business_employee_email,
                'business_employee_phone' => $request->business_employee_phone,
                'is_login' => $request->has('is_login') ? 1 : 0
            ]);

            // Retrieve the Business Employee record
            $business_employee = BusinessEmployee::where('business_employee_id', $business_employee_id)->first();

            // Check if the user already exists for the business employee
            if ($business_employee->user_id) {
                // If the user exists, update the user's information
                User::where('user_id', $business_employee->user_id)->update([
                    'name' => $request->user_name,
                    'email' => $request->user_email,
                    'password' => Hash::make($request->user_password),
                ]);
            } else {
                // Check if the email already exists in the users table
                $existingUser = User::where('email', $request->user_email)->first();

                if ($existingUser) {
                    // Redirect with an error message if the user already exists
                    return redirect()->route('business-admin.add.employee', ['business_id' => $business_id])
                        ->with('failed', __('The User email is already registered. Please use a different email!'));
                }

                $newUser = User::create([
                    'user_id' => uniqid(),
                    'name' => $request->user_name,
                    'email' => $request->user_email,
                    'password' => Hash::make($request->user_password),
                    'business_id' => $business_id,
                    'role' => 3,
                    'status' => 1,
                ]);

                // Update the Business Employee record to associate it with the new user
                $business_employee->update(['user_id' => $newUser->user_id]);
                event(new Registered($newUser));
            }

            return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])
                ->with('success', trans('Employee Updated Successfully!'));
        } else {
            BusinessEmployee::where('business_employee_id', $business_employee_id)->update([
                'is_login' => $request->has('is_login') ? 1 : 0,
                'business_employee_name' => $request->business_employee_name,
                'business_employee_email' => $request->business_employee_email,
                'business_employee_phone' => $request->business_employee_phone,
            ]);
            return redirect()->route('business-admin.employees.index', ['business_id' => $business_id])->with('success', trans('Employee Updated Successfully!'));
        }
    }
}
