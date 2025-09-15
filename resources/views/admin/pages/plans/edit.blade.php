@extends('admin.layouts.app')

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
                            {{ __('Edit Plan') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    {{-- Update Plan --}}
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('admin.update.plan', $plan_details->plan_id) }}" method="post"
                            class="card">
                            @csrf

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

                            <div class="card-header">
                                <h4 class="page-title">{{ __('Plan Details') }}</h4>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">

                                            {{-- Recommended --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('Recommended') }}</div>
                                                    <label class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="is_recommended"
                                                            {{ $plan_details->is_recommended == 1 ? 'checked' : '' }} />
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- Private Plan --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('Private Plan') }}</div>
                                                    <label class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="is_private"
                                                            {{ $plan_details->is_private == 1 ? 'checked' : '' }} />
                                                    </label>
                                                    <small
                                                        class="text-muted">{{ __('This plan does not show on the customer page. Only the admin panel can assign this plan to the customer.') }}
                                                    </small>
                                                </div>
                                            </div>

                                            {{-- Plan Name --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Plan Name') }}</label>
                                                    <input type="text" class="form-control text-capitalize"
                                                        name="plan_name" placeholder="{{ __('Plan Name') }}"
                                                        value="{{ $plan_details->plan_name }}" required />
                                                </div>
                                            </div>

                                            {{-- Plan Description --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Description') }}</label>
                                                    <textarea class="form-control text-capitalize" name="plan_description" rows="3"
                                                        placeholder="{{ __('Description') }}.." required>{{ $plan_details->plan_description }}</textarea>

                                                </div>
                                            </div>

                                            {{-- Plan Pricing --}}
                                            <h2 class="page-title my-3">
                                                {{ __('Plan Prices') }}
                                            </h2>
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Price') }}</label>
                                                    <input type="number" class="form-control" name="plan_price"
                                                        min="0" step="0.01" placeholder="{{ __('Price') }}"
                                                        value="{{ $plan_details->plan_price }}" required />
                                                    <small class="text-muted">{{ __('Set 0 for "Free"') }} </small>
                                                </div>
                                            </div>

                                            {{-- Plan Validity --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Validity') }}</label>
                                                    <input type="number" class="form-control" name="plan_validity"
                                                        min="1" max="9999" placeholder="{{ __('Validity') }}"
                                                        value="{{ $plan_details->plan_validity }}" required />
                                                    <small
                                                        class="text-muted">{{ __('Set 31 for "Month", Set 365 for "Year", Set 9999 for "Forever"') }}
                                                    </small>
                                                </div>
                                            </div>

                                            {{-- Features --}}
                                            <h2 class="page-title my-3">
                                                {{ __('Features') }}
                                            </h2>

                                            {{-- Businesses --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Number of Businesses') }}
                                                        <span class="text-muted"></label>
                                                    <input type="number" class="form-control" name="no_of_businesses"
                                                        min="1" placeholder="{{ __('No. of Businesses') }}"
                                                        value="{{ $plan_details->plan_features['no_of_businesses'] }}"
                                                        required />
                                                </div>
                                            </div>

                                            {{-- Services --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Number of Services') }} <span
                                                            class="text-muted"></label>
                                                    <input type="number" class="form-control" name="no_of_services"
                                                        min="1" placeholder="{{ __('No. of Services') }}"
                                                        value="{{ $plan_details->plan_features['no_of_services'] }}"
                                                        required />
                                                </div>
                                            </div>

                                            {{-- Employees --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Number of Employees') }}
                                                        <span class="text-muted"></label>
                                                    <input type="number" class="form-control" name="no_of_employees"
                                                        min="1" placeholder="{{ __('No. of Employees') }}"
                                                        value="{{ $plan_details->plan_features['no_of_employees'] }}"
                                                        required />
                                                </div>
                                            </div>

                                            {{-- Gateway --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label required">{{ __('Payment Gateway Charges(in %)') }}
                                                        <span class="text-muted"></label>
                                                    <input type="number" class="form-control"
                                                        name="payment_gateway_charge" min="0" max="100"
                                                        placeholder="{{ __('Charges(in %)') }}"
                                                        value="{{ $plan_details->plan_features['payment_gateway_charge'] }}"
                                                        required />
                                                </div>
                                            </div>

                                            {{-- Bookings --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Number of Bookings') }}
                                                        <span class="text-muted"></label>
                                                    <input type="number" class="form-control" name="no_of_bookings"
                                                        min="1" placeholder="{{ __('No. of Bookings') }}"
                                                        value="{{ $plan_details->plan_features['no_of_bookings'] }}"
                                                        required />
                                                </div>
                                            </div>


                                            {{-- Additional Features --}}
                                            <h2 class="page-title my-3">
                                                {{ __('Additional') }}
                                            </h2>

                                            {{-- Support --}}
                                            <div class="col-md-3 col-xl-3">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('Customer Support') }}</div>
                                                    <label class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="is_customer_support"
                                                            {{ $plan_details->is_customer_support == 1 ? 'checked' : '' }} />
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-edit" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3">
                                                            </path>
                                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3">
                                                            </path>
                                                            <line x1="16" y1="5" x2="19"
                                                                y2="8"></line>
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
        @include('admin.includes.footer')
    </div>
@endsection
