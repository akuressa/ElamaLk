<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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

    // Users
    public function index()
    {
        return view('admin.pages.users.index');
    }

    // View user
    public function viewUser(Request $request, $id)
    {
        // Get user details
        $user_details = User::where('user_id', $id)->first();

        // Check user
        if ($user_details == null) {
            return back();
        } else {
            $settings = Setting::where('status', 1)->first();

            return view('admin.pages.transactions.view-business', compact('user_details', 'settings'));
        }
    }
}
