<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Setting;
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

    // My account
    public function index()
    {
        $user = Auth::user();
        // Queries
        $account_details = User::where('user_id', $user->user_id)->where('status', 1)->first();


        return view('business.pages.account.index', compact('account_details'));
    }

    // Edit account
    public function editAccount()
    {
        $user = Auth::user();
        // Queries
        $account_details = User::where('user_id', $user->user_id)->where('status', 1)->first();

        return view('business.pages.account.edit', compact('account_details'));
    }

    // Update account
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required'
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
                $profile_picture = 'images/business/profile_images/' . $UploadProfile . '_' . uniqid() . '.' . $UploadExtension;
                $request->profile_picture->move(public_path('images/business/profile_images'), $profile_picture);

                // Update user profile image
                User::where('user_id', $user->user_id)->update([
                    'profile_image' => $profile_picture
                ]);
            }

            return redirect()->route('business.index.account')->with('success', trans('Profile Image Updated Successfully!'));
        } else {
            // Update user profile data
            User::where('user_id', $user->user_id)->update([
                'name' => $request->name
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
                        'email' => $request->email
                    ]);
                    return redirect()->route('business.index.account')->with('success', trans('Profile Email Updated Successfully!'));
                } else {
                    return redirect()->route('business.edit.account')->with('failed', trans('This email address already registered!'));
                }
            }

            return redirect()->route('business.index.account')->with('success', trans('Profile Updated Successfully!'));
        }
    }

    // Change password
    public function changePassword()
    {
        $user = Auth::user();
        // Queries
        $account_details = User::where('user_id', $user->user_id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Configuration::get();

        return view('business.pages.account.change-password', compact('account_details', 'settings', 'config'));
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

            return redirect()->route('business.index.account')->with('success', trans('Profile Password Changed Successfully!'));
        } else {
            return redirect()->route('business.change.password')->with('failed', trans('Confirm Password Mismatched!'));
        }
    }

    // Change theme
    public function changeTheme($id)
    {
        $user = Auth::user();     // Update Password
        User::where('user_id', $user->user_id)->update([
            'choosed_theme' => $id
        ]);

        return redirect()->route('business.dashboard.index');
    }
}
