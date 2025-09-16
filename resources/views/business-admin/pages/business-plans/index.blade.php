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
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Business Plans') }}
                        </h2>
                    </div>
                    <!-- Add Business Plans -->
                    <div class="col-auto ms-auto d-print-none">
                        <a type="button" href="{{ route('business-admin.business-plans.create', ['business_id' => $business_id]) }}"
                            class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            {{ __('Create') }}
                        </a>
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
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table table-vcenter card-table" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('Plan Name') }}</th>
                                            <th class="text-start">{{ __('Services') }}</th>
                                            <th class="text-start">{{ __('Duration') }}</th>
                                            <th class="text-start">{{ __('Price') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="w-1 text-start">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($businessPlans->count() > 0)
                                            @foreach ($businessPlans as $plan)
                                                <tr>
                                                    <td class="text-start">{{ $loop->iteration }}</td>
                                                    <td class="text-start">
                                                        <div>
                                                            <div class="font-weight-bold">{{ $plan->plan_name }}</div>
                                                            
                                                        </div>
                                                    </td>
                                                    <td class="text-start">
                                                        @php
                                                            $services = $plan->getServices();
                                                        @endphp
                                                        @if($services->count() > 0)
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach($services as $service)
                                                                    <span class="badge bg-blue">{{ $service->business_service_name }}</span>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-muted">{{ __('No services') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-start">{{ $plan->duration_label }}</td>
                                                    <td class="text-start">
                                                        <span class="font-weight-bold">Rs {{ number_format($plan->plan_price, 2) }}</span>
                                                    </td>
                                                    <td class="text-start">
                                                        @if ($plan->is_active)
                                                            <span class="badge bg-green">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge bg-red">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="dropdown">
                                                            <button class="btn small-btn dropdown-toggle align-text-top"
                                                                data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                                aria-expanded="false">{{ __('Actions') }}</button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('business-admin.business-plans.edit', ['business_id' => $business_id, 'business_plan_id' => $plan->business_plan_id]) }}">
                                                                    {{ __('Edit') }}
                                                                </a>
                                                                @if ($plan->is_active)
                                                                    <a class="dropdown-item" href="#"
                                                                        onclick="toggleBusinessPlan('{{ $business_id }}', '{{ $plan->business_plan_id }}', 0); return false;">
                                                                        {{ __('Deactivate') }}
                                                                    </a>
                                                                @else
                                                                    <a class="dropdown-item" href="#"
                                                                        onclick="toggleBusinessPlan('{{ $business_id }}', '{{ $plan->business_plan_id }}', 1); return false;">
                                                                        {{ __('Activate') }}
                                                                    </a>
                                                                @endif
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="deleteBusinessPlan('{{ $business_id }}', '{{ $plan->business_plan_id }}'); return false;">
                                                                    {{ __('Delete') }}
                                                                </a>
                                                            </div>
                                                    </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                        @else
                                            <tr class="empty-row">
                                                <td colspan="7" class="text-center py-4">
                                                {{ __('No business plans found') }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('business-admin.includes.footer')
    </div>

    {{-- Activation Modal --}}
    <div class="modal modal-blur fade" id="activation-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will activate/deactivate this business plan.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="business_plan_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will delete this business plan.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form id="delete-form" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Yes, proceed') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Custom JS --}}
@section('custom-js')
    {{-- Datatable --}}
    <script>
        $(document).ready(function() {
            // Check if table has data rows (not empty state)
            var hasDataRows = $('#table tbody tr').length > 0 && !$('#table tbody tr').hasClass('empty-row');
            
            if (hasDataRows) {
                try {
                    $('#table').DataTable({
                        "order": [
                            [0, "asc"]
                        ],
                        "language": {
                            "emptyTable": "No business plans found"
                        }
                    });
                } catch (error) {
                    console.log('DataTable initialization error:', error);
                }
            }
        });
    </script>


    <script>
        // Toggle Business Plan Status
        function toggleBusinessPlan(businessId, planId, status) {
            "use strict";

            $("#activation-modal").modal("show");

            // Get the link element
            var link = document.getElementById("business_plan_id");

            // Create the URL for status toggle
            var url = "{{ route('business-admin.business-plans.toggle', ['business_id' => '__business_id__', 'business_plan_id' => '__business_plan_id__']) }}";
            url = url.replace('__business_id__', businessId).replace('__business_plan_id__', planId);
            url += '?status=' + status;

            // Set the href attribute of the link element
            link.setAttribute("href", url);
        }

        // Delete Business Plan
        function deleteBusinessPlan(businessId, planId) {
            "use strict";
            
            $("#delete-modal").modal("show");

            // Get the form element
            var form = document.getElementById("delete-form");

            // Create the URL for deletion
            var url = "{{ route('business-admin.business-plans.destroy', ['business_id' => '__business_id__', 'business_plan_id' => '__business_plan_id__']) }}";
            url = url.replace('__business_id__', businessId).replace('__business_plan_id__', planId);

            // Set the action attribute of the form element
            form.setAttribute("action", url);
        }
    </script>
@endsection
@endsection
