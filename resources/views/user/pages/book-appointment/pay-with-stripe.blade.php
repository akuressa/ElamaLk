@extends('user.layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container mx-auto mt-6 px-4 h-screen">
            <div class="w-full">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __($service_name) }}</h3>

                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form action="{{ route('booking.payment.stripe.status', $paymentId) }}" method="post"
                            id="payment-form">
                            @csrf

                            <div class="mb-4">
                                <div class="border-b pb-2 mb-4">
                                    <label for="card-element" class="text-gray-700 font-semibold">
                                        {{ __('Please enter your credit card information') }}
                                    </label>
                                </div>
                                <div>
                                    <div id="card-element" class="p-2 border rounded">
                                        <!-- Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Display form errors. -->
                                    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                                    <input type="hidden" name="plan" value="" />
                                </div>
                            </div>

                            <div class="pt-4">
                                <button id="card-button"
                                    class="bg-gray-800 text-white font-semibold py-2 px-4 rounded hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                                    type="submit" data-secret="{{ $intent }}">
                                    {{ __('Pay Now') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <br>
                    <a class="mt-2 text-gray-600 underline hover:text-gray-800"
                        href="{{ route('booking.stripe.payment.cancel', $paymentId) }}">
                        {{ __('Cancel payment and back to home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- Custom JS --}}
@section('custom-js')
    {{-- Stripe --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        ! function() {
            "use strict";
            
            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            const stripe = Stripe('{{ $config[9]->config_value }}', {
                locale: 'en'
            }); // Create a Stripe client.
            const elements = stripe.elements(); // Create an instance of Elements.
            const cardElement = elements.create('card', {
                style: style
            }); // Create an instance of the card Element.
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            cardElement.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.

            // Handle real-time validation errors from the card Element.
            cardElement.addEventListener('change', function(event) {
                "use strict";
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');

            form.addEventListener('submit', function(event) {
                "use strict";
                event.preventDefault();

                stripe.handleCardPayment(clientSecret, cardElement, {
                        payment_method_data: {
                            //billing_details: { name: cardHolderName.value }
                        }
                    })
                    .then(function(result) {
                        console.log(result);
                        if (result.error) {
                            // Inform the user if there was an error.
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                        } else {
                            console.log(result);
                            form.submit();
                        }
                    });
            });
        }();
    </script>
@endsection
@endsection
