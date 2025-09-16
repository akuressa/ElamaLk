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
                            {{ __('Business Plans') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Edit Business Plan') }}
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
                    {{-- Update Business Plan --}}
                    <div class="col-sm-12 col-lg-12">
                        <form
                            action="{{ route('business-admin.business-plans.update', ['business_id' => $business_id, 'business_plan_id' => $businessPlan->business_plan_id]) }}"
                            method="post" class="card">
                    @csrf
                    @method('PUT')
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Plan Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">

                    {{-- Plan Name --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Plan Name') }}</label>
                                                    <input type="text" class="form-control" name="plan_name"
                                                        placeholder="{{ __('Enter plan name') }}"
                                                        value="{{ old('plan_name', $businessPlan->plan_name) }}" required />
                                                </div>
                    </div>

                                            {{-- Plan Price --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Plan Price (Rs)') }}</label>
                                                    <input type="number" class="form-control" name="plan_price"
                                                        placeholder="{{ __('Enter plan price') }}"
                                                        value="{{ old('plan_price', $businessPlan->plan_price) }}" step="0.01" min="0" required />
                                                </div>
                    </div>

                                            {{-- Select Services --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Select Services') }}</label>
                                                    <div class="form-checklist border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                                        @foreach($businessServices as $service)
                                                            <label class="form-check">
                                                                <input type="checkbox" 
                                                                    name="business_service_ids[]" 
                                                                    value="{{ $service->business_service_id }}" 
                                                                    class="form-check-input"
                                                                    {{ in_array($service->business_service_id, old('business_service_ids', $businessPlan->business_service_ids ?? [])) ? 'checked' : '' }}>
                                                                <span class="form-check-label">
                                                                    {{ $service->business_service_name }} (Rs {{ number_format($service->amount, 2) }})
                                                                </span>
                        </label>
                            @endforeach
                                                    </div>
                                                    <div class="form-hint">{{ __('Select one or more services for this plan') }}</div>
                                                </div>
                    </div>

                                            {{-- Duration --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Duration') }}</label>
                                                    <select class="form-select" name="duration_months" required>
                                                        <option value="">{{ __('Select duration') }}</option>
                            @foreach($durationOptions as $option)
                                <option value="{{ $option['value'] }}" 
                                                                {{ old('duration_months', $businessPlan->duration_months) == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                                                </div>
                    </div>

                                            {{-- Plan Description --}}
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Plan Description') }}</label>
                                                    <textarea class="form-control" name="plan_description" rows="3"
                                                        placeholder="{{ __('Enter plan description') }}">{{ old('plan_description', $businessPlan->plan_description) }}</textarea>
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
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                        {{ __('Update Plan') }}
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
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";
            
            const form = document.querySelector('form');
            const serviceCheckboxes = document.querySelectorAll('input[name="business_service_ids[]"]');
            
            form.addEventListener('submit', function(e) {
                const checkedServices = Array.from(serviceCheckboxes).filter(cb => cb.checked);
                
                if (checkedServices.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one service for this plan.');
                    return false;
                }
            });
        });
    </script>
    @endsection
@endsection