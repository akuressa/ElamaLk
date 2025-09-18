@extends('layouts.classic')

@section('custom-css')
    <style>
        .img-class {
            height: 30rem;
        }
    </style>
@endsection

@section('content')
    <div>
        {{-- Banner Section --}}
        <section class="overflow-hidden">
            <div class="container px-4 mx-auto">
                <div class="flex flex-wrap">
                    <div class="w-full img-class">
                        <img src="{{ asset($business->business_cover_image_url) }}" class="w-full h-full object-cover rounded-2xl"
                            alt="Business Cover Image">
                    </div>
                    <div class="flex my-5 flex-col md:flex-row justify-between w-full">
                        {{-- Business Details --}}
                        <div class="flex">
                            <img class="h-28 w-28 rounded-full object-cover" src="{{ asset($business->business_logo_url) }}"
                                alt="Business Logo" />
                            <div class="ml-5">
                                <h3 class="text-5xl font-bold leading-snug">
                                    {{ __($business->business_name) }}
                                </h3>
                                <p class="font-medium text-xl">
                                    {{ $business->business_address }}, {{ $business->state_name }},
                                    {{ $business->city_name }}.
                                </p>
                            </div>
                        </div>

                        {{-- Booking Button --}}
                        <div class="flex items-center">
                            @if ($is_booking_available == true)
                                <a href="{{ route('user.book-appointment.index', ['business_id' => $business->business_id]) }}"
                                    class="bg-{{ $config[11]->config_value }}-500 text-white font-bold text-xl py-4 px-3 md:py-6 md:px-6 w-full flex mt-6 md:mt-0 justify-center rounded-full">
                                    {{ __('Book An Appointment') }}
                                </a>
                            @else
                                <a href="#"
                                    class="bg-{{ $config[11]->config_value }}-500 text-white font-bold text-xl py-4 px-3 md:py-6 md:px-6 w-full flex mt-6 md:mt-0 justify-center rounded-full">
                                    {{ __('Sorry Currently Unavailable.') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-gray-500">{{ $business->business_description }}</p>
                </div>
            </div>
        </section>

        {{-- Our Services --}}
        <section class="py-16 bg-white overflow-hidden" id="faq">
            <div class="container px-4 mx-auto">
                <div class="flex flex-wrap -m-8">
                    <div class="w-full md:w-1/2 p-8">
                        <div class="md:max-w-full">
                            <h2 class="mb-5 text-5xl font-bold font-heading tracking-px-n leading-tight">
                                {{ __('Our Services') }}
                            </h2>
                            <p class="mb-11 text-gray-600 font-medium leading-relaxed">
                                {{ $business->business_name }}
                                {{ __('provides a wide range of services to meet the needs of our clients. We offer the following services:') }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 p-8">
                        <div class="md:max-w-2xl ml-auto">
                            <div class="flex flex-wrap">
                                <div class="w-full">
                                    <a x-data="{ accordion: true }" x-on:click.prevent="accordion = !accordion"
                                        class="block border border-gray-300 rounded-xl" href="#">
                                        <div class="flex flex-wrap justify-between p-5 -m-1.5">
                                            <div class="flex-1 p-1.5">
                                                <div class="flex flex-wrap -m-1.5">
                                                    <div class="flex-1 p-1.5">
                                                        <h3 class="font-semibold leading-normal capitalize">
                                                            {{ __('Services with pricing') }}
                                                        </h3>
                                                        <div x-ref="container"
                                                            :style="accordion ? 'height: ' + $refs.container.scrollHeight +
                                                                'px' : ''"
                                                            class="overflow-hidden h-0 duration-500">
                                                            @foreach ($business_services as $business_service)
                                                                <div class="p-4 border-b">
                                                                    <div class="flex items-center justify-between w-full">
                                                                        <p class="font-semibold">{{ __($business_service->business_service_name) }}</p>
                                                                        <p class="font-semibold">{{ $currency->iso_code }} {{ $business_service->amount }}</p>
                                                                    </div>

                                                                    <p class="mt-4 text-gray-600 font-medium text-indent-4">
                                                                        {{ $business_service->business_service_description }}
                                                                    </p>                                                                    
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-auto p-1.5 flex">
                                                <div :class="{ 'hidden': !accordion }" class="hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-up">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M6 15l6 -6l6 6" />
                                                    </svg>
                                                </div>
                                                <div :class="{ 'hidden': accordion }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M6 9l6 6l6 -6" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Business Plans with Pricing --}}
        @if($business_plans->count() > 0)
        <section class="pb-20 relative overflow-hidden">
            <div class="container px-4 mx-auto">
                <div class="flex flex-wrap -m-8">
                    <div class="w-full md:w-1/2 p-8">
                        <div class="md:max-w-2xl">
                            <h2 class="mb-8 text-6xl md:text-7xl xl:text-7xl font-bold font-heading tracking-px-n leading-none">
                                {{ __('Business Plans') }}
                            </h2>
                            <p class="mb-8 text-lg text-gray-600 font-medium leading-relaxed">
                                {{ __('We offer comprehensive business plans designed to meet your specific needs. Choose from our range of plans:') }}
                            </p>
                        </div>
                        @if(Auth::check() && $user_subscriptions->count() > 0)
                            <div class="md:max-w-2xl">
                                <h2 class="mb-4 text-3xl md:text-6l xl:text-6xl font-bold font-heading tracking-px-n leading-none">
                                    {{ __('My Business Plans') }}
                                </h2>
                                <p class="mb-8 text-lg text-gray-600 font-medium leading-relaxed">
                                    {{ __('Your active business plan subscriptions:') }}
                                </p>
                            </div>
                            <div class="w-full">
                                    <div class="block border border-gray-300 rounded-xl">
                                        <div class="p-5">
                                            <h3 class="font-semibold leading-normal capitalize mb-4 text-red-600">
                                                {{ __('Active Subscriptions') }}
                                            </h3>
                                            @foreach ($user_subscriptions as $subscription)
                                                <div class="p-4 border-b last:border-b-0">
                                                    <div class="flex items-center justify-between w-full">
                                                        <div class="flex-1">
                                                            <p class="font-semibold">{{ $subscription->businessPlan->plan_name }}</p>
                                                            <p class="text-sm text-gray-500">{{ $currency->iso_code }} {{ number_format($subscription->subscription_price, 2) }}</p>
                                                        </div>
                                                        <div class="ml-4">
                                                            <span class="inline-block px-6 py-2 text-xs bg-green-500 text-green-800 rounded-full">
                                                                {{ __('Active') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="mt-2 text-sm text-gray-500">
                                                        {{ __('Expires') }}: {{ $subscription->end_date->format('M d, Y') }}
                                                    </p>
                                                    
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ __('Remaining Days') }}: {{ $subscription->getRemainingDays() }}
                                                    </p>
                                                    
                                                    @php
                                                        $includedServices = $subscription->getIncludedServices();
                                                    @endphp
                                                    @if($includedServices->count() > 0)
                                                    <div class="mt-3">
                                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Included Services') }}:</p>
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($includedServices as $service)
                                                                <span class="inline-block px-6 py-1 text-sm bg-blue-300 text-blue-800 rounded-full">{{ $service->business_service_name }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                        @endif
                    </div>
                    <div class="w-full md:w-1/2 p-8">
                        <div class="md:max-w-2xl ml-auto">
                            <div class="flex flex-wrap">
                                <div class="w-full">
                                    <a x-data="{ accordion: true }" x-on:click.prevent="accordion = !accordion"
                                        class="block border border-gray-300 rounded-xl" href="#">
                                        <div class="flex flex-wrap justify-between p-5 -m-1.5">
                                            <div class="flex-1 p-1.5">
                                                <div class="flex flex-wrap -m-1.5">
                                                    <div class="flex-1 p-1.5">
                                                        <h3 class="font-semibold leading-normal capitalize">
                                                            {{ __('Business Plans with pricing') }}
                                                        </h3>
                                                        <div x-ref="container"
                                                            :style="accordion ? 'height: ' + $refs.container.scrollHeight +
                                                                'px' : ''"
                                                            class="overflow-hidden h-0 duration-500">
                                                            @foreach ($business_plans as $business_plan)
                                                                <div class="p-4 border-b">
                                                                    <div class="flex items-center justify-between w-full">
                                                                        <div class="flex-1">
                                                                            <p class="font-semibold">{{ __($business_plan->plan_name) }}</p>
                                                                            <p class="font-semibold text-lg">{{ $currency->iso_code }} {{ number_format($business_plan->plan_price, 2) }}</p>
                                                                        </div>
                                                                        <div class="ml-4">
                                                                            <button onclick="subscribeToPlan('{{ $business_plan->business_plan_id }}', '{{ $business_plan->plan_name }}', '{{ $business_plan->plan_price }}')" 
                                                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                                                                {{ __('Choose Plan') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <p class="mt-2 text-sm text-gray-500 font-medium">
                                                                        {{ __('Duration') }}: {{ $business_plan->duration_label }}
                                                                    </p>

                                                                    @if($business_plan->plan_description)
                                                                    <p class="mt-4 text-gray-600 font-medium text-indent-4">
                                                                        {{ $business_plan->plan_description }}
                                                                    </p>
                                                                    @endif

                                                                    @php
                                                                        $services = $business_plan->getServices();
                                                                    @endphp
                                                                    @if($services->count() > 0)
                                                                    <div class="mt-3">
                                                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ __('Includes Services') }}:</p>
                                                                        <div class="flex flex-wrap gap-1">
                                                                            @foreach($services as $service)
                                                                                <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $service->business_service_name }}</span>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-auto p-1.5 flex">
                                                <div :class="{ 'hidden': !accordion }" class="hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-up">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M6 15l6 -6l6 6" />
                                                    </svg>
                                                </div>
                                                <div :class="{ 'hidden': accordion }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-down">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M6 9l6 6l6 -6" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>

    {{-- Subscription Confirmation Modal --}}
    <div class="modal modal-blur fade" id="subscription-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Subscription') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        {{ __('Are you sure you want to subscribe to') }} <strong id="modal-plan-name"></strong> for <strong id="modal-plan-price"></strong>?
                    </div>
                    <div class="mb-3">
                        {{ __('This subscription will be active immediately after confirmation.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="cancel-subscription" style="background-color: #dc2626; border-color: #dc2626; color: white;">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm-subscription" style="background-color: #dc2626; border-color: #dc2626; color: white;">
                        <span id="confirm-text">{{ __('Yes, proceed') }}</span>
                        <span id="loading-text" style="display: none;">{{ __('Subscribing...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal modal-blur fade" id="success-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success">{{ __('SUBSCRIPTION SUCCESSFUL') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="success-message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="continue-btn" style="background-color: #dc2626; border-color: #dc2626; color: white;">{{ __('Continue') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Error Modal --}}
    <div class="modal modal-blur fade" id="error-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">{{ __('SUBSCRIPTION FAILED') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="error-message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="close-error-btn" style="background-color: #dc2626; border-color: #dc2626; color: white;">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom CSS --}}
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
        }
        .modal.show {
            display: block !important;
        }
        .modal-dialog {
            position: relative;
            width: auto;
            margin: 0.5rem;
            pointer-events: none;
        }
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.2);
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            outline: 0;
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1rem 0.5rem 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .modal-title {
            margin-bottom: 0;
            line-height: 1.5;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: 0.5;
            cursor: pointer;
            padding: 0;
            width: 1.5rem;
            height: 1.5rem;
        }
        .btn-close:hover {
            opacity: 0.75;
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 100vw;
            height: 100vh;
            background-color: #000;
            opacity: 0.5;
        }
        .modal-backdrop.fade {
            opacity: 0;
        }
        .modal-backdrop.show {
            opacity: 0.5;
        }
        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1rem;
        }
        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            padding: 0.75rem 1rem 1rem 1rem;
            border-top: 1px solid #dee2e6;
            border-bottom-right-radius: calc(0.5rem - 1px);
            border-bottom-left-radius: calc(0.5rem - 1px);
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto;
            }
            .modal-dialog-centered {
                min-height: calc(100% - 3.5rem);
            }
        }
    </style>

    {{-- Custom JS --}}
    <script>
        // Global variables
        let currentPlanId = null;
        let currentBusinessId = '{{ $business->business_id }}';

        // Modal management class
        class ModalManager {
            static show(modalId) {
                const modal = document.getElementById(modalId);
                if (!modal) return false;
                
                modal.style.display = 'block';
                modal.classList.add('show');
                modal.setAttribute('aria-modal', 'true');
                modal.removeAttribute('aria-hidden');
                
                // Add backdrop
                this.addBackdrop();
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
                
                return true;
            }
            
            static hide(modalId) {
                const modal = document.getElementById(modalId);
                if (!modal) return false;
                
                modal.style.display = 'none';
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                modal.removeAttribute('aria-modal');
                
                // Remove backdrop
                this.removeBackdrop();
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                
                return true;
            }
            
            static addBackdrop() {
                if (document.querySelector('.modal-backdrop')) return;
                
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
            
            static removeBackdrop() {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeModals();
            bindEvents();
        });

        function initializeModals() {
            // Hide all modals on page load
            const modals = ['subscription-modal', 'success-modal', 'error-modal'];
            modals.forEach(modalId => {
                ModalManager.hide(modalId);
            });
        }

        function bindEvents() {
            // Cancel subscription button
            const cancelBtn = document.getElementById('cancel-subscription');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    ModalManager.hide('subscription-modal');
                });
            }

            // Confirm subscription button
            const confirmBtn = document.getElementById('confirm-subscription');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', handleSubscription);
            }

            // Continue button (success modal)
            const continueBtn = document.getElementById('continue-btn');
            if (continueBtn) {
                continueBtn.addEventListener('click', function() {
                    ModalManager.hide('success-modal');
                    window.location.reload();
                });
            }

            // Close error button
            const closeErrorBtn = document.getElementById('close-error-btn');
            if (closeErrorBtn) {
                closeErrorBtn.addEventListener('click', function() {
                    ModalManager.hide('error-modal');
                });
            }

            // Close buttons (X buttons)
            document.addEventListener('click', function(event) {
                if (event.target.matches('.btn-close') || event.target.matches('[data-bs-dismiss="modal"]')) {
                    const modal = event.target.closest('.modal');
                    if (modal) {
                        ModalManager.hide(modal.id);
                    }
                }
            });

            // Close modal when clicking backdrop
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    ModalManager.hide(event.target.id);
                }
            });
        }

        // Choose business plan function
        function subscribeToPlan(planId, planName, planPrice) {
            @if(Auth::check())
                currentPlanId = planId;
                
                // Update modal content
                const planNameElement = document.getElementById('modal-plan-name');
                const planPriceElement = document.getElementById('modal-plan-price');
                
                if (planNameElement) planNameElement.textContent = planName;
                if (planPriceElement) planPriceElement.textContent = '{{ $currency->iso_code }} ' + parseFloat(planPrice).toFixed(2);
                
                // Show modal
                ModalManager.show('subscription-modal');
            @else
                // Show login required modal
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.textContent = 'Please login to subscribe to business plans.';
                }
                ModalManager.show('error-modal');
                
                // Redirect to login after a delay
                setTimeout(() => {
                    window.location.href = '{{ route("login") }}';
                }, 2000);
            @endif
        }

        // Handle subscription confirmation
        function handleSubscription() {
            const button = document.getElementById('confirm-subscription');
            const confirmText = document.getElementById('confirm-text');
            const loadingText = document.getElementById('loading-text');
            
            if (!button || !confirmText || !loadingText) return;
            
            // Show loading state
            confirmText.style.display = 'none';
            loadingText.style.display = 'inline';
            button.disabled = true;
            
            // Make AJAX request
            fetch('{{ route("subscribe.business.plan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    business_plan_id: currentPlanId,
                    business_id: currentBusinessId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hide subscription modal
                ModalManager.hide('subscription-modal');
                
                if (data.success) {
                    // Show success modal
                    const successMessage = document.getElementById('success-message');
                    if (successMessage) {
                        successMessage.textContent = data.message;
                    }
                    ModalManager.show('success-modal');
                } else {
                    // Show error modal
                    const errorMessage = document.getElementById('error-message');
                    if (errorMessage) {
                        errorMessage.textContent = data.error || 'Something went wrong. Please try again.';
                    }
                    ModalManager.show('error-modal');
                }
            })
            .catch(error => {
                console.error('Subscription Error:', error);
                
                // Hide subscription modal
                ModalManager.hide('subscription-modal');
                
                // Show error modal
                const errorMessage = document.getElementById('error-message');
                if (errorMessage) {
                    errorMessage.textContent = 'Something went wrong. Please try again.';
                }
                ModalManager.show('error-modal');
            })
            .finally(() => {
                // Reset button state
                if (confirmText) confirmText.style.display = 'inline';
                if (loadingText) loadingText.style.display = 'none';
                if (button) button.disabled = false;
            });
        }
    </script>
@endsection
