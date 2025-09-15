@extends('layouts.classic')
@php
    $business_category_slug = request()->route()->parameter('business_category_slug');

    $city = request()->query('city');

@endphp

@section('content')
    <section class="pb-20 white overflow-hidden px-5 mx-auto">
        <div class="container px-4 mx-auto">
            <div class="flex justify-end mr-2 md:mr-0">
                <!-- Filter Button -->
                <button data-dropdown-toggle="cityDropdown"
                    class="flex items-center px-10 py-2 border-2 rounded-lg text-lg font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="{{ $config[11]->config_value }}"
                        class="icon icon-tabler icons-tabler-filled icon-tabler-map-pin">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M18.364 4.636a9 9 0 0 1 .203 12.519l-.203 .21l-4.243 4.242a3 3 0 0 1 -4.097 .135l-.144 -.135l-4.244 -4.243a9 9 0 0 1 12.728 -12.728zm-6.364 3.364a3 3 0 1 0 0 6a3 3 0 0 0 0 -6z" />
                    </svg>
                    <p id="filter-text" class="font-medium">
                        @if ($city == null)
                            {{ __('All') }}
                        @else
                            {{ $city }}
                        @endif
                    </p>
                </button>

                <!-- Filter Panel -->
                <div id="cityDropdown"
                     aria-labelledby="citydropdown1"
                    class="mt-4 p-4 hidden bg-white rounded-xl shadow fixed top-36 right-50 w-60 sm:max-w-xs z-50">
                    <!-- Example Filter Options -->
                    <label class="block mb-2">
                        <span class="font-medium text-md">{{ __('Search City') }}</span>
                    </label>
                    <input type="text" class="w-full rounded-xl border border-gray-300 py-2 pl-2" id="city-input"
                        placeholder="Search City" autofocus />
                    <div id="city-suggestions"
                        class="absolute z-100 mt-4 w-60 max-h-48 overflow-y-auto rounded shadow-lg hidden bg-white">
                    </div>
                    <button
                        class="w-full py-2 bg-{{ $config[11]->config_value }}-500 rounded-xl text-white font-bold text-lg mt-3 z-10"
                        id="apply-button">
                        {{ __('Apply') }}
                    </button>
                </div>
            </div>

            {{-- Businesses --}}
            @if ($businesses->isEmpty())
                <div class="flex flex-col justify-center items-center min-h-screen -my-40">
                    <div class="flex justify-center items-center flex-col ">
                        <img src="{{ asset('img/no-data.svg') }}" alt="" class="w-96 h-96">
                        <p class="text-xl font-bold -mt-10">Sorry No Results Available!</p>
                    </div>
                </div>
            @else
                <div id="businesses-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    @foreach ($businesses as $business)
                        <div class="w-full p-2">
                            <a href="{{ route('web.business', ['business_id' => $business->business_id]) }}"
                                class="bg-{{ $config[11]->config_value }}-50 border-2 border-{{ $config[11]->config_value }}-400 rounded-xl h-full flex flex-col justify-between">
                                <div class="p-6">
                                    <!-- Cover Image -->
                                    <img src="{{ asset($business->business_cover_image_url) }}"
                                        alt="{{ __($business->business_name) }}"
                                        class="w-full h-48 object-cover rounded-lg mb-4">

                                    <!-- Logo and Business Info -->
                                    <div class="flex items-center mb-8">
                                        <img class="h-16 w-16 rounded-full object-cover"
                                            src="{{ asset($business->business_logo_url) }}" alt="Business Logo">
                                        <div class="ml-4">
                                            <h3 class="text-3xl  font-bold leading-snug">
                                                {{ __($business->business_name) }}
                                            </h3>
                                            <p class="font-medium">
                                                {{ $business->state }}, {{ $business->city }}.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Services and Employees Count -->
                                    <ul class="list-none">
                                        <li class="flex items-center  text-lg font-medium">
                                            {{ $business->service_count }}
                                            {{ __($business->service_count == 1 ? __('Service') : __('Services')) }}
                                        </li>
                                        <li class="flex items-center  text-lg font-medium">
                                            {{ $business->employee_count }}
                                            {{ __($business->employee_count == 1 ? __('Employee') : __('Employees')) }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Book Now Button -->
                                <div class="p-6 flex items-center font-bold">
                                    <span class="mr-2">{{ __('Book Now') }}</span>
                                    <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 3.75L16.25 9M16.25 9L11 14.25M16.25 9L2.75 9" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CUstom JS --}}
@section('custom-js')
    <script>
        // Cities
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";

            const cities = @json($cities);
            const cityInput = document.getElementById('city-input');
            const suggestionsContainer = document.getElementById('city-suggestions');


            cityInput.addEventListener('input', function() {
                const filter = cityInput.value.toLowerCase();
                suggestionsContainer.innerHTML = ''; // Clear previous suggestions

                if (filter) {
                    const filteredCities = cities.filter(city =>
                        city.name.toLowerCase().includes(filter)
                    );

                    if (filteredCities.length > 0) {
                        suggestionsContainer.classList.remove('hidden');

                        filteredCities.forEach(city => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.classList.add('p-2', 'hover:bg-gray-200',
                                'cursor-pointer');
                            suggestionItem.textContent = city.name;
                            suggestionItem.dataset.id = city.id;

                            suggestionItem.addEventListener('click', function() {

                                cityInput.value = this
                                    .textContent;
                                cityInput.dataset.id = this.dataset
                                    .id;
                                suggestionsContainer.innerHTML =
                                    '';
                                suggestionsContainer.classList.add(
                                    'hidden');
                            });

                            suggestionsContainer.appendChild(suggestionItem);
                        });
                    } else {
                        suggestionsContainer.classList.add('hidden');
                    }
                } else {
                    suggestionsContainer.classList.add('hidden');
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function(event) {
                if (!cityInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
                    suggestionsContainer.classList.add('hidden');
                }
            });

            // Apply City
            document.getElementById('apply-button').addEventListener('click', function() {
                const selectedCity = cityInput.value; // Get the slug stored in the input                
                if (selectedCity) {
                    // Redirect to the route with the slug
                    window.location.href =
                        `{{ url('businesses/' . $business_category_slug . '?city=') }}${selectedCity}`;
                } else {

                }
            });
        });
    </script>
@endsection
@endsection
