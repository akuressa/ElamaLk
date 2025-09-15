@extends('business-admin.layouts.app')

@php
    $business_id = request()->route()->parameter('business_id');
@endphp

{{-- Custom JS --}}
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}">
@endsection

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
                            {{ __('Bookings') }}
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
                                            <th class="text-start">{{ __('Name') }}</th>
                                            <th class="text-start">{{ __('Email') }}</th>
                                            <th class="text-start">{{ __('Phone') }}</th>
                                            <th class="text-start">{{ __('Service') }}</th>
                                            <th class="text-start">{{ __('Employee') }}</th>
                                            <th class="text-start">{{ __('Date') }}</th>
                                            <th class="text-start">{{ __('Time') }}</th>
                                            <th class="text-start">{{ __('Notes') }}</th>
                                            <th class="text-start">{{ __('Price') }}</th>
                                            <th class="text-start">{{ __('Payment Gateway') }}</th>
                                            <th class="text-start w-1">{{ __('Wallet Settlement') }}</th>
                                            <th class="text-start w-1">{{ __('Booking Status') }}</th>
                                            <th class="text-start w-1">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start">{{ $booking->name }}
                                                </td>
                                                <td class="text-start">{{ $booking->email }}
                                                </td>
                                                <td class="text-start">{{ $booking->phone_number }}
                                                </td>
                                                <td class="text-start">{{ __($booking->business_service_name) }}
                                                </td>
                                                <td class="text-start">{{ $booking->business_employee_name }}
                                                </td>
                                                <td class="text-start">
                                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-y') }}
                                                </td>
                                                <td class="text-start">{{ $booking->booking_time }}
                                                </td>
                                                <td class="text-start text-blue" style="max-width: 100px;">
                                                    <span class="text-truncate" style="cursor: pointer;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#notes-modal-{{ $booking->id }}">
                                                        {{ Str::limit($booking->notes ?? '-', 15) }}
                                                    </span>
                                                </td>
                                                <td class="text-start">{{ $booking->transaction_total }}
                                                </td>
                                                <td class="text-start">{{ __($booking->payment_gateway_name) }}
                                                </td>
                                                <td class="text-center">
                                                    @if (
                                                        $booking->status == 1 &&
                                                            $booking->transaction_status == 'completed' &&
                                                            $booking->booking_date < \Carbon\Carbon::now()->format('Y-m-d'))
                                                        <span class="badge bg-green">{{ __('Success') }}</span>
                                                    @elseif($booking->status == -1)
                                                        <span class="badge bg-red">{{ __('Cancelled') }}</span>
                                                    @else
                                                        <span class="badge bg-orange">{{ __('Pending') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($booking->status == -1)
                                                        <span class="badge bg-red">{{ __('Cancelled') }}</span>
                                                    @elseif ($booking->status == 0)
                                                        <span class="badge bg-orange">{{ __('Pending') }}</span>
                                                    @elseif ($booking->status == 1)
                                                        <span class="badge bg-green">{{ __('Success') }}</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $timeZone = new DateTimeZone(env('APP_TIMEZONE', 'UTC'));
                                                    // Booking date and time slot
                                                    $bookingDate = \Carbon\Carbon::parse(
                                                        $booking->booking_date,
                                                    )->format('y-m-d');

                                                    $timeSlot = $booking->booking_time;

                                                    // Split the time slot into start and end times
                                                    [$startTime, $endTime] = explode(' - ', $timeSlot);
                                                    // Create DateTime objects for the start time and the current time
                                                    $bookingStart = new DateTime("$bookingDate $startTime", $timeZone);
                                                    $currentDateTime = new DateTime('now', $timeZone);
                                                @endphp
                                                @if ($currentDateTime < $bookingStart && $booking->status != -1)
                                                    <td class="text-center">
                                                        <span class="dropdown">
                                                            <button class="btn small-btn dropdown-toggle align-text-top"
                                                                data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                                aria-expanded="false">{{ __('Actions') }}</button>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">

                                                                <a class="dropdown-item" href="#"
                                                                    onclick="rescheduleReq('{{ $booking->id }}'); return false;">
                                                                    {{ __('Reschedule') }}
                                                                </a>
                                                                <a href="#"
                                                                    onclick="cancelBooking('{{ $business_id }}', '{{ $booking->booking_id }}')"
                                                                    class="dropdown-item">
                                                                    {{ __('Cancel') }}
                                                                </a>

                                                            </div>
                                                        </span>
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        <span class="badge bg-red">{{ __('Closed') }}</span>
                                                    </td>
                                                @endif
                                            </tr>

                                            <!-- Notes Modal for each booking -->
                                            <div class="modal modal-blur fade" id="notes-modal-{{ $booking->id }}"
                                                tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body"
                                                            style="max-height: 400px; overflow-y: auto;">
                                                            <div class="modal-title">
                                                                {{ __('Notes from ') . $booking->name }}</div>
                                                            <div>{{ $booking->notes ?? '-' }}</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Reschedule Booking Modal --}}
                                            <div class="modal modal-blur fade" id="reshedule-modal-{{ $booking->id }}"
                                                tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="modal-title">{{ __('Reschedule Booking') }}</div>
                                                            <div>
                                                                <p class="text-red">
                                                                    {{ __('Note: Please confirm your customer that you have rescheduled your appointment') }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <form
                                                                    action="{{ route('business-admin.reschedule.booking', ['business_id' => $business_id, 'booking_id' => $booking->booking_id]) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    {{-- Date --}}
                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="form-label">{{ __('Select Date') }}</label>
                                                                        <input type="text" class="form-control"
                                                                            id="date"
                                                                            placeholder="{{ __('Pick a Date') }}"
                                                                            name="date"
                                                                            onchange="fetchSlots(this.value, '{{ $booking->business_service_id }}', '{{ $booking->business_employee_id }}')"
                                                                            data-input>
                                                                    </div>

                                                                    {{-- Slots --}}
                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="form-label">{{ __('Time Slots') }}</label>
                                                                        <select class="tomselected form-select"
                                                                            name="time_slot" id="time_slot">
                                                                        </select>
                                                                    </div>

                                                                    <div class="d-flex justify-content-between">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">{{ __('Reschedule') }}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
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
        @include('business-admin.includes.footer')
    </div>


    {{-- Cancel Modal --}}
    <div class="modal modal-blur fade" id="cancel-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div class="text-red">
                        {{ __('Note: This action cannot be undone kindly inform your customer that you have cancelled their appointment.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="cancel_booking_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    {{-- Flatpickr --}}
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>

    {{-- Datatable --}}
    <script>
        $('#table').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
    </script>

    {{-- Reschedule Booking --}}
    <script>
        function rescheduleReq(parameter) {
            "use strict";

            $("#reshedule-modal-" + parameter).modal("show");
        }

        // Cancel Booking
        function cancelBooking(businessId, bookingId) {
            "use strict";

            $("#cancel-modal").modal("show");

            // Get the link element
            var link = document.getElementById("cancel_booking_id");

            // Replace the parameters in the URL with the actual values
            var url =
                "{{ route('business-admin.cancel.booking', ['business_id' => '__business_id__', 'booking_id' => '__booking_id__']) }}";
            url = url.replace('__business_id__', businessId).replace('__booking_id__', bookingId);

            // Set the href attribute of the link element
            link.setAttribute("href", url);
        }

        // Fetch slots
        document.addEventListener('DOMContentLoaded', function() {
            "use strict";

            flatpickr("#date", {
                dateFormat: "Y-m-d",
            });

            // Initialize Tom Select only once
            const timeSlotSelect = new TomSelect("#time_slot", {
                create: false,
                maxOptions: null,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });

        // Fetch slots
        function fetchSlots(value, serviceId, employeeId) {
            "use strict";

            const date = new Date(value);
            const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            const dayName = dayNames[date.getDay()];

            $.ajax({
                url: "{{ route('business-admin.fetch.slots') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                dataType: "json",
                data: {
                    date: value,
                    dayName: dayName,
                    serviceId: serviceId,
                    employeeId: employeeId
                },
                success: function(response) {
                    const timeSlots = response.available_slots;
                    const timeSlotSelectElement = document.getElementById('time_slot');

                    // Clear current options
                    timeSlotSelectElement.innerHTML =
                        `<option value="">{{ __('Select a Time Slot') }}</option>`;

                    // Add new options
                    timeSlots.forEach(function(slot) {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSlotSelectElement.appendChild(option);
                    });

                    // Refresh the Tom Select instance to display the new options
                    timeSlotSelectElement.tomselect.sync();
                }
            });
        }
    </script>
@endsection
@endsection
