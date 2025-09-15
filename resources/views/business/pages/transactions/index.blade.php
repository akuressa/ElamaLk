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
                            {{ __('Transactions') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table card-table table-vcenter text-nowrap datatable" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('Transaction Date') }}</th>
                                            <th class="w-1">{{ __('Payment ID') }}</th>
                                            <th class="text-start">{{ __('Transaction ID') }}</th>
                                            <th class="text-start">{{ __('Payment Mode') }}</th>
                                            <th class="text-start">{{ __('Amount') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="text-start w-1">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Transactions --}}
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start">
                                                    {{ $transaction->created_at->format('d-m-Y H:i:s A') }}</td>
                                                <td class="text-start">
                                                    <span>{{ $transaction->transaction_total != 0.0 ? $transaction->transaction_id : '-' }}</span>
                                                </td>
                                                <td class="text-start">{{ $transaction->transaction_id }}</td>
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
                                                    @if ($transaction->invoice_number > 0)
                                                        <span class="dropdown">
                                                            <button class="btn small-btn dropdown-toggle align-text-top"
                                                                data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                                aria-expanded="false">{{ __('Actions') }}</button>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('business.view.invoice', ['id' => $transaction->transaction_id]) }}">{{ __('Invoice') }}</a>
                                                            </div>
                                                        </span>
                                                    @endif
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
        @include('business.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        $(document).ready(function() {
            "use strict";

            $('#table').DataTable({
                "order": [
                    [0, "asc"]
                ]
            });
        });
    </script>
@endsection
@endsection
