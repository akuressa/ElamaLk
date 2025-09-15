@extends('user.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    {{-- HTML2PDF --}}
    <script src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        // Generate PDF
        function generatePDF() {
            "use strict";

            const element = document.getElementById('invoice');
            html2pdf()
                .set({
                    filename: `{{ $transaction->invoice_prefix ? $transaction->invoice_prefix : 'TR' }}{{ $transaction->invoice_number ? $transaction->invoice_number : $transaction->transaction_id }}` +
                        '.pdf',
                    html2canvas: {
                        scale: 4
                    }
                })
                .from(element)
                .save();
        }

        // Print Div
        function printDiv(divId) {
            "use strict";
            
            const printContents = document.getElementById(divId).innerHTML;
            const originalContents = document.body.innerHTML;

            // Write the div content into the body for printing
            document.body.innerHTML = printContents;

            // Trigger the print function
            window.print();

            // Restore the original page content after printing
            document.body.innerHTML = originalContents;

            // Reload the styles to apply them correctly again
            window.location.reload();
        }
    </script>
@endsection

@section('content')
    <div>
        <div class="print:block flex justify-end w-full space-x-2">
            <button
                class="inline-block hover:cursor-pointer w-24 text-center px-4 py-2 rounded bg-{{ $config[11]->config_value }}-500 text-white font-medium"
                onclick="generatePDF()">
                {{ __('Download') }}
            </button>
            <button
                class="inline-block hover:cursor-pointer w-24 text-center px-4 py-2 rounded bg-{{ $config[11]->config_value }}-500 text-white font-medium"
                onclick="printDiv('invoice')">
                {{ __('Print') }}
            </button>
        </div>

        <div class="page-body mt-10">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="p-6" id="invoice">
                    <div class="flex flex-wrap ">
                        <div class="w-1/2 px-4">
                            <p class="text-xl font-semibold">
                                {{ $transaction->billing_details['from_billing_name'] }}</p>
                            <address class="text-gray-600">
                                {{ $transaction->billing_details['from_billing_name'] }}<br>
                                {{ $transaction->billing_details['from_billing_address'] ?? __('Not Available') }}<br>
                                {{ $transaction->billing_details['from_billing_state'] ?? __('Not Available') }},
                                {{ $transaction->billing_details['from_billing_city'] ?? __('Not Available') }}<br>
                                {{ $transaction->billing_details['from_billing_country'] ?? __('Not Available') }},
                                {{ $transaction->billing_details['from_billing_zipcode'] ?? __('Not Available') }}<br>
                                {{ $transaction->billing_details['from_billing_email'] ?? __('Not Available') }}<br>
                                {{ $transaction->billing_details['from_billing_phone'] ?? __('Not Available') }}<br>
                                @if ($transaction->billing_details['from_vat_number'] != null)
                                    <p>{{ __('Tax Number:') }}
                                        {{ $transaction->billing_details['from_vat_number'] }}</p>
                                @endif
                            </address>
                        </div>
                        <div class="w-1/2 px-4 text-right">
                            <p class="text-xl font-semibold">{{ $transaction->billing_details['to_billing_name'] }}
                            </p>
                            <address class="text-gray-600">
                                {{ $transaction->billing_details['to_billing_email'] ?? __('Not Available') }}<br>
                            </address>
                            <h4 class="text-lg font-bold">{{ __('INVOICE DATE') }} :
                                {{ date('d-m-Y h:i A', strtotime($transaction->transaction_date)) }}</h4>
                        </div>
                        @if ($transaction->invoice_number > 0)
                            <div class="flex items-center w-full my-5">
                                <div class="w-10/12 text-2xl font-bold">
                                    {{ __('INVOICE NO') }} :
                                    {{ $transaction->invoice_prefix }}{{ $transaction->invoice_number }}
                                </div>
                                <div class="w-2/12">
                                    <img src="{{ asset('img/payments/paid.png') }}" class="p-4">
                                </div>
                            </div>
                        @endif
                    </div>
                    <table class="w-full text-left mt-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-center w-1 border">{{ __('S.No') }}</th>
                                <th class="p-3 border">{{ __('Description') }}</th>
                                <th class="p-3 text-right border">{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-3 text-center border">1</td>
                                <td class="p-3 border">
                                    <p class="font-bold">{{ __('Service') }} : {{ __($service_name) }}</p>
                                    <div class="text-gray-600">{{ __('Via') }} :
                                        {{ __($transaction->payment_gateway_name) }}</div>
                                </td>
                                <td class="p-3 text-right border">
                                    @foreach ($currencies as $currency)
                                        @if ($transaction->transaction_currency == $currency->iso_code)
                                            {{ $currency->symbol }}
                                            {{ number_format($transaction->billing_details['service_amount'], 2) }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-3 text-right font-semibold border">
                                    {{ __('Payment Gateway Charge') }}</td>
                                <td class="p-3 text-right border">
                                    @foreach ($currencies as $currency)
                                        @if ($transaction->transaction_currency == $currency->iso_code)
                                            {{ $currency->symbol }}
                                            {{ number_format($transaction->billing_details['payment_gateway_charge'], 2) }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-3 text-right font-semibold border">{{ __('Subtotal') }}</td>
                                <td class="p-3 text-right border">
                                    @foreach ($currencies as $currency)
                                        @if ($transaction->transaction_currency == $currency->iso_code)
                                            {{ $currency->symbol }}
                                            {{ number_format($transaction->billing_details['subtotal'], 2) }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            @if ($transaction->billing_details['tax_amount'] > 0)
                                <tr>
                                    <td colspan="2" class="p-3 text-right font-semibold border">
                                        {{ __('Tax') }}
                                        ({{ $transaction->billing_details['tax_value'] }}%)
                                    </td>
                                    <td class="p-3 text-right border">
                                        @foreach ($currencies as $currency)
                                            @if ($transaction->transaction_currency == $currency->iso_code)
                                                {{ $currency->symbol }}
                                                {{ number_format($transaction->billing_details['tax_amount'], 2) }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="2" class="p-3 text-right font-bold text-uppercase border">
                                    {{ __('Total') }}</td>
                                <td class="p-3 text-right font-bold border">
                                    @foreach ($currencies as $currency)
                                        @if ($transaction->transaction_currency == $currency->iso_code)
                                            {{ $currency->symbol }}
                                            {{ number_format($transaction->billing_details['invoice_amount'], 2) }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
