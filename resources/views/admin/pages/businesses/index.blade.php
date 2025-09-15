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
                            {{ __('Businesses') }}
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
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table table-vcenter card-table" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('Business Name') }}</th>
                                            <th class="text-start">{{ __('Category') }}</th>
                                            <th class="text-start">{{ __('Email') }}</th>
                                            <th class="text-start">{{ __('Joined') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="text-start w-1">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($businesses as $business)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start"><a class="font-normal"
                                                        href="{{ route('admin.business.index', ['business_id' => $business->business_id]) }}">{{ __($business->business_name) }}
                                                    </a></td>
                                                <td class="text-start">{{ __($business->business_category_name) }}</td>
                                                <td class="text-start">{{ __($business->business_email) }}</td>
                                                <td class="text-start">{{ $business->created_at }}</td>
                                                <td class="text-start">
                                                    @if ($business->status == 0)
                                                        <span class="badge bg-red">{{ __('Discontinued') }}</span>
                                                    @else
                                                        <span class="badge bg-green">{{ __('Active') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.business.index', ['business_id' => $business->business_id]) }}">
                                                                {{ __('Open') }}
                                                            </a>
                                                            @if ($business->status == 0)
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getBusiness('{{ $business->business_id }}'); return false;">{{ __('Activate') }}</a>
                                                            @elseif ($business->status == 1)
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getBusiness('{{ $business->business_id }}'); return false;">{{ __('Deactivate') }}</a>
                                                            @endif
                                                            <a class="dropdown-item" href="#"
                                                                onclick="deleteBusiness('{{ $business->business_id }}'); return false;">{{ __('Delete') }}</a>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>

    {{-- Activation Modal --}}
    <div class="modal modal-blur fade" id="activation-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will active/deactivate this business data.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="business_id">{{ __('Yes, proceed') }}</a>
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
                    <div>{{ __('If you proceed, you will delete this business data.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="delete_business_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    {{-- Datatable --}}
    <script>
        $('#table').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
    </script>

    <script>
        // Get business
        function getBusiness(parameter) {
            "use strict";

            console.log(parameter);
            $("#activation-modal").modal("show");
            var link = document.getElementById("business_id");
            link.setAttribute("href", "{{ route('admin.activation.business') }}?business_id=" + parameter);
        }

        // Delete business
        function deleteBusiness(parameter) {
            "use strict";

            $("#delete-modal").modal("show");
            var link = document.getElementById("delete_business_id");
            link.setAttribute("href", "{{ route('admin.delete.business') }}?business_id=" + parameter);
        }
    </script>
@endsection
@endsection
