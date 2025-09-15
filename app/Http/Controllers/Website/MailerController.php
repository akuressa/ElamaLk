<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailerController extends Controller
{
    // Compose Email
    public function composeEmail(Request $request)
    {
        // Check recaptcha is enabled
        if (env('RECAPTCHA_ENABLE') == 'on') {
            $validated = Validator::make($request->all(), [
                'emailName' => 'required',
                'emailRecipient' => 'required',
                'emailBody' => 'required',
                'g-recaptcha-response' => ['recaptcha', 'required']
            ]);
        } else {
            $validated = Validator::make($request->all(), [
                'emailName' => 'required',
                'emailRecipient' => 'required',
                'emailBody' => 'required'
            ]);
        }

        // Check if validation fails
        if ($validated->fails()) {
            return back()->with('failed', $validated->messages()->all()[0])->withInput();
        }

        try {
            // Contact Details Array
            $contactDetails = [
                'name' => $request->emailName,
                'email' => $request->emailRecipient,
                'message' => $request->emailBody,
            ];

            // Send mail
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactMail($contactDetails));

            return redirect()->route('web.contact')->with('success', trans('Email sent!'));
        } catch (Exception $e) {
            return back()->with("error", trans("Email service not available!"));
        }

        return redirect()->route('web.contact')->with('success', trans('Email sent!'));
    }
}
