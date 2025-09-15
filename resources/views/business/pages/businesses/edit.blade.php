@extends('business.layouts.app')

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
                            {{ __('Edit Business') }}
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
                        <form action="{{ route('business.update.business', $business_details->business_id) }}"
                            method="post" class="card" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Business Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            {{-- Business Name --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Business Name') }}</label>
                                                    <input type="text" class="form-control text-capitalize"
                                                        name="business_name" placeholder="{{ __('Business Name') }}"
                                                        value="{{ $business_details->business_name }}" required />
                                                </div>
                                            </div>

                                            {{-- Business Category Name --}}
                                            <div class="col-sm-4 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label required"
                                                        for="business_category_name">{{ __('Business Category Name') }}</label>
                                                    <select class="form-select" name="business_category_id"
                                                        id="business_category_name" required>
                                                        @foreach ($business_categories as $business_category)
                                                            <option value="{{ $business_category->business_category_id }}"
                                                                {{ $business_category_id == $business_category->business_category_id ? 'selected' : '' }}>
                                                                {{ $business_category->business_category_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Description --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Description') }}</label>
                                                    <textarea class="form-control text-capitalize" name="business_description" rows="3"
                                                        placeholder="{{ __('Description') }}.." required>{{ old('business_description', $business_details->business_description) }}</textarea>
                                                </div>
                                            </div>

                                            {{-- Website Details --}}
                                            <h2 class="page-title my-3">
                                                {{ __('Website Details') }}
                                            </h2>

                                            {{-- Cover Image --}}
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Cover Image') }}
                                                    </div>
                                                    <input type="file" class="form-control"
                                                        name="business_cover_image_url"
                                                        value="{{ $business_details->business_cover_image_url }}"
                                                        placeholder="{{ __('Cover Image') }}"
                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml" />
                                                </div>
                                            </div>

                                            {{-- Logo Url --}}
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Logo') }}
                                                    </div>
                                                    <input type="file" class="form-control" name="business_logo_url"
                                                        placeholder="{{ __('Logo') }}"
                                                        value="{{ $business_details->business_logo_url }}"
                                                        accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml" />
                                                </div>
                                            </div>


                                            {{-- Website Url --}}
                                            <div class="col-md-6 col-xl-4 ">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Website Url') }}</label>
                                                    <input type="text" class="form-control" name="business_website_url"
                                                        placeholder="{{ __('Website Url') }}"
                                                        value="{{ $business_details->business_website_url }}" />
                                                </div>
                                            </div>

                                            {{-- Personal Details --}}
                                            <h2 class="page-title my-3">
                                                {{ __('Personal Details') }}
                                            </h2>

                                            {{-- Email --}}
                                            <div class="col-md-6 col-xl-4 ">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Email') }}</label>
                                                    <input type="email" class="form-control" name="business_email"
                                                        placeholder="{{ __('Email') }}"
                                                        value="{{ $business_details->business_email }}" required />
                                                </div>
                                            </div>

                                            {{-- Phone --}}
                                            <div class="col-md-6 col-xl-4 ">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Phone') }}</label>
                                                    <input type="number" min="10" class="form-control"
                                                        name="business_phone" placeholder="{{ __('Phone') }}"
                                                        value="{{ $business_details->business_phone }}" required />
                                                </div>
                                            </div>

                                            {{-- Address Line --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Address Line') }}</label>
                                                    <input type="text" class="form-control" name="business_address"
                                                        placeholder="{{ __('Address Line') }}"
                                                        value="{{ $business_details->business_address }}" required />
                                                </div>
                                            </div>

                                            {{-- State --}}
                                            <div class="col-sm-4 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label required"
                                                        for="state">{{ __('State/Province') }}</label>
                                                    <select class="form-select" name="business_state" id="state"
                                                        onchange="fetchCities(this.value)" required>
                                                        @foreach ($default_states as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ $business_details->business_state == $state->id ? 'selected' : '' }}>
                                                                {{ $state->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- City --}}
                                            <div class="col-sm-4 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label required"
                                                        for="city">{{ __('City') }}</label>
                                                    <select class="form-select" name="business_city" id="city"
                                                        required>
                                                        @foreach ($default_cities as $city)
                                                            <option value="{{ $city->id }}"
                                                                {{ $business_details->business_city == $city->id ? 'selected' : '' }}>
                                                                {{ $city->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Tax Details --}}
                                            <h2 class="page-title my-3">
                                                {{ __('Tax Details') }}
                                            </h2>

                                            {{-- Tax Number --}}
                                            <div class="col-md-6 col-xl-4 ">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Tax Number') }}</label>
                                                    <input type="text" class="form-control text-capitalize"
                                                        name="business_tax_number" placeholder="{{ __('Tax Number') }}"
                                                        value="{{ $business_details->tax_number }}"  />
                                                </div>
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
                                "
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('business.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        // Cities
        function fetchCities(stateId) {
            "use strict";

            // Destroy TomSelect instance if it exists
            if (typeof $("#city")[0].tomselect !== 'undefined') {
                $("#city")[0].tomselect.destroy();
            }

            // Fetch cities based on selected state
            $.ajax({
                url: "{{ route('business.cities.index') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                dataType: "json",
                data: {
                    state_id: stateId
                },
                success: function(response) {
                    $("#city").html("");
                    // Append new options to the city dropdown
                    for (var i = 0; i < response.length; i++) {
                        $("#city").append("<option value='" + response[i].id + "'>" + response[i].name +
                            "</option>");
                    }

                    // Reinitialize TomSelect for the city dropdown
                    new TomSelect("#city", {
                        copyClassesToDropdown: false,
                        dropdownClass: 'dropdown-menu ts-dropdown',
                        optionClass: 'dropdown-item',
                        controlInput: '<input>',
                        maxOptions: null,
                    });
                }
            });
        }

        // Apply TomSelect to the initial country dropdown
        var elementIds = ['state', 'city', 'business_category_name'];

        elementIds.forEach(function(id) {
            "use strict";
            var el = document.getElementById(id);
            if (el) {
                new TomSelect(el, {
                    copyClassesToDropdown: false,
                    dropdownClass: 'dropdown-menu ts-dropdown',
                    optionClass: 'dropdown-item',
                    controlInput: '<input>',
                    maxOptions: null
                });
            }
        });
    </script>
@endsection
@endsection
