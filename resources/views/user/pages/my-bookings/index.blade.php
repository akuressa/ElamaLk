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

    <div id="booking-container" class="p-4 h-screen">
        <div class="overflow-x-auto"> <!-- Add overflow-x-auto for horizontal scrolling -->
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('S.No.') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Service') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Employee') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Date') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Slot') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Price') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Status') }}</th>
                        <th class="px-4 py-2 border border-gray-300 text-left">{{ __('Cancellation') }}</th>
                    </tr>
                </thead>
                <tbody id="booking-body">
                    @if ($my_bookings->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center p-5">{{ __('No Datas found!') }}</td>
                        </tr>
                    @else
                        @foreach ($my_bookings as $index => $booking)
                            @php
                                $transaction = \App\Models\BookingTransaction::where(
                                    'booking_id',
                                    $booking->booking_id,
                                )->first();

                                $is_offline = false;

                                if ($transaction && $transaction->payment_gateway_name == 'Offline') {
                                    $is_offline = true;
                                }
                            @endphp

                            <tr>
                                <td class="px-4 py-2 border border-gray-300">
                                    {{ $index + 1 + ($my_bookings->currentPage() - 1) * $my_bookings->perPage() }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ __($booking->business_service_name) }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $booking->business_employee_name }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-y') }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $booking->booking_time }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $booking->total_price }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    @if ($booking->status == 1)
                                        <span
                                            class="inline-block w-24 text-center  py-1 rounded bg-green-500 text-white">{{ __('Success') }}</span>
                                    @elseif($booking->status == -1 && $booking->is_refund == 1)
                                        <span
                                            class="inline-block w-24 text-center  py-1 rounded bg-red-500 text-white">{{ __('Cancelled') }}</span>
                                    @elseif($booking->status == -1 && $booking->is_refund == 0)
                                        <span
                                            class="inline-block w-24 text-center  py-1 rounded bg-blue-500 text-white">{{ __('Refunded') }}</span>
                                    @elseif($booking->status == 0)
                                        @if ($is_offline)
                                            <span
                                                class="inline-block w-24 text-center px-2 py-1 rounded bg-yellow-500 text-white">{{ __('Pending') }}</span>
                                        @else
                                            <span
                                                class="inline-block w-24 text-center px-2 py-1 rounded bg-red-500 text-white">{{ __('Failed') }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-2 border border-gray-300">
                                    @php
                                        $timeZone = new DateTimeZone(env('APP_TIMEZONE', 'UTC'));
                                        // Booking date and time slot
                                        $bookingDate = \Carbon\Carbon::parse($booking->booking_date)->format('y-m-d');

                                        $timeSlot = $booking->booking_time;

                                        // Split the time slot into start and end times
                                        [$startTime, $endTime] = explode(' - ', $timeSlot);
                                        // Create DateTime objects for the start time and the current time
                                        $bookingStart = new DateTime("$bookingDate $startTime", $timeZone);
                                        $currentDateTime = new DateTime('now', $timeZone);
                                    @endphp
                                    @if ($currentDateTime < $bookingStart && $booking->status != -1 && $booking->status != 0)
                                        <a href="#" onclick="showModal('{{ $booking->booking_id }}')"
                                            class="inline-block w-20 text-center px-2 py-1 rounded bg-red-500 text-white">
                                            {{ __('Cancel') }}
                                        </a>

                                        <!-- Modal -->
                                        <div id="cancelModal-{{ $booking->booking_id }}"
                                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                            <form
                                                action="{{ route('user.booking.cancel', ['booking_id' => $booking->booking_id]) }}"
                                                method="post"
                                                class="bg-white p-6 rounded-lg shadow-lg w-3/4 md:w-1/2 lg:w-1/3">
                                                @csrf
                                                <!-- Modal Title -->
                                                <h3 class="text-lg font-semibold text-gray-800">{{ __('Are You Sure?') }}
                                                </h3>
                                                <p class="mt-2 text-red-600">
                                                    {{ __('Note: This action cannot be undone and your amount will be refunded as soon as possible.') }}
                                                </p>

                                                {{-- Bank Details --}}
                                                <div class="mt-4 flex flex-col justify-center">
                                                    <div>
                                                        <label class="block mb-2 font-bold text-lg"
                                                            for="notes">{{ __('Bank Details') }}:</label>
                                                        <div class="relative">
                                                            <textarea
                                                                class="w-full p-4 text-sm placeholder-gray-500 border border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                                                                name="bank_details" id="bank_details" placeholder="{{ __('Enter details') }}" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end mt-2 space-x-4">
                                                        <a href="#"
                                                            onclick="closeModal('{{ $booking->booking_id }}')"
                                                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">{{ __('Cancel') }}</a>

                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">{{ __('Send') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
            {{ $my_bookings->links() }} <!-- Laravel's built-in pagination links -->
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        // Show Modal
        function showModal(bookingId) {
            "use strict";

            document.getElementById('cancelModal-' + bookingId).classList.remove('hidden');
        }

        // Close Modal
        function closeModal(bookingId) {
            "use strict";

            document.getElementById('cancelModal-' + bookingId).classList.add('hidden');
        }
    </script>
@endsection
@endsection
