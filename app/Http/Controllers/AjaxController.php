<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BusinessCategory;
use App\Models\BusinessEmployee;
use App\Models\BusinessService;
use App\Models\City;
use App\Models\Configuration;
use App\Models\Currency;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    // Get states by country code
    public function states(Request $request)
    {
        $country_id = $request->country_id;
        $states = State::where('country_id', $country_id)->get();
        return response()->json($states);
    }

    // Get cities by state id
    public function cities(Request $request)
    {
        $state_id = $request->state_id;

        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }

    // Get Slots
    public function slots(Request $request)
    {
        // Extract request parameters
        $date = $request->date; // Selected date
        $day = strtolower($request->dayName); // Day name (e.g., 'monday', 'tuesday', etc.)
        $business_service_id = $request->serviceId; // Service ID
        $business_employee_id = $request->employeeId; // Employee ID

        // Get the service details
        $business_service = BusinessService::where('business_service_id', $business_service_id)->first();

        // Check if service exists
        if (!$business_service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        // Decode the service_slots JSON
        $business_service_slots = json_decode($business_service->service_slots, true);

        // Check if slots for the given day exist
        if (!isset($business_service_slots[$day])) {
            return response()->json(['error' => 'No slots available for this day'], 404);
        }

        // Fetch the slots for the requested day
        $available_slots = $business_service_slots[$day];

        // Determine if the selected date is today
        $is_today = Carbon::parse($date)->isToday();

        // Get the current time in 'H:i' format
        $current_time_str = Carbon::now()->format('H:i');

        // Filter available slots to remove times before the current time if the selected date is today
        $filtered_slots = $is_today ? array_filter($available_slots, function ($slot) use ($current_time_str) {
            $slot_parts = explode(' - ', $slot); // Split slot into start and end times
            return $slot_parts[0] >= $current_time_str; // Keep only slots starting after the current time
        }) : $available_slots;

        // Fetch booked slots for the specific date, service, and employee
        $booked_slots = Booking::where('business_service_id', $business_service_id)
            ->where('business_employee_id', $business_employee_id)
            ->where('booking_date', $date)
            ->where('status', 1)
            ->pluck('booking_time')
            ->toArray();

        // Calculate available slots by removing booked slots from filtered slots
        $remaining_slots = array_diff($filtered_slots, $booked_slots);

        // Return the remaining available slots
        return response()->json([
            'date' => $date,
            'day' => $day,
            'available_slots' => array_values($remaining_slots)
        ]);
    }

    // Filter businesses
    public function filterBusinesses(Request $request)
    {
        // Get request parameters
        $business_category_slug = $request->query('business_category_slug');
        $city_id = $request->query('city_id');

        // Get business category
        $business_category = BusinessCategory::where('business_category_slug', $business_category_slug)->first();

        // Check if business category exists
        if (!$business_category) {
            return response()->json(['businesses' => []]);
        }

        // Get businesses
        $businesses = DB::table('businesses')
            ->leftJoin('business_services', 'businesses.business_id', '=', 'business_services.business_id')
            ->leftJoin('business_employees', 'businesses.business_id', '=', 'business_employees.business_id')
            ->leftJoin('states', 'businesses.business_state', '=', 'states.id')
            ->leftJoin('cities', 'businesses.business_city', '=', 'cities.id')
            ->select(
                'businesses.*',
                DB::raw('COUNT(DISTINCT business_services.id) as service_count'),
                DB::raw('COUNT(DISTINCT business_employees.id) as employee_count'),
                'states.name as state',
                'cities.name as city',
            )
            ->where('businesses.business_category_id', $business_category->business_category_id)
            ->where('businesses.business_city', $city_id)
            ->where('businesses.status', 1)
            ->groupBy(
                'businesses.id',
                'businesses.user_id',
                'businesses.business_id',
                'businesses.business_name',
                'businesses.business_description',
                'businesses.business_category_id',
                'businesses.business_cover_image_url',
                'businesses.business_logo_url',
                'businesses.business_website_url',
                'businesses.business_email',
                'businesses.business_phone',
                'businesses.business_address',
                'businesses.business_country',
                'businesses.business_state',
                'businesses.business_city',
                'businesses.tax_number',
                'businesses.status',
                'businesses.offline_transaction_details',
                'businesses.created_at',
                'businesses.updated_at',
                'states.name',
                'cities.name'
            )
            ->having('service_count', '>=', 1)
            ->having('employee_count', '>=', 1)
            ->get();

        return response()->json(['businesses' => $businesses]);
    }

    public function bookingDetails(Request $request)
    {
        $booking = Booking::where('booking_id', $request->booking_id)->first();
        $currency = Configuration::where('config_key', 'currency')->first();

        if ($booking) {
            return response()->json([
                'customer' => User::where('user_id', $booking->user_id)->first()->name,
                'service' => BusinessService::where('business_service_id', $booking->business_service_id)->first()->business_service_name,
                'employee' => BusinessEmployee::where('business_employee_id', $booking->business_employee_id)->first()->business_employee_name,
                'date' => $booking->booking_date,
                'time' => $booking->booking_time,
                'phone' => $booking->phone_number,
                'price' => $booking->total_price,
                'currency' => $currency->config_value,
            ]);
        } else {
            return response()->json(['error' => 'Booking not found'], 404);
        }
    }
}
