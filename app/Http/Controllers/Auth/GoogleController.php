<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        // Store the custom parameter in the session
        if (request()->has('type')) {
            session(['register_type' => request()->has('type')]);
        } else {
            session(['register_type' => 'user']);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        // Retrieve the type parameter from the session
        $type = session('register_type');

        // Check registration type
        if ($type == 'business') {
            $role = 2;
        } elseif ($type == 'user') {
            $role = 4;
        } else {
            return redirect()->route('register', ['type' => 'business']);
        }

        // Get type from google 
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // If the user already exists, log them in
                Auth::login($user);
            } else {
                // If the user does not exist, create a new user
                $user = User::create([
                    'user_id' => uniqid(),
                    'business_id' => null,
                    'role' => $role,
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt('default_password'), // You can set a default password or something else
                ]);

                event(new Registered($user));

                // Email Message
                $message = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];

                try {
                    // Send Welcome email
                    Mail::to($user->email)->send(new \App\Mail\WelcomeMail($message));
                    // Send email verification
                    // $user->newEmail($data['email']);
                } catch (Exception $e) {
                }

                Auth::login($user);
            }

            if ($user->role == 1) {
                // Role 1 could be for Admin
                return redirect()->route('admin.dashboard.index');
            } elseif ($user->role == 2) {
                // Role 2 could be for Business
                return redirect()->route('business.dashboard.index');
            } elseif ($user->role == 3) {
                // Role 2 could be for Business
                return redirect()->route('business-admin.dashboard.index', [$user->business_id]);
            } else if ($user->role == 4) {
                return redirect()->route('user.my-bookings');
            } else {
                // Default or fallback redirect if the role is not recognized
                return redirect()->route('/');
            }
        } catch (\Exception $e) {
            return redirect('login')->with('error', 'Something went wrong, please try again!');
        }
    }
}
