<?php

namespace App\Http\Controllers\BusinessAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
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

    // Change theme
    public function changeTheme($id)
    {
        $user = Auth::user();

        // Debugging: Check if business_id exists
        if (is_null($user->business_id)) {
            return back();
        }

        // Update the user's theme
        User::where('user_id', $user->user_id)->update([
            'choosed_theme' => $id
        ]);

        // Redirect to the business admin dashboard with business_id parameter
        return redirect()->route('business-admin.dashboard.index', ['business_id' => $user->business_id]);
    }

    // Account
    public function index($business_id)
    {
        $user = Auth::user();

        // Debugging: Check if business_id exists
        if (is_null($user->business_id)) {
            return back();
        }

        // Check if the user is the business admin
        if ($user->business_id != $business_id) {
            abort(403, 'You are not authorized to access this page');
        }

        return view('business-admin.pages.account.index', compact('user'));
    }

    // Edit account
    public function editAccount($business_id)
    {
        $user = Auth::user();

        // Debugging: Check if business_id exists
        if (is_null($user->business_id)) {
            return back();
        }

        // Check if the user is the business admin
        if ($user->business_id != $business_id) {
            return back();
        }

        return view('business-admin.pages.account.edit', compact('user'));
    }

    // Update account
    public function updateAccount(Request $request)
    {
        // Valuser_idation
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required',
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }

        // Check profile image
        if (isset($request->profile_picture)) {

            // Validation
            $validator = Validator::make($request->all(), [
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:' . (int) env('SIZE_LIMIT', 2048),
            ]);

            // Validation error
            if ($validator->fails()) {
                return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
            }

            // get profile image
            $profile_picture = $request->profile_picture->getClientOriginalName();
            $UploadProfile = pathinfo($profile_picture, PATHINFO_FILENAME);
            $UploadExtension = pathinfo($profile_picture, PATHINFO_EXTENSION);

            // Upload image
            if ($UploadExtension == "jpeg" || $UploadExtension == "png" || $UploadExtension == "jpg" || $UploadExtension == "gif" || $UploadExtension == "svg") {
                // Upload image
                $profile_picture = 'images/business-admin/profile_images/' . $UploadProfile . '_' . uniqid() . '.' . $UploadExtension;
                $request->profile_picture->move(public_path('images/business-admin/profile_images'), $profile_picture);

                // Update user profile image
                User::where('user_id', $user->user_id)->update([
                    'profile_image' => $profile_picture
                ]);
            }

            return redirect()->route('business-admin.index.account', ['business_id' => Auth::user()->business_id])->with('success', trans('Profile Image Updated Successfully!'));
        } else {
            // Update user profile data
            User::where('user_id', $user->user_id)->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            // Get register user data
            $registerUserData = User::where('user_id', $user->user_id)->first();

            if ($request->email != $registerUserData->email) {
                // Check already register count
                $alreadyRegister = User::where('email', $request->email)->count();

                // Check already register
                if ($alreadyRegister <= 0) {
                    // Update user profile data
                    User::where('user_id', $user->user_id)->update([
                        'email' => $request->email,
                        'name' => $request->name,
                    ]);
                    return redirect()->route('business-admin.index.account', ['business_id' => Auth::user()->business_id])->with('success', trans('Profile Updated Successfully!'));
                } else {
                    return redirect()->route('business-admin.edit.account', ['business_id' => Auth::user()->business_id])->with('failed', trans('This email address already registered!'));
                }
            }

            return redirect()->route('business-admin.index.account', ['business_id' => Auth::user()->business_id])->with('success', trans('Profile Updated Successfully!'));
        }
    }

    // Change password
    public function changePassword($business_id)
    {
        return view('business-admin.pages.account.change-password');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        // Validation error
        if ($validator->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
        }


        if ($request->new_password == $request->confirm_password) {
            // Update Password
            User::where('user_id', $user->user_id)->update([
                'password' => bcrypt($request->new_password)
            ]);

            return redirect()->route('business-admin.index.account', ['business_id' => $user->business_id])->with('success', trans('Profile Password Changed Successfully!'));
        } else {
            return redirect()->route('business-admin.change.password', ['business_id' => $user->business_id])->with('failed', trans('Confirm Password Mismatched!'));
        }
    }
}
