@extends('user.layouts.app')

{{-- Payments --}}
@php
    $type = $config[13]->config_value;
@endphp

{{-- Custom JS --}}
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}">
@endsection

@section('content')
    {{-- Failed --}}
    @if (Session::has('failed'))
        <div class="flex items-center justify-between p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300"
            role="alert">
            <div class="flex items-center">
                {{ Session::get('failed') }}
            </div>
            <button type="button" class="ml-3 text-red-700 hover:text-red-900"
                onclick="this.parentElement.style.display='none'" aria-label="Close">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9l-5-5m0 0l5 5-5 5m5-5l5-5m-5 5l5 5-5-5z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Success --}}
    @if (Session::has('success'))
        <div class="flex items-center justify-between p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300"
            role="alert">
            <div class="flex items-center">
                {{ Session::get('success') }}
            </div>
            <button type="button" class="ml-3 text-green-700 hover:text-green-900"
                onclick="this.parentElement.style.display='none'" aria-label="Close">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9l-5-5m0 0l5 5-5 5m5-5l5-5m-5 5l5 5-5-5z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    <div>
        <form action="{{ route('user.appointment.book', ['business_id' => $business->business_id]) }}" method="POST"
            enctype="multipart/form-data" class="flex flex-wrap -mx-4">
            @csrf

            {{-- Service --}}
            <div class="mb-6 w-full md:w-1/2 lg:w-1/3 px-4">
                <label class="block mb-2 font-bold text-lg" for="service-select">{{ __('Select Service') }} Test</label>
                <div class="relative">
                    <select
                        class="w-full p-4 text-sm placeholder-gray-500 focus:border-none border border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                        name="business_service_id" id="service-select" onchange="fetchEmployees(this.value)" required>
                        <option value="">{{ __('Select a Service') }}</option>
                        @foreach ($business_services as $business_service)
                            <option value="{{ $business_service->business_service_id }}"
                                data-employees="{{ json_encode($business_service->business_employee_ids) }}">
                                {{ __($business_service->business_service_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Employee --}}
            <div class="mb-6 w-full md:w-1/2 lg:w-1/3 px-4">
                <label class="block mb-2 font-bold text-lg" for="employee-select">{{ __('Select Employee') }}</label>
                <div class="relative">
                    <select
                        class="w-full p-4 text-sm placeholder-gray-500 border focus:border-none border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                        name="business_employee_id" id="employee-select" required>
                        <option value="">{{ __('Select an Employee') }}</option>
                    </select>

                </div>
            </div>

            {{-- Date --}}
            <div class="mb-6 w-full md:w-1/2 lg:w-1/3 px-4">
                <label class="block mb-2 font-bold text-lg" for="date">{{ __('Select Date') }}</label>
                <div class="relative">
                    <input type="text" id="date"
                        class="w-full p-4 text-sm placeholder-gray-500 focus:border-none border border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                        placeholder="{{ __('Select Date') }}" name="date" onchange="fetchSlots(this.value)" required>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M13 5.5L8 10.5L3 5.5" stroke="#A3A3A3" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Time Slots --}}
            <div class="w-full md:w-1/2 lg:w-1/3 px-4">
                <label class="block mb-2 font-bold text-lg" for="time-slot-select">{{ __('Select Time Slot') }}</label>
                <div class="relative">
                    <select
                        class="w-full p-4 text-sm placeholder-gray-500 focus:border-none border border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                        name="time_slot" id="time-slot-select" required>
                        <option value="">{{ __('Select a Time Slot') }}</option>
                    </select>
                </div>
                <p id="errorMsg" class="text-red-500 pt-1 hidden">{{ __('No Slots Available!') }}</p>
            </div>

            {{-- Phone Number --}}
            <div class="w-full md:w-1/2 lg:w-1/3 px-4">
                <label class="block mb-2 font-bold text-lg" for="notes">{{ __('Phone') }}</label>
                <div class="relative">
                    <input type="number" id="phone_number" name="phone_number"
                        class="w-full p-4 text-sm placeholder-gray-500 border focus:border-none border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                        placeholder="{{ __('Phone') }}" required />
                </div>
            </div>

            {{-- Notes --}}
            <div class="w-full md:w-1/2 lg:w-1/3 px-4">
                <label class="block mb-2 font-bold text-lg" for="notes">{{ __('Notes') }}</label>
                <div class="relative">
                    <textarea
                        class="w-full p-4 text-sm placeholder-gray-500 border focus:border-none border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                        name="notes" id="notes" placeholder="{{ __('Notes') }}"></textarea>
                </div>
            </div>

            {{-- Payment Methods --}}
            @if ($type == 1)
                <div class="w-full" id="payment-methods-section">
                    {{-- Payment Methods --}}
                    <h3 class="text-xl font-semibold text-gray-500 py-2 px-5">{{ __('Payment Methods') }}</h3>
                    <div class="py-2 px-5">
                        <div class="w-full">
                            <div class="mb-6">
                                <div class="flex flex-wrap -mx-4">
                                    @foreach ($gateways as $gateway)
                                        <div class="w-full md:w-1/3 px-4 mb-6">
                                            <div class="w-full border border-gray-300 rounded-lg p-4">
                                                <label class="w-full">
                                                    <input type="radio" name="payment_gateway_id"
                                                        value="{{ $gateway->payment_gateway_id }}"
                                                        class="hidden gateway-radio" onchange="setSelectedGateway(this)">
                                                    <div
                                                        class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-100 cursor-pointer">
                                                        <div class="mr-4">
                                                            <span
                                                                id="gateway-{{ $gateway->payment_gateway_id }}-indicator"
                                                                class="block w-6 h-6 border-2 border-gray-300 rounded-full transition-colors duration-300"></span>
                                                        </div>
                                                        <img class="mr-4 w-10 h-10 rounded-full"
                                                            src="{{ asset($gateway->payment_gateway_logo_url) }}"
                                                            alt="Logo">
                                                        <div>
                                                            <div class="font-semibold text-lg">
                                                                {{ __($gateway->payment_gateway_name) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="mb-6 w-full mt-5">
                                <table class="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="py-2 px-4 text-left text-gray-600 font-semibold">
                                                {{ __('Description') }}</th>
                                            <th class="py-2 px-4 text-left text-gray-600 font-semibold">
                                                {{ __('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b border-gray-300">
                                            <td class="py-2 px-4 font-bold"><span id="service-name"></span></td>
                                            <td class="py-2 px-4">
                                                {{ $currency->symbol }} <span id="plan-value" class="font-bold">0</span>
                                            </td>
                                        </tr>
                                        <tr class="border-b border-gray-300">
                                            <td class="py-2 px-4 font-bold">{{ __('Service Charge') }}</td>
                                            <td class="py-2 px-4">
                                                {{ $currency->symbol }} <span id="service-charge-value"
                                                    class="font-bold">0</span>
                                            </td>
                                        </tr>

                                        <tr class="border-b border-gray-300">
                                            <td class="py-2 px-4 font-bold">{{ __('Total') }}</td>
                                            <td class="py-2 px-4">
                                                {{ $currency->symbol }} <span id="total-value" class="font-bold">0</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                {{-- Book Button --}}
                                <div class="flex justify-end mt-4">
                                    <button type="submit" id="book-button-payment"
                                        class="bg-{{ $config[11]->config_value }}-500 text-white font-bold py-3 px-6 rounded-md hover:bg-{{ $config[11]->config_value }}-600 transition">
                                        {{ __('Proceed') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Subscription Message Section (Hidden by default) --}}
                <div class="w-full" id="subscription-message-section" style="display: none;">
                    <div class="p-6">
                        <div class="bg-green-100 text-green-600 p-4 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="text-lg font-semibold">{{ __('No Payment Required') }}</p>
                                    <p class="text-sm" id="subscription-message-text">{{ __('This service is included in your subscription plan. Your appointment will be booked without any payment.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    {{-- Hidden input for payment gateway (required for form submission) --}}
                    <input type="hidden" name="payment_gateway_id" id="payment-gateway-input" value="">
                    
                    {{-- Pricing Table for Subscription Users --}}
                    <div class="mb-6 w-full mt-5">
                        <!-- <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 text-left text-gray-600 font-semibold">
                                        {{ __('Description') }}</th>
                                    <th class="py-2 px-4 text-left text-gray-600 font-semibold">
                                        {{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-300">
                                    <td class="py-2 px-4 font-bold"><span id="service-name-subscription"></span></td>
                                    <td class="py-2 px-4">
                                        {{ $currency->symbol }} <span id="plan-value-subscription" class="font-bold">0</span>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-300">
                                    <td class="py-2 px-4 font-bold">{{ __('Total') }}</td>
                                    <td class="py-2 px-4">
                                        {{ $currency->symbol }} <span id="total-value-subscription" class="font-bold text-green-600">0</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->

                        {{-- Book Button --}}
                        <div class="flex justify-end mt-4">
                            <button type="submit" id="book-button-subscription"
                                class="bg-{{ $config[11]->config_value }}-500 text-white font-bold py-3 px-6 rounded-md hover:bg-{{ $config[11]->config_value }}-600 transition">
                                {{ __('Book Appointment (Free)') }}
                            </button>
                        </div>
                    </div>
                </div>
            @else
                {{-- Payments empty --}}
                <div class="p-6">
                    <div class="bg-red-100 text-red-600 p-4 rounded-lg">
                        <p class="text-lg font-semibold">{{ __('Payment module not available.') }}</p>
                    </div>
                </div>
            @endif
        </form>
    </div>

{{-- Custom JS --}}
@section('custom-js')
    <!-- Flatpickr JS -->
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        // Date Picker
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";

            flatpickr("#date", {
                dateFormat: "Y-m-d",
                minDate: "today"
            });
        });

        // Gateway Selector
        function setSelectedGateway(selectedRadio) {
            "use strict";

            // Reset the color of all indicators
            const indicators = document.querySelectorAll('[id^="gateway-"][id$="-indicator"]');
            indicators.forEach(indicator => {
                indicator.classList.remove('bg-{{ $config[11]->config_value }}-500',
                    'border-{{ $config[11]->config_value }}-500');
                indicator.classList.add('border-gray-300'); // Reset to default color
            });

            // Get the selected gateway's ID and change its color
            const selectedGatewayId = selectedRadio.value;
            const selectedIndicator = document.getElementById(`gateway-${selectedGatewayId}-indicator`);
            selectedIndicator.classList.add('bg-{{ $config[11]->config_value }}-500',
                'border-{{ $config[11]->config_value }}-500');
        }

        // Dropdown Toggle
        function toggleDropdown() {
            "use strict";

            const dropdown = document.getElementById('dropdown-menu');
            dropdown.classList.toggle('hidden');
        }

        // Dropdown Close
        document.addEventListener('click', (event) => {
            "use strict";

            const dropdownButton = document.getElementById('menu-button');
            const dropdown = document.getElementById('dropdown-menu');

            // Close dropdown if clicking outside of it
            if (!dropdownButton.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Employee Selector
        function fetchEmployees(serviceId) {
            "use strict";

            try {
                // Reset employee dropdown
                const employeeSelect = document.getElementById('employee-select');
                if (!employeeSelect) {
                    console.error('Employee select element not found');
                    return;
                }
                
                employeeSelect.innerHTML = '<option value="">Select an Employee</option>';

                // Get service option and employee IDs
                const serviceOption = document.querySelector(`#service-select option[value="${serviceId}"]`);
                if (serviceOption) {
                    const employeeIds = JSON.parse(serviceOption.getAttribute('data-employees'));

                    // Fetch employees based on selected service
                    const employees = @json($business_employees); // Pass all employees to JS

                    if (employeeIds && employeeIds.length > 0) {
                        employeeIds.forEach(employeeId => {
                            const employee = employees.find(emp => emp.business_employee_id === employeeId);

                            if (employee) {
                                const option = document.createElement('option');
                                option.value = employee.business_employee_id;
                                option.textContent = employee.business_employee_name;
                                employeeSelect.appendChild(option);
                            }
                        });
                    } else {
                        console.log('No employees found for service:', serviceId);
                    }
                } else {
                    console.error('Service option not found for serviceId:', serviceId);
                }

                // Update pricing after employee loading
                setAmount(serviceId);
            } catch (error) {
                console.error('Error in fetchEmployees:', error);
            }
        }

        // Time Slot Selector
        function fetchSlots(value) {
            "use strict";

            // check if service and employee is selected
            if (document.getElementById('service-select').value == "" || document.getElementById('employee-select').value ==
                "") {
                alert("Please select a service and employee");
                return false;
            }

            const serviceId = document.getElementById('service-select').value;
            const employeeId = document.getElementById('employee-select').value;

            // Convert the selected date (value) to a Date object
            const date = new Date(value);
            // Array of day names
            const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            // Get the day name using getDay() (returns 0 for Sunday, 1 for Monday, etc.)
            const dayName = dayNames[date.getDay()];


            // Ajax request to fetch states
            $.ajax({
                url: "{{ route('user.fetch.slots') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                dataType: "json",
                data: {
                    date: value,
                    dayName: dayName,
                    serviceId: serviceId,
                    employeeId: employeeId
                },
                success: function(response) {
                    // Get the time slots from the response
                    const timeSlots = response.available_slots;
                    const errorMsg = document.getElementById('errorMsg');
                    errorMsg.classList.add('hidden');

                    const timeSlotSelect = $('#time-slot-select');

                    // Clear existing options
                    timeSlotSelect.empty();
                    timeSlotSelect.append(`<option value="">{{ __('Select a Time Slot') }}</option>`);

                    if (timeSlots && timeSlots.length > 0) {
                        timeSlots.forEach(function(slot) {
                            timeSlotSelect.append('<option value="' + slot + '">' + slot + '</option>');
                        });
                    } else {
                        // Handle case where no slots are available
                        timeSlotSelect.append(`<option value="">{{ __('No Slots Available') }}</option>`);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle 404 error specifically
                    if (xhr.status === 404) {
                        const timeSlotSelect = $('#time-slot-select');
                        const errorMsg = document.getElementById('errorMsg');

                        // Clear existing options
                        timeSlotSelect.empty();
                        timeSlotSelect.append(`<option value="">{{ __('No Slots Available') }}</option>`);

                        // Display error message
                        errorMsg.classList.remove('hidden');
                        errorMsg.classList.add('block');

                    } else {
                        // Handle other errors if necessary
                        console.log('Error:', status, error);
                    }
                }
            });
        };

        // Service Amount Calculator
        function setAmount(serviceId) {
            "use strict";

            try {
                var serviceAmounts = {
                    @foreach ($business_services as $service)
                        "{{ $service->business_service_id }}": {{ $service->amount }},
                    @endforeach
                };

                var serviceNames = {
                    @foreach ($business_services as $service)
                        "{{ $service->business_service_id }}": "{{ __($service->business_service_name) }}",
                    @endforeach
                };

                // Ensure serviceId is defined elsewhere in your code before using it
                var selectedAmount = serviceAmounts[serviceId] || 0;
                var selectedServiceName = serviceNames[serviceId] || "";

                // Payment Gateway Charge
                var serviceCharge = selectedAmount * (10 / 100);

                // Calculate the total amount with tax
                var total = (selectedAmount + serviceCharge).toFixed(2);

                // Check if user has active subscription
                var hasActiveSubscription = {{ $hasActiveSubscription ? 'true' : 'false' }};
                var userSubscription = @json($userSubscription);
                
                // Check if the selected service is included in the subscription plan
                var serviceIncludedInPlan = false;
                if (hasActiveSubscription && userSubscription && userSubscription.business_plan) {
                    var planServiceIds = userSubscription.business_plan.business_service_ids || [];
                    serviceIncludedInPlan = planServiceIds.includes(serviceId);
                }

                // Show/hide sections based on service inclusion
                var paymentMethodsSection = document.getElementById('payment-methods-section');
                var subscriptionMessageSection = document.getElementById('subscription-message-section');
                var paymentGatewayInput = document.getElementById('payment-gateway-input');
                var bookButtonPayment = document.getElementById('book-button-payment');
                var bookButtonSubscription = document.getElementById('book-button-subscription');

                if (hasActiveSubscription && serviceIncludedInPlan) {
                    // Service is included in subscription - hide payment methods, show subscription message
                    if (paymentMethodsSection) paymentMethodsSection.style.display = 'none';
                    if (subscriptionMessageSection) subscriptionMessageSection.style.display = 'block';
                    if (paymentGatewayInput) paymentGatewayInput.value = 'subscription';
                    if (paymentGatewayInput) paymentGatewayInput.name = 'payment_gateway_id'; // Ensure name is set for form submission
                    if (bookButtonSubscription) bookButtonSubscription.textContent = 'Book Appointment (Free)';
                    
                    // Disable radio buttons to prevent conflicts
                    const radioButtons = document.querySelectorAll('input[name="payment_gateway_id"]');
                    radioButtons.forEach(radio => {
                        radio.disabled = true;
                        radio.checked = false;
                    });

                    // Update subscription pricing table (check if elements exist)
                    var serviceNameSub = document.getElementById('service-name-subscription');
                    var planValueSub = document.getElementById('plan-value-subscription');
                    var totalValueSub = document.getElementById('total-value-subscription');
                    
                    if (serviceNameSub) serviceNameSub.innerText = selectedServiceName;
                    if (planValueSub) planValueSub.innerText = '0.00';
                    if (totalValueSub) totalValueSub.innerText = '0.00';
                } else {
                    // Service not included or no subscription - show payment methods, hide subscription message
                    if (paymentMethodsSection) paymentMethodsSection.style.display = 'block';
                    if (subscriptionMessageSection) subscriptionMessageSection.style.display = 'none';
                    if (paymentGatewayInput) paymentGatewayInput.value = '';
                    if (paymentGatewayInput) paymentGatewayInput.name = ''; // Remove name to prevent conflict with radio buttons
                    if (bookButtonPayment) bookButtonPayment.textContent = 'Proceed';
                    
                    // Enable radio buttons for regular payment selection
                    const radioButtons = document.querySelectorAll('input[name="payment_gateway_id"]');
                    radioButtons.forEach(radio => {
                        radio.disabled = false;
                    });

                    // Update regular pricing table (check if elements exist)
                    var serviceName = document.getElementById('service-name');
                    var planValue = document.getElementById('plan-value');
                    var serviceChargeValue = document.getElementById('service-charge-value');
                    var totalValue = document.getElementById('total-value');
                    
                    if (serviceName) serviceName.innerText = selectedServiceName;
                    if (planValue) planValue.innerText = selectedAmount.toFixed(2);
                    if (serviceChargeValue) serviceChargeValue.innerText = serviceCharge.toFixed(2);
                    if (totalValue) totalValue.innerText = total;
                }
            } catch (error) {
                console.error('Error in setAmount:', error);
            }
        }
    </script>
@endsection
@endsection
