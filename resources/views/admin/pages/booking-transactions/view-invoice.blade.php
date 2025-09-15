@extends('admin.layouts.app')

{{-- CUstom CSS --}}
@section('custom-css')
    {{-- HTML2PDF --}}
    <script src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>

    {{-- Generate PDF --}}
    <script>
        function generatePDF() {
            "use strict";

            const element = document.getElementById('invoice');
            html2pdf()
                .set({
                    filename: `{{ $transaction->invoice_prefix ? $transaction->invoice_prefix : 'TR' }}{{ $transaction->invoice_number ? $transaction->invoice_number : $transaction->booking_transaction_id }}` +
                        '.pdf',
                    html2canvas: {
                        scale: 4
                    }
                })
                .from(element)
                .save();
        }
    </script>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            {{ __('Invoice') }}
                        </h2>
                    </div>
                    <!-- Page title actions -->
                    <div class="col-auto ms-auto d-print-none">
                        <div class="dropdown">
                            <button type="button" class="btn btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2">
                                    </path>
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path>
                                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z">
                                    </path>
                                </svg>
                                {{ __('Actions') }}
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" onclick="generatePDF()" onclick="javascript:window.print();">
                                    {{ __('Download') }}
                                </a>
                                <a class="dropdown-item" onclick="javascript:window.print();">
                                    {{ __('Print') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="card card-lg">
                    <div class="p-6" id="invoice">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <p class="h3">{{ $transaction->billing_details['from_billing_name'] }}</p>
                                    <address>
                                        {{ $transaction->billing_details['from_billing_name'] }}<br>
                                        {{ $transaction->billing_details['from_billing_address'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_address'] }}<br>
                                        {{ $transaction->billing_details['from_billing_state'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_state'] }},
                                        {{ $transaction->billing_details['from_billing_city'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_city'] }}<br>
                                        {{ $transaction->billing_details['from_billing_country'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_country'] }},
                                        {{ $transaction->billing_details['from_billing_zipcode'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_zipcode'] }}<br>
                                        {{ $transaction->billing_details['from_billing_email'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_email'] }}

                                        <br>
                                        {{ $transaction->billing_details['from_billing_phone'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['from_billing_phone'] }}
                                        <br>
                                        <br>
                                        @if ($transaction->billing_details['from_vat_number'] != null)
                                            <p>{{ __('Tax Number:') }}
                                                {{ $transaction->billing_details['from_vat_number'] }}</p>
                                        @endif
                                    </address>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="h3">{{ $transaction->billing_details['to_billing_name'] }}</p>
                                    <address>
                                        {{ $transaction->billing_details['to_billing_email'] == null
                                            ? __('Not Available')
                                            : $transaction->billing_details['to_billing_email'] }}
                                        <br><br><br><br><br><br>
                                    </address>
                                    <h3>{{ __('INVOICE DATE') }} :
                                        {{ date('d-m-Y h:i A', strtotime($transaction->transaction_date)) }}</h3>
                                </div>
                                @if ($transaction->invoice_number > 0)
                                    <div class="row">
                                        <div class="col-10 my-5">
                                            <h1>{{ __('INVOICE NO') }} :
                                                {{ $transaction->invoice_prefix }}{{ $transaction->invoice_number }}</h1>
                                        </div>
                                        <div class="col-2">
                                            <img src="{{ asset('img/payments/paid.png') }}" class="img-responsive p-4">
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <table class="table table-transparent table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 1%">#</th>
                                        <th colspan="3">{{ __('Description') }}</th>
                                        <th class="text-end" style="width: 1%">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td colspan="3">
                                        <p class="strong mb-1">{{ __('Service') }} : {{ $transaction->description }}</p>
                                        <div class="text-muted">{{ __('Via') }} :
                                            {{ __($transaction->payment_gateway_name) }}</div>
                                    </td>
                                    <td class="text-end">
                                        @foreach ($currencies as $currency)
                                            @if ($transaction->transaction_currency == $currency->iso_code)
                                                {{ $currency->symbol }}{{ number_format($transaction->billing_details['service_amount'], 2) }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="strong text-end">{{ __('Payment Gateway Charge') }}</td>
                                    <td class="text-end">
                                        @foreach ($currencies as $currency)
                                            @if ($transaction->transaction_currency == $currency->iso_code)
                                                {{ $currency->symbol }}{{ number_format($transaction->billing_details['payment_gateway_charge'], 2) }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="strong text-end">{{ __('Subtotal') }}</td>
                                    <td class="text-end">
                                        @foreach ($currencies as $currency)
                                            @if ($transaction->transaction_currency == $currency->iso_code)
                                                {{ $currency->symbol }}{{ number_format($transaction->billing_details['subtotal'], 2) }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>

                                @if ($transaction->billing_details['tax_amount'] > 0)
                                    <tr>
                                        <td colspan="4" class="strong text-end">
                                            {{ __('Tax') }} {{ __('Rate') }}
                                            ({{ $transaction->billing_details['tax_value'] }}%)
                                        </td>

                                        <td class="strong text-end">
                                            @foreach ($currencies as $currency)
                                                @if ($transaction->transaction_currency == $currency->iso_code)
                                                    {{ $currency->symbol }}{{ number_format($transaction->billing_details['tax_amount'], 2) }}
                                                @endif
                                            @endforeach
                                        </td>

                                    </tr>
                                @endif

                                <tr>
                                    <td colspan="4" class="strong text-uppercase text-end">{{ __('Total') }}</td>
                                    <td class="strong text-end">
                                        @foreach ($currencies as $currency)
                                            @if ($transaction->transaction_currency == $currency->iso_code)
                                                {{ $currency->symbol }}{{ number_format($transaction->billing_details['invoice_amount'], 2) }}
                                            @endif
                                        @endforeach
                                    </td>

                                </tr>
                            </table>
                            <p class="text-bold text-center mt-5">
                                {{ __($config[29]->config_value) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            @include('admin.includes.footer')
        </div>
    </div>
@endsection
