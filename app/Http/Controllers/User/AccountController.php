<?php

namespace App\Http\Controllers\User;

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

    // Account
    public function index()
    {
        $user_id = Auth::user()->user_id;
        $user = User::where('user_id', $user_id)->first();
        $config = Configuration::all();
        $setting = Setting::where('status', 1)->first();
        $title = "Account";
        return view('user.pages.account.index', compact('user', 'config', 'setting', 'title'));
    }

    // Edit account
    public function editAccount()
    {
        $user_id = Auth::user()->user_id;
        $user = User::where('user_id', $user_id)->first();
        $config = Configuration::all();
        $setting = Setting::where('status', 1)->first();
        $title = "Edit Account";
        return view('user.pages.account.edit', compact('user', 'config', 'setting', 'title'));
    }

    // Update account
    public function updateAccount(Request $request)
    {
        $user_id = Auth::user()->user_id;
        $user = User::where('user_id', $user_id)->first();

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
                $profile_picture = 'images/user/profile_images/' . $UploadProfile . '_' . uniqid() . '.' . $UploadExtension;
                $request->profile_picture->move(public_path('images/user/profile_images'), $profile_picture);

                // Update user profile image
                User::where('user_id', $user->user_id)->update([
                    'profile_image' => $profile_picture
                ]);
            }

            return redirect()->route('user.account.index')->with('success', trans('Profile Image Updated Successfully!'));
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
                    return redirect()->route('user.account.index')->with('success', trans('Profile Updated Successfully!'));
                } else {
                    return redirect()->route('user.edit.account')->with('failed', trans('This email address already registered!'));
                }
            }

            return redirect()->route('user.account.index')->with('success', trans('Profile Updated Successfully!'));
        }
    }

    // Change password
    public function changePassword()
    {
        $user_id = Auth::user()->user_id;
        $user = User::where('user_id', $user_id)->first();
        $config = Configuration::all();
        $setting = Setting::where('status', 1)->first();
        $title = "Account";
        return view('user.pages.account.change-password', compact('user', 'config', 'setting', 'title'));
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user_id = Auth::user()->user_id;
        $user = User::where('user_id', $user_id)->first();

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

            return redirect()->route('user.account.index')->with('success', trans('Profile Password Changed Successfully!'));
        } else {
            return redirect()->route('user.change.password')->with('failed', trans('Confirm Password Mismatched!'));
        }
    }
}
