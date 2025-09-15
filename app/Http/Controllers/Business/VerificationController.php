<?php

namespace App\Http\Controllers\Business;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
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

    // Verified email
    public function verifyEmailVerification()
    {
        // Update
        User::where('user_id', Auth::user()->user_id)->update([
            'email_verified_at' => now()
        ]);

        // Page redirect 
        return redirect()->route('business.dashboard.index');
    }

    // Resend Email Verification
    public function resendEmailVerification()
    {
        // Queries
        $user = User::where('user_id', Auth::user()->user_id)->where('status', 1)->first();
        // Send Email
        try {
            Mail::to($user->email)->send(new \App\Mail\SendVerificationEmail($user));
        } catch (\Throwable $th) {
            return redirect()->route('business.dashboard.index')->with('failed', trans('Email service not available.'));
        }

        // Page redirect 
        return redirect()->route('business.dashboard.index')->with('success', trans('Mail Sent.'));
    }
}
