<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {

        // Validation
        $validatedData = Validator::make($request->all(), [
            'user_id' => 'required|unique:users',
            'choosed_theme' => 'required|string|max:255',
            'business_id' => 'nullable|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            'role' => ['required', Rule::in(['1', '2', '3', '4'])],
            'profile_image' => 'nullable|string|max:255',
            'status' => 'nullable|numeric',
        ]);

        // Validation error
        if ($validatedData->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validatedData)->withInput();
        }

        // Create user
        $user = User::create([
            'user_id' => $validatedData['user_id'],
            'business_id' => $validatedData['business_id'] ?? null,
            'choosed_theme' => $validatedData['choosed_theme'] ?? 'light',
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone' => $validatedData['phone'],
            'role' => $validatedData['role'],
            'profile_image' => $validatedData['profile_image'],
            'status' => $validatedData['status'] ?? 1,
        ]);

        return response()->json(['message' => 'User created successfully!', 'user' => $user], 201);
    }

    /**
     * Update an existing user in the database.
     */
    public function update(Request $request, User $user)
    {
        // Validation
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'profile_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Validation error
        if ($validatedData->fails()) {
            return back()->with('failed', trans('Validation Failed!'))->withErrors($validatedData)->withInput();
        }

        // Update user
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'profile_image' => $validatedData['profile_image'],
            'is_active' => $validatedData['is_active'] ?? $user->is_active,
        ]);

        return response()->json(['message' => 'User updated successfully!', 'user' => $user]);
    }
}
