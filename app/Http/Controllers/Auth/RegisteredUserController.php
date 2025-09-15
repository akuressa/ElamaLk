<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

    protected function validator(array $data)
    {
        if (env('RECAPTCHA_ENABLE') == 'on') {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
                'g-recaptcha-response' => ['recaptcha', 'required']
            ]);
        } else {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:5', 'confirmed']
            ]);
        }
    }
    public function create(): View
    {
        // Queries
        $config = Configuration::get();

        $setting = Setting::where('status', 1)->first();

        $recaptcha_configuration = [
            'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', 'off'),
            'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
            'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', ''),
            'RECAPTCHA_SKIP_IP' => env('RECAPTCHA_SKIP_IP', '[]'),
        ];

        $settings['recaptcha_configuration'] = $recaptcha_configuration;

        // Return values
        $returnValues = compact('config', 'setting', 'settings');

        return view('auth.register', $returnValues);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $config = Configuration::all();

        if ($request->type == 'business') {
            $role = 2;
        } elseif ($request->type == 'user') {
            $role = 4;
        } else {
            return redirect()->route('register', ['type' => 'user']);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (env('RECAPTCHA_ENABLE') == 'on') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'g-recaptcha-response' => ['recaptcha', 'required']
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        }

        $user = User::create([
            'user_id' => uniqid(),
            'business_id' => null,
            'role' => $role,
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $config[52]->config_value == '1' ? null : now(),
            'password' => Hash::make($request->password),
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

        if ($user->role == 1) {
            return redirect()->route('admin.dashboard.index');
        } else if ($user->role == 2) {
            return redirect()->route('business.dashboard.index');
        } else if ($user->role == 4) {
            return redirect()->route('user.my-bookings');
        } else {
            return redirect()->route('login');
        }
    }
}
