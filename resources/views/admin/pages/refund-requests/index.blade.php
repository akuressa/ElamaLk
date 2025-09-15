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
                            {{ __('Refund Requests') }}
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
                                            <th class="text-start">{{ __('Full Name') }}</th>
                                            <th class="text-start">{{ __('Email') }}</th>
                                            <th class="text-start">{{ __('Date') }}</th>
                                            <th class="text-start">{{ __('Amount') }}</th>
                                            <th class="text-start">{{ __('Banking Details') }} </th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="w-1 text-start">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($refund_requests as $refund_request)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start">{{ $refund_request->name }}</td>
                                                <td class="text-start">{{ $refund_request->email }}</td>
                                                <td class="text-start">{{ $refund_request->created_at }}</td>
                                                <td class="text-start">
                                                    {{ $currency->symbol }}{{ $refund_request->total_price }}</td>
                                                <td class="text-start text-blue" style="max-width: 100px;">
                                                    <span class="text-truncate" style="cursor: pointer;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#message-modal-{{ $refund_request->id }}">
                                                        {{ Str::limit($refund_request->refund_message ?? '-', 15) }}
                                                    </span>
                                                </td>
                                                <td class="text-start">
                                                    @if ($refund_request->is_refund == 1)
                                                        <span class="badge bg-orange">{{ __('Pending') }}</span>
                                                    @else
                                                        <span class="badge bg-green">{{ __('Success') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            @if ($refund_request->is_refund == 0)
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.refund.status', ['id' => $refund_request->booking_id, 'status' => 1]) }}">{{ __('Pending') }}</a>
                                                            @endif
                                                            @if ($refund_request->is_refund == 1)
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="approveRequest('{{ $refund_request->booking_id }}'); return false;">{{ __('Success') }}</a>
                                                            @endif
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>

                                            <!-- Message Modal -->
                                            <div class="modal modal-blur fade" id="message-modal-{{ $refund_request->id }}"
                                                tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body"
                                                            style="max-height: 400px; overflow-y: auto;">
                                                            <div class="modal-title">
                                                                {{ __('From ') . $refund_request->name }}</div>
                                                            <div>{{ $refund_request->refund_message ?? '-' }}</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

    {{-- refund request modal --}}
    <div class="modal modal-blur fade" id="refund-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will approve/reject this refund request.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="booking_id">{{ __('Yes, proceed') }}</a>
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

    {{-- Approve refund request --}}
    <script>
        function approveRequest(parameter) {
            "use strict";

            $("#refund-modal").modal("show");
            var link = document.getElementById("booking_id");
            link.getAttribute("href");
            link.setAttribute("href", "/admin/refund-status/" + parameter + "/0");
        }
    </script>
@endsection
@endsection
