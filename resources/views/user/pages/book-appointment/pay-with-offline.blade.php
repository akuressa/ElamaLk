@extends('user.layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container mx-auto mt-6 px-4 h-screen">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Service Details --}}
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div>
                        <form action="{{ route('mark.booking.payment.payment') }}" method="post">
                            @csrf
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                {{ __('Service Name') }}: {{ $service_name }}
                            </h3>
                            <input type="hidden" value="{{ $booking_details->booking_id }}" name="booking_id">
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-2 required">
                                    {{ __('Transaction ID') }}
                                </label>
                                <input type="text"
                                    class="border border-gray-300 focus:border-none rounded-md w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500"
                                    name="transaction_id" placeholder="{{ __('Transaction ID') }}" required>
                            </div>
                            <div class="mt-4">
                                <button type="submit"
                                    class="bg-{{ $config[11]->config_value }}-600 text-white font-semibold py-2 px-4 rounded hover:bg-{{ $config[11]->config_value }}-700 focus:outline-none focus:ring-2 focus:ring-{{ $config[11]->config_value }}-500 focus:ring-opacity-50">
                                    {{ __('Verify Payment') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Bank Details --}}
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ __('Bank Details') }}</h3>
                    <div class="bg-gray-800 text-white p-4 rounded">
                        {!! $config[31]->config_value !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
