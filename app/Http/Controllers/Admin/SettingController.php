<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Setting;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\ResponseCache\Facades\ResponseCache;

class SettingController extends Controller
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

    // Settings
    public function index()
    {
        $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $settings = Setting::first();
        $config = Configuration::get();
        $currencies = Currency::get();
        $countries = Country::get();

        $image_limit = [
            'SIZE_LIMIT' => env('SIZE_LIMIT', '')
        ];

        // Get Recaptcha configuration details
        $recaptcha_configuration = [
            'RECAPTCHA_ENABLE' => env('RECAPTCHA_ENABLE', 'off'),
            'RECAPTCHA_SITE_KEY' => env('RECAPTCHA_SITE_KEY', ''),
            'RECAPTCHA_SECRET_KEY' => env('RECAPTCHA_SECRET_KEY', '')
        ];

        // Get google configuration details
        $google_configuration = [
            'GOOGLE_ENABLE' => env('GOOGLE_ENABLE', 'off'),
            'GOOGLE_CLIENT_ID' => env('GOOGLE_CLIENT_ID', ''),
            'GOOGLE_CLIENT_SECRET' => env('GOOGLE_CLIENT_SECRET', ''),
            'GOOGLE_REDIRECT' => env('GOOGLE_REDIRECT', '')
        ];

        // Get email configuration details
        $email_configuration = [
            'driver' => env('MAIL_MAILER', 'smtp'),
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'address' => env('MAIL_FROM_ADDRESS'),
            'name' => env('MAIL_FROM_NAME', $settings->site_name),
        ];


        $settings['image_limit'] = $image_limit;
        $settings['google_configuration'] = $google_configuration;
        $settings['recaptcha_configuration'] = $recaptcha_configuration;
        $settings['email_configuration'] = $email_configuration;

        return view('admin.pages.settings.index', compact('timezonelist', 'settings', 'config', 'currencies', 'countries'));
    }

    // Update General Setting
    public function changeGeneralSettings(Request $request)
    {
        Configuration::where('config_key', 'default_country')->update([
            'config_value' => $request->default_country,
        ]);

        Configuration::where('config_key', 'timezone')->update([
            'config_value' => $request->timezone,
        ]);

        Configuration::where('config_key', 'currency')->update([
            'config_value' => $request->currency,
        ]);

        Configuration::where('config_key', 'term')->update([
            'config_value' => $request->term,
        ]);

        Setting::where('id', '1')->update([
            'tawk_chat_key' => $request->tawk_chat_bot_key
        ]);

        // WhatsApp chatbot
        Configuration::where('config_key', 'show_whatsapp_chatbot')->update([
            'config_value' => $request->show_whatsapp_chatbot,
        ]);

        Configuration::where('config_key', 'whatsapp_chatbot_mobile_number')->update([
            'config_value' => $request->whatsapp_chatbot_mobile_number,
        ]);

        Configuration::where('config_key', 'whatsapp_chatbot_message')->update([
            'config_value' => $request->whatsapp_chatbot_message,
        ]);

        // Set new values using putenv
        $this->updateEnvFile('APP_TIMEZONE', $request->timezone);
        $this->updateEnvFile('COOKIE_CONSENT_ENABLED', $request->cookie);
        $this->updateEnvFile('SIZE_LIMIT', $request->image_limit);

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('General Settings Updated Successfully!'));
    }

    // Update Website Setting
    public function changeWebsiteSettings(Request $request)
    {
        Setting::where('id', '1')->update([
            'site_name' => $request->site_name
        ]);

        Configuration::where('config_key', 'site_name')->update([
            'config_value' => $request->site_name
        ]);

        Configuration::where('config_key', 'app_theme')->update([
            'config_value' => $request->app_theme
        ]);


        // Set new values using putenv
        $this->updateEnvFile('APP_NAME', '"' . $request->site_name . '"');

        // Check website logo
        if (isset($request->site_logo)) {

            // Validation
            $validator = Validator::make($request->all(), [
                'site_logo' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            // Validation error
            if ($validator->fails()) {
                return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
            }

            $site_logo = '/images/web/elements/' . uniqid() . '.' . $request->site_logo->extension();
            $request->site_logo->move(public_path('images/web/elements'), $site_logo);

            // Update details
            Setting::where('id', '1')->update([
                'analytics_id' => $request->google_analytics_id,
                'google_tag' => $request->google_tag,
                'adsense_code' => $request->adsense_code,
                'site_name' => $request->site_name,
                'site_logo' => $site_logo,
                'tawk_chat_key' => $request->tawk_chat_bot_key,
            ]);
        }

        // Check favicon
        if (isset($request->favi_icon)) {
            
            // Validation
            $validator = Validator::make($request->all(), [
                'favi_icon' => 'mimes:jpeg,png,jpg,gif,svg|max:' . env("SIZE_LIMIT") . '',
            ]);

            // Validation error
            if ($validator->fails()) {
                return back()->with('failed', trans('Validation Failed!'))->withErrors($validator)->withInput();
            }

            $favi_icon = '/images/web/elements/' . uniqid() . '.' . $request->favi_icon->extension();
            $request->favi_icon->move(public_path('images/web/elements'), $favi_icon);

            // Update details
            Setting::where('id', '1')->update([
                'analytics_id' => $request->google_analytics_id,
                'google_tag' => $request->google_tag,
                'adsense_code' => $request->adsense_code,
                'site_name' => $request->site_name,
                'favicon' => $favi_icon,
                'tawk_chat_key' => $request->tawk_chat_bot_key,
            ]);
        }

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('Website Settings Updated Successfully!'));
    }

    // Update Payments Setting
    public function changePaymentsSettings(Request $request)
    {
        // Paypal
        Configuration::where('config_key', 'paypal_mode')->update([
            'config_value' => $request->paypal_mode
        ]);

        Configuration::where('config_key', 'paypal_client_id')->update([
            'config_value' => $request->paypal_client_key
        ]);

        Configuration::where('config_key', 'paypal_secret')->update([
            'config_value' => $request->paypal_secret
        ]);

        // Razorpay
        Configuration::where('config_key', 'razorpay_key')->update([
            'config_value' => $request->razorpay_client_key
        ]);

        Configuration::where('config_key', 'razorpay_secret')->update([
            'config_value' => $request->razorpay_secret
        ]);

        // Stripe
        Configuration::where('config_key', 'stripe_publishable_key')->update([
            'config_value' => $request->stripe_publishable_key
        ]);

        Configuration::where('config_key', 'stripe_secret')->update([
            'config_value' => $request->stripe_secret
        ]);

        // Paystack
        Configuration::where('config_key', 'paystack_public_key')->update([
            'config_value' => $request->paystack_public_key
        ]);

        Configuration::where('config_key', 'paystack_secret_key')->update([
            'config_value' => $request->paystack_secret
        ]);

        Configuration::where('config_key', 'merchant_email')->update([
            'config_value' => $request->merchant_email
        ]);

        // Mollie
        Configuration::where('config_key', 'mollie_key')->update([
            'config_value' => $request->mollie_key
        ]);

      

       

        // Offline
        Configuration::where('config_key', 'bank_transfer')->update([
            'config_value' => $request->bank_transfer
        ]);

        // Phonepe
        Configuration::where('config_key', 'merchantId')->update([
            'config_value' => $request->merchantId,
        ]);

        Configuration::where('config_key', 'saltKey')->update([
            'config_value' => $request->saltKey,
        ]);

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('Payment Settings Updated Successfully!'));
    }

    // Update Google Setting
    public function changeGoogleSettings(Request $request)
    {
        Setting::where('id', '1')->update([
            'analytics_id' => $request->google_analytics_id,
            'google_tag' => $request->google_tag,
        ]);

        // Set new values using putenv (google login)
        $this->updateEnvFile('GOOGLE_ENABLE', $request->google_auth_enable);
        $this->updateEnvFile('GOOGLE_CLIENT_ID', '"' . str_replace('"', "'", $request->google_client_id) . '"');
        $this->updateEnvFile('GOOGLE_CLIENT_SECRET', '"' . str_replace('"', "'", $request->google_client_secret) . '"');
        $this->updateEnvFile('GOOGLE_REDIRECT', '"' . str_replace('"', "'", $request->google_redirect) . '"');

        // Set new values using putenv (google recaptcha)
        $this->updateEnvFile('RECAPTCHA_ENABLE', $request->recaptcha_enable);
        $this->updateEnvFile('RECAPTCHA_SITE_KEY', '"' . str_replace('"', "'", $request->recaptcha_site_key) . '"');
        $this->updateEnvFile('RECAPTCHA_SECRET_KEY', '"' . str_replace('"', "'", $request->recaptcha_secret_key) . '"');

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('Google Settings Updated Successfully!'));
    }

    // Update Email Setting
    public function changeEmailSettings(Request $request)
    {
        // Mail username
        $mailDriver = str_replace('"', "", $request->mail_driver);
        $mailDriver = str_replace("'", "", $mailDriver);

        // Mail host
        $mailHost = str_replace('"', "", $request->mail_host);
        $mailHost = str_replace("'", "", $mailHost);

        // Mail port
        $mailPort = str_replace('"', "", $request->mail_port);
        $mailPort = str_replace("'", "", $mailPort);

        // Mail username
        $userName = str_replace('"', "", $request->mail_username);
        $userName = str_replace("'", "", $userName);

        // Mail password
        $password = str_replace('"', "", $request->mail_password);
        $password = str_replace("'", "", $password);

        // Mail password
        $mailEncryption = str_replace('"', "", $request->mail_encryption);
        $mailEncryption = str_replace("'", "", $mailEncryption);

        // Mail email
        $senderEmail = str_replace('"', "", $request->mail_address);
        $senderEmail = str_replace("'", "", $senderEmail);

        // Mail sender name
        $mailSenderName = str_replace('"', "", $request->mail_sender);
        $mailSenderName = str_replace("'", "", $mailSenderName);

        // Set new values using putenv (google login)
        $this->updateEnvFile('MAIL_MAILER', $mailDriver);
        $this->updateEnvFile('MAIL_HOST', $mailHost);
        $this->updateEnvFile('MAIL_PORT', $mailPort);
        $this->updateEnvFile('MAIL_USERNAME', $userName);
        $this->updateEnvFile('MAIL_PASSWORD', $password);
        $this->updateEnvFile('MAIL_ENCRYPTION', $mailEncryption);
        $this->updateEnvFile('MAIL_FROM_ADDRESS', $senderEmail);
        $this->updateEnvFile('MAIL_FROM_NAME', '"' . $mailSenderName . '"');

        // User Email Verification Syetem
        Configuration::where('config_key', 'disable_user_email_verification')->update([
            'config_value' => $request->disable_user_email_verification,
        ]);

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('Email configuration settings updated successfully!'));
    }

    // Tax settings
    public function taxSetting()
    {
        // Queries
        $config = Configuration::get();
        $settings = Setting::first();

        // Page view
        return view('admin.pages.tax.index', compact('config', 'settings'));
    }

    // Update tax setting
    public function updateTaxSetting(Request $request)
    {
        // Update
        Configuration::where('config_key', 'invoice_prefix')->update([
            'config_value' => $request->invoice_prefix,
        ]);

        Configuration::where('config_key', 'invoice_name')->update([
            'config_value' => $request->invoice_name,
        ]);

        Configuration::where('config_key', 'invoice_email')->update([
            'config_value' => $request->invoice_email,
        ]);

        Configuration::where('config_key', 'invoice_phone')->update([
            'config_value' => $request->invoice_phone,
        ]);

        Configuration::where('config_key', 'invoice_address')->update([
            'config_value' => $request->invoice_address,
        ]);

        Configuration::where('config_key', 'invoice_city')->update([
            'config_value' => $request->invoice_city,
        ]);

        Configuration::where('config_key', 'invoice_state')->update([
            'config_value' => $request->invoice_state,
        ]);

        Configuration::where('config_key', 'invoice_zipcode')->update([
            'config_value' => $request->invoice_zipcode,
        ]);

        Configuration::where('config_key', 'invoice_country')->update([
            'config_value' => $request->invoice_country,
        ]);

        Configuration::where('config_key', 'tax_name')->update([
            'config_value' => $request->tax_name,
        ]);

        Configuration::where('config_key', 'tax_number')->update([
            'config_value' => $request->tax_number,
        ]);

        Configuration::where('config_key', 'tax_value')->update([
            'config_value' => $request->tax_value,
        ]);

        Configuration::where('config_key', 'invoice_footer')->update([
            'config_value' => $request->invoice_footer,
        ]);

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('Invoice Setting Updated Successfully!'));
    }

    // Update email setting
    public function updateEmailSetting(Request $request)
    {
        // Update
        Configuration::where('config_key', 'email_heading')->update([
            'config_value' => $request->email_heading,
        ]);

        Configuration::where('config_key', 'email_footer')->update([
            'config_value' => $request->email_footer,
        ]);

        // Page redirect
        return redirect()->route('admin.settings.index')->with('success', trans('Email Setting Updated Successfully!'));
    }

    // Test email
    public function testEmail()
    {
        $message = [
            'msg' => 'Test mail'
        ];
        $mail = false;
        try {
            Mail::to(ENV('MAIL_FROM_ADDRESS'))->send(new \App\Mail\TestMail($message));
            $mail = true;
        } catch (\Exception $e) {

            // Mail send failed
            $mail = false;

            // Page redirect
            return redirect()->route('admin.settings.index')->with('failed', trans('Email configuration wrong.'));
        }
        // Check email
        if ($mail == true) {
            // Page redirect
            return redirect()->route('admin.settings.index')->with('success', trans('Test mail send successfully.'));
        }
    }

    // Change .env file
    public function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');

        // Check if the .env file exists
        if (file_exists($envPath)) {

            // Read the .env file
            $contentArray = file($envPath);

            // Loop through each line to find the key and update its value
            foreach ($contentArray as &$line) {

                // Split the line by '=' to get key and value
                $parts = explode('=', $line, 2);

                // Check if the key matches and update its value
                if (isset($parts[0]) && $parts[0] === $key) {
                    $line = $key . '=' . $value . PHP_EOL;
                }
            }

            // Implode the array back to a string and write it to the .env file
            $newContent = implode('', $contentArray);
            file_put_contents($envPath, $newContent);

            // Reload the environment variables
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
