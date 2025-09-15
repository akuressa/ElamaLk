@extends('user.layouts.app')

@section('content')
    {{-- Failed --}}
    @if (Session::has('failed'))
        <div class="flex items-center justify-between p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300"
            role="alert">
            <div class="flex items-center">
                {{ Session::get('failed') }}
            </div>
            <button type="button" class="ml-3 text-red-700 hover:text-red-900"
                onclick="this.parentElement.style.display='none'" aria-label="Close">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9l-5-5m0 0l5 5-5 5m5-5l5-5m-5 5l5 5-5-5z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Success --}}
    @if (Session::has('success'))
        <div class="flex items-center justify-between p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300"
            role="alert">
            <div class="flex items-center">
                {{ Session::get('success') }}
            </div>
            <button type="button" class="ml-3 text-green-700 hover:text-green-900"
                onclick="this.parentElement.style.display='none'" aria-label="Close">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9l-5-5m0 0l5 5-5 5m5-5l5-5m-5 5l5 5-5-5z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    @endif

    <div id="booking-container" class="p-4 ">
        <div class="overflow-x-auto h-screen"> 
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('S.No.') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Date') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Gateway Name') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Amount') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Status') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Transactions --}}
                <tbody id="booking-body">
                    @if ($booking_transactions->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center p-5">{{ __('No Datas found!') }}</td>
                        </tr>
                    @else
                        @foreach ($booking_transactions as $index => $transaction)
                            <tr>
                                <td class="px-4 py-2 border border-gray-300">
                                    {{ $index + 1 + ($booking_transactions->currentPage() - 1) * $booking_transactions->perPage() }}
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-y') }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $transaction->payment_gateway_name }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $transaction->transaction_total }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    @if ($transaction->transaction_status == 'completed')
                                        <span
                                            class="inline-block w-20 text-center px-2 py-1 rounded bg-green-500 text-white">{{ __('Success') }}</span>
                                    @elseif($transaction->transaction_status == 'pending')
                                        <span
                                            class="inline-block w-20 text-center px-2 py-1 rounded bg-yellow-500 text-white">{{ __('Pending') }}</span>
                                    @elseif($transaction->transaction_status == 'refunded')
                                        <span
                                            class="inline-block w-20 text-center px-2 py-1 rounded bg-blue-500 text-white">{{ __('Refunded') }}</span>
                                    @elseif($transaction->transaction_status == 'failed')
                                        <span
                                            class="inline-block w-20 text-center px-2 py-1 rounded bg-red-500 text-white">{{ __('Failed') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    @if ($transaction->transaction_status == 'completed')
                                        <a class="inline-block w-20 text-center px-2 py-1 rounded bg-{{ $config[11]->config_value }}-500 text-white"
                                            href="{{ route('user.view.invoice', ['id' => $transaction->booking_transaction_id]) }}">{{ __('Invoice') }}</a>
                                    @else
                                        <p class="ml-8 text-xl">-</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-end mt-4">
            {{ $booking_transactions->links() }} <!-- Laravel's built-in pagination links -->
        </div>
    </div>
@endsection
