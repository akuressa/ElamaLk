@extends('business-admin.layouts.app')

@php
    $business_id = request()->route()->parameter('business_id');
@endphp

@section('content')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Add Service') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-deck row-cards">
                    {{-- Save Business --}}
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('business-admin.save.service', ['business_id' => $business_id]) }}"
                            method="post" class="card" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Service Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            {{-- Service Name --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Service Name') }}</label>
                                                    <input type="text" class="form-control" name="business_service_name"
                                                        placeholder="{{ __('Service Name') }}" required />
                                                </div>
                                            </div>

                                            {{-- Choose Employees --}}
                                            <div class="col-sm-4 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="business_employee_ids">{{ __('Choose Employees') }}</label>
                                                    <select class="tomselected form-select" name="business_employee_ids[]"
                                                        id="business_employee_ids" required multiple >
                                                        <option value="" disabled>-- {{ __('Choose Employee') }} --</option>
                                                        @foreach ($business_employees as $business_employee)
                                                            <option value="{{ $business_employee->business_employee_id }}">
                                                                {{ $business_employee->business_employee_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Service Amount --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Amount') }}</label>
                                                    <input type="number" class="form-control" name="amount"
                                                        placeholder="{{ __('Amount') }}" required />
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Description') }}</label>
                                                    <textarea class="form-control text-capitalize" name="business_service_description" rows="3"
                                                        placeholder="{{ __('Description') }}.." required></textarea>
                                                </div>
                                            </div>

                                            {{-- Time Slots --}}
                                            <h2 class="page-title my-3">{{ __('Time Slots') }}</h2>

                                            {{-- Time Duration --}}
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Time Duration') }}</label>
                                                    <select class="tomselected form-select" name="time_duration"
                                                        id="time_duration" required>
                                                        <option value="" disabled selected>-- {{ __('Choose Time Duration') }} --
                                                        </option>
                                                        @for ($i = 5; $i <= 60; $i += 5)
                                                            <option value="{{ $i }}">{{ $i }} {{ __('minutes') }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Timings --}}
                                            <div class="mb-3 mt-3" id="days-container" style="display: none;">
                                                <h2 class="page-title">{{ __('Days') }}</h2>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <div class="mb-3 mt-2">
                                                        <label class="form-label">{{ __($day) }}</label>
                                                        <select class="form-select"
                                                            name="service_slots[{{ strtolower($day) }}][]"
                                                            id="service_slots_{{ strtolower($day) }}" multiple>
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="text-end">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-plus" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <line x1="12" y1="5" x2="12"
                                                                y2="19"></line>
                                                            <line x1="5" y1="12" x2="19"
                                                                y2="12"></line>
                                                        </svg>
                                                        {{ __('Add') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('business-admin.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        // Employee Select
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";

            const employeeSelect = new TomSelect('#business_employee_ids', {
                maxItems: null,
                maxOptions: null,
                searchField: 'text',
            });

            let timeDurationSelect = new TomSelect('#time_duration', {
                maxItems: 1, 
                searchField: 'text',
                onChange: function(value) {
                    updateTimeSlots(value); // Update time slots based on selected duration
                    toggleFields(value !== ''); // Show/Hide fields based on selection
                }
            });

            // Update time slots
            function updateTimeSlots(selectedDuration) {
                const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                days.forEach(day => {
                    const timeSlotsDropdown = document.getElementById(`service_slots_${day}`);
                    if (timeSlotsDropdown.tomselect) {
                        timeSlotsDropdown.tomselect.destroy(); // Destroy existing TomSelect instance
                    }

                    timeSlotsDropdown.innerHTML = ''; // Clear previous options

                    if (selectedDuration) {
                        const duration = Number(selectedDuration); // Convert to number
                        let currentTime = 0; // Current time in minutes

                        while (currentTime < 1440) { // 1440 minutes in a day
                            const startHours = Math.floor(currentTime / 60);
                            const startMinutes = currentTime % 60;
                            const endMinutes = currentTime + duration;

                            if (endMinutes > 1440) break; // Stop if the end time exceeds 24 hours

                            const endHours = Math.floor(endMinutes / 60);
                            const endMinutesMod = endMinutes % 60;

                            const startHour = String(startHours).padStart(2, '0');
                            const startMinute = String(startMinutes).padStart(2, '0');
                            const endHour = String(endHours).padStart(2, '0');
                            const endMinute = String(endMinutesMod).padStart(2, '0');

                            const optionValue = `${startHour}:${startMinute} - ${endHour}:${endMinute}`;
                            const option = document.createElement('option');
                            option.value = optionValue;
                            option.textContent = optionValue;
                            timeSlotsDropdown.appendChild(option);

                            currentTime += duration; // Move to the next time slot
                        }

                        // Reinitialize TomSelect after populating new options
                        new TomSelect(timeSlotsDropdown, {maxOptions: null});
                    }
                });
            }

            // Toggle fields
            function toggleFields(show) {
                const daysContainer = document.getElementById('days-container');
                if (show) {
                    daysContainer.style.display = 'block';
                } else {
                    daysContainer.style.display = 'none';
                }
            }
        });
    </script>
@endsection
@endsection
