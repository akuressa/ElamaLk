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
                            {{ __('Edit Service') }}
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
                        <form
                            action="{{ route('business-admin.update.service', ['business_id' => $business_id, 'business_service_id' => $business_service->business_service_id]) }}"
                            method="post" class="card">
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
                                                        placeholder="{{ __('Service Name') }}"
                                                        value="{{ $business_service->business_service_name }}" required />
                                                </div>
                                            </div>

                                            {{-- Choose Employees --}}
                                            <div class="col-sm-4 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="business_employee_ids">{{ __('Choose Employees') }}</label>
                                                    <select class="tomselected form-select" name="business_employee_ids[]"
                                                        id="business_employee_ids" multiple required>
                                                        <option value="" disabled>-- {{ __('Choose Employee') }} --
                                                        </option>
                                                        @foreach ($all_business_employees as $business_employee)
                                                            <option value="{{ $business_employee->business_employee_id }}"
                                                                {{ in_array($business_employee->business_employee_id, $business_employee_ids) ? 'selected' : '' }}>
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
                                                        placeholder="{{ __('Amount') }}"
                                                        value="{{ $business_service->amount }}" step="0.01" required />
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Description') }}</label>
                                                    <textarea class="form-control text-capitalize" name="business_service_description" rows="3"
                                                        placeholder="{{ __('Description') }}.." required>{{ $business_service->business_service_description }}</textarea>
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
                                                        <option value="" disabled selected>--
                                                            {{ __('Choose Time Duration') }} --
                                                        </option>
                                                        @for ($i = 5; $i <= 60; $i += 5)
                                                            <option value="{{ $i }}"
                                                                {{ $i == $business_service->time_duration ? 'selected' : '' }}>
                                                                {{ $i }} {{ __('minutes') }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Timings --}}
                                            <div class="mb-3 mt-3" id="days-container" style="display: no">
                                                <h2 class="page-title mb-3">{{ __('Days') }}</h2>
                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __($day) }}</div>
                                                        <div class="input-group mb-2">
                                                            <select name="service_slots[{{ strtolower($day) }}][]"
                                                                data-day="{{ strtolower($day) }}"
                                                                class="form-select time-select" multiple></select>
                                                        </div>
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
                                                        {{ __('Update') }}
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
                placeholder: `-- {{ __('Choose Employee') }} --`,
                maxItems: null, // Unlimited selections
                maxOptions: null,
                searchField: 'text', // Field to search by
            });

            const timeDurationSelect = new TomSelect('#time_duration', {
                maxItems: 1, // Single selection
                searchField: 'text',
            });

            const slotDurationInput = document.getElementById('time_duration');
            const timeSelects = document.querySelectorAll('.time-select');

            // Assuming the PHP outputs the JSON directly
            const timeSlotsByDay = {!! $business_service->service_slots !!}; // Use the JSON-encoded variable

            // Initialize TomSelect for each select element and store instances
            const tomSelectInstances = Array.from(timeSelects).map(select => {
                return new TomSelect(select, {
                    copyClassesToDropdown: false,
                    dropdownParent: 'body',
                    controlInput: '<input>',
                    maxOptions: null
                });
            });

            // Generate time slots
            function generateTimeSlots(duration) {
                if (isNaN(duration) || duration <= 0) return;

                tomSelectInstances.forEach(tomSelectInstance => {
                    tomSelectInstance.clearOptions(); // Clear existing options

                    for (let i = 0; i < 24 * 60; i += duration) {
                        const start = String(Math.floor(i / 60)).padStart(2, '0') + ':' + String(i % 60)
                            .padStart(2, '0');
                        const endMinutes = i + duration;

                        // Stop if end time goes beyond 24:00 (1440 minutes)
                        if (endMinutes > 24 * 60) {
                            break; // Stops adding more options once we exceed 24:00
                        }

                        const end = String(Math.floor(endMinutes / 60)).padStart(2, '0') + ':' + String(
                            endMinutes % 60).padStart(2, '0');

                        tomSelectInstance.addOption({
                            value: `${start} - ${end}`,
                            text: `${start} - ${end}`,
                        });
                    }

                    tomSelectInstance.refreshOptions(false);
                });
            }

            generateTimeSlots(Math.min(parseInt(slotDurationInput.value), 60));

            slotDurationInput.addEventListener('change', function() {
                let duration = Math.min(parseInt(this.value), 60);
                tomSelectInstances.forEach(tomSelectInstance => {
                    tomSelectInstance.clear(); // Deselect all values
                });
                generateTimeSlots(duration);
            });

            // Tom Select instances
            tomSelectInstances.forEach(select => {
                const day = select.input.dataset.day.toLowerCase();

                if (day && timeSlotsByDay[day]) {
                    try {
                        const valuesToSelect = (timeSlotsByDay[day]);
                        setTimeout(() => {
                            select.setValue(valuesToSelect); // Set values directly
                        }, 100);
                    } catch (e) {

                    }
                } else {

                }
            });
        });
    </script>
@endsection

@endsection
