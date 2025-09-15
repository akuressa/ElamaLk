@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    <style>
        .page-wrapper {
            flex: initial !important;
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="container mx-auto mt-6 px-4 h-screen">
            <div class="w-full">
                <div class="bg-white border rounded-lg p-6">
                    {{-- Plan Details --}}
                    <div class="card-body flex justify-between">
                        <div class="flex flex-col">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ __('Service') }}: <span
                                    class="font-medium text-gray-800">{{ __($service_name) }}</span></h3>
                            <p class="text-lg font-bold text-gray-800">
                                {{ __('Total') }}: <span class="font-medium text-gray-800">{{ $currency }}
                                    {{ $total }}</span>
                            </p>
                        </div>
                        <button id="rzp-button1"
                            class="bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">{{ __('Pay Now') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Custom JS --}}
@section('custom-js')
    {{-- Razorpay --}}
    <script type="text/javascript" src="{{ asset('assets/js/razorpay-checkout.js') }}"></script>
    <script>
        ! function() {
            "use strict";
            
            var options = {
                "key": "{{ $config[6]->config_value }}",
                "amount": "{{ $order->amount }}",
                "currency": "{{ $order->currency }}",
                "name": "{{ env('APP_NAME') }}",
                "description": "Upgrade Package",
                "image": "{{ asset($setting->site_logo) }}",
                "order_id": "{{ $order->id }}",
                "handler": function(response) {
                    window.location = "../razorpay-booking-payment-status/" + response.razorpay_order_id + "/" +
                        response
                        .razorpay_payment_id;
                },
                "prefill": {
                    "name": "{{ Auth::user()->name }}",
                    "email": "{{ Auth::user()->email }}",
                    "contact": ""
                },
                "notes": {
                    "bookin_transaction_id": "{{ $bookin_transaction_id }}"
                },
                "theme": {
                    "color": "#613BBB"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed',
                function(response) {
                    window.location = "../razorpay-booking-payment-status/" + response.error.metadata.order_id + "/" +
                        response
                        .error
                        .metadata.payment_id;
                });
            document.getElementById('rzp-button1').onclick = function(e) {
                rzp1.open();
                e.preventDefault();
            }
        }();
    </script>
@endsection
@endsection
