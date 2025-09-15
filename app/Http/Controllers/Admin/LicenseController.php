<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as serverReq;

class LicenseController extends Controller
{
    //  License
    public function license()
    {
        // Queries
        $config = Configuration::get();

        return view('admin.pages.license.index', compact('config'));
    }

    // Verify License
    public function verifyLicense(Request $request)
    {
        // Default message
        $resp_data = [];
        $errorMessage = "Something went wrong! Please contact author support team.";
        $server_name = serverReq::server("SERVER_NAME");

        // License Validator
        $client = new \GuzzleHttp\Client();
        $res = $client->post('https://verify.nativecode.in/bookin/validate', [
            'form_params' => [
                'purchase_code' => $request->purchase_code,
                'server_name' => $server_name
            ]
        ]);

        // Return response
        $resp_data = json_decode($res->getBody(), true);

        if ($resp_data) {
            // Check status is "TRUE"
            if ($resp_data['status'] == true) {
                // Update
                Configuration::where('config_key', 'app_settings')->update([
                    'config_value' => $resp_data['resp'],
                ]);

                // Update License
                Configuration::where('config_key', 'purchase_code')->update([
                    'config_value' => $request->purchase_code,
                ]);

                // Success message and redirect
                return redirect()->route('admin.license')->with('success', $resp_data['message'] . ' Purchase code verified successfully.');
            } else {
                // Failed message and redirect
                return redirect()->route('admin.license')->with('failed', $resp_data['message']);
            }
        } else {
            // Failed message and redirect
            return redirect()->route('admin.license')->with('failed', $errorMessage);
        }

        // Failed message and redirect
        return redirect()->route('admin.license')->with('success', trans('Purchase code verified failed.'));
    }
}
