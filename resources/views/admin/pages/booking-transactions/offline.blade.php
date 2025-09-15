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
                            {{ __('Booking Offline Transactions') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">

                {{-- failed --}}
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

                {{-- success --}}
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

                {{-- Offline Transactions --}}
                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table card-table table-vcenter text-nowrap datatable" id="table">
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
                                                <td class="text-start">{{ $transaction->booking_transaction_id }}
                                                </td>
                                                <td class="text-start"><a
                                                        href="{{ route('admin.view.user', $transaction->userId) }}">{{ __($transaction->name) }}</a>
                                                </td>
                                                <td class="text-start">
                                                    {{ __($transaction->payment_gateway_name) }}
                                                </td>
                                                <td class="text-start">
                                                    @foreach ($currencies as $currency)
                                                        @if ($transaction->transaction_currency == $currency->iso_code)
                                                            {{ $currency->symbol }}{{ $transaction->transaction_total }}
                                                        @endif
                                                    @endforeach
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
                                                </td>
                                                <td class="text-end">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            @if (
                                                                $transaction->transaction_total != 0.0 &&
                                                                    $transaction->invoice_number > 0 &&
                                                                    $transaction->transaction_status == 'completed')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.view.invoice.booking', ['id' => $transaction->booking_transaction_id]) }}">{{ __('Invoice') }}</a>
                                                            @endif
                                                            @if ($transaction->transaction_status != 'completed')
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getOfflineTransaction('{{ $transaction->booking_transaction_id }}'); return false;">{{ __('Success') }}</a>
                                                            @endif
                                                            @if ($transaction->transaction_status != 'pending')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.booking.offline.trans.status', ['id' => $transaction->booking_transaction_id, 'status' => 'pending']) }}">{{ __('Pending') }}</a>
                                                            @endif
                                                            @if ($transaction->transaction_status != 'failed')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.booking.offline.trans.status', ['id' => $transaction->booking_transaction_id, 'status' => 'failed']) }}">{{ __('Failed') }}</a>
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
                    <div>
                        {{ __('If you proceed with this transaction, you will have payment status success.') }}
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

    {{-- Get offline transaction --}}
    <script>
        function getOfflineTransaction(parameter) {
            "use strict";
            
            $("#delete-modal").modal("show");
            var link = document.getElementById("booking_transaction_id");
            link.getAttribute("href");
            link.setAttribute("href", "/admin/booking-offline-transaction-status/" + parameter + "/completed");
        }
    </script>
@endsection
@endsection
