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
                            {{ __('Booking Transactions') }}
                        </h2>
                    </div>
                    <!-- Add plan -->
                    <div class="col-auto ms-auto d-print-none">
                        <a type="button" href="{{ route('admin.booking.offline.transactions') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <rect x="9" y="3" width="6" height="4" rx="2" />
                                <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                <path d="M12 17v1m0 -8v1" />
                            </svg>
                            {{ __('Offline Transactions') }}
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

                {{-- Transactions --}}
                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            {{-- Transactions --}}
                            <div class="table-responsive px-2 py-2">
                                <table class="table table-vcenter card-table" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('Transaction Date') }}</th>
                                            <th class="text-start">{{ __('Payment Trans ID') }}</th>
                                            <th class="text-start">{{ __('Customer Name') }}</th>
                                            <th class="text-start">{{ __('Gateway Name') }}</th>
                                            <th class="text-start">{{ __('Amount') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="text-start w-1">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start">
                                                    {{ $transaction->created_at->format('d-m-Y H:i:s A') }}</td>
                                                <td class="text-start">
                                                    {{ $transaction->transaction_total != 0.0 ? $transaction->booking_transaction_id : '-' }}
                                                </td>
                                                <td class="text-start"><a
                                                        href="{{ route('admin.view.user', $transaction->userId) }}">{{ __($transaction->name) }}</a>
                                                </td>
                                                <td class="text-start">
                                                    {{ __($transaction->payment_gateway_name) }}
                                                </td>
                                                <td class="text-start">
                                                    {{ $currency->symbol }}{{ $transaction->transaction_total }}
                                                </td>
                                                <td class="text-start">
                                                    @if ($transaction->transaction_status == 'completed')
                                                        <span class="badge bg-green">{{ __('Paid') }}</span>
                                                    @endif
                                                    @if ($transaction->transaction_status == 'failed')
                                                        <span class="badge bg-red">{{ __('Failed') }}</span>
                                                    @endif
                                                    @if ($transaction->transaction_status == 'pending')
                                                        <span class="badge bg-orange">{{ __('Pending') }}</span>
                                                    @endif
                                                    @if ($transaction->transaction_status == 'refunded')
                                                        <span class="badge bg-red">{{ __('Refunded') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            @if ($transaction->transaction_total != 0.0 && $transaction->transaction_status == 'completed')
                                                                <a class="dropdown-item" target="_blank"
                                                                    href="{{ route('admin.view.invoice.booking', ['id' => $transaction->booking_transaction_id]) }}">{{ __('Invoice') }}</a>
                                                            @endif
                                                            @if ($transaction->transaction_status != 'pending')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.booking.trans.status', ['id' => $transaction->booking_transaction_id, 'status' => 'pending']) }}">{{ __('Pending') }}</a>
                                                            @endif
                                                            @if ($transaction->transaction_status != 'completed')
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getTransaction('{{ $transaction->booking_transaction_id }}'); return false;">{{ __('Success') }}</a>
                                                            @endif
                                                            @if ($transaction->transaction_status != 'failed')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.booking.trans.status', ['id' => $transaction->booking_transaction_id, 'status' => 'failed']) }}">{{ __('Failed') }}</a>
                                                            @endif
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

    {{-- Update transaction status --}}
    <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed with this transaction, you will have payment status success this plan.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="booking_transaction_id">{{ __('Yes, proceed') }}</a>
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

    {{-- Get transaction --}}
    <script>
        function getTransaction(parameter) {
            "use strict";
            $("#delete-modal").modal("show");
            var link = document.getElementById("booking_transaction_id");
            link.getAttribute("href");
            link.setAttribute("href", "/admin/booking-transaction-status/" + parameter + "/completed");
        }
    </script>
@endsection
@endsection
