<?php

namespace App\Http\Controllers;

use App\Models\BusinessPlan;
use App\Models\BusinessPlanSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BusinessPlanSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'business_plan_id' => 'required|exists:business_plans,business_plan_id',
            'business_id' => 'required|exists:businesses,business_id'
        ]);

        $businessPlan = BusinessPlan::where('business_plan_id', $request->business_plan_id)->first();
        
        if (!$businessPlan || !$businessPlan->is_active) {
            return response()->json(['error' => 'Business plan not available'], 400);
        }

        // Check if user already has an active subscription for this business plan
        $existingSubscription = BusinessPlanSubscription::where('user_id', Auth::user()->user_id)
            ->where('business_plan_id', $request->business_plan_id)
            ->where('business_id', $request->business_id)
            ->where('status', 'active')
            ->where('end_date', '>', Carbon::now())
            ->first();

        if ($existingSubscription) {
            return response()->json(['error' => 'You already have an active subscription for this plan'], 400);
        }

        // Create new subscription
        $subscription = BusinessPlanSubscription::create([
            'subscription_id' => uniqid(),
            'user_id' => Auth::user()->user_id,
            'business_id' => $request->business_id,
            'business_plan_id' => $request->business_plan_id,
            'subscription_price' => $businessPlan->plan_price,
            'duration_months' => $businessPlan->duration_months,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths($businessPlan->duration_months),
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to the business plan!',
            'subscription' => $subscription
        ]);
    }

    public function getUserSubscriptions($business_id)
    {
        $subscriptions = BusinessPlanSubscription::with('businessPlan')
            ->where('user_id', Auth::user()->user_id)
            ->where('business_id', $business_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($subscriptions);
    }
}
