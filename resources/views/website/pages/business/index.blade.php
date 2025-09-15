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
    </div>
@endsection
