@extends('business-admin.layouts.app')

@section('content')
    {{-- Page Content --}}
    <div class="page-wrapper">
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

            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Dashboard') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                {{-- Message --}}
                @if (session()->has('message'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                    <path d="M12 9h.01"></path>
                                    <path d="M11 12h1v4h1"></path>
                                </svg>
                            </div>
                            <div>
                                {!! session('message') !!}
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                    @php
                        session()->forget('message');
                    @endphp
                @endif

                <div class="row row-deck row-cards mb-5">
                    {{-- This Month Income --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-red">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-calendar-stats" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4">
                                            </path>
                                            <path d="M18 14v4h4"></path>
                                            <circle cx="18" cy="18" r="4"></circle>
                                            <path d="M15 3v4"></path>
                                            <path d="M7 3v4"></path>
                                            <path d="M3 11h16"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('This Month Income') }}</div>
                                </div>
                                <div class="h1">{{ $currency->symbol }}{{ number_format($this_month_income, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Today Income --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-red">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-businessplan" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <ellipse cx="16" cy="6" rx="5" ry="3"></ellipse>
                                        <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                        <path d="M5 15v1m0 -8v1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Today Income') }}</div>
                                </div>
                                <div class="h1">{{ $currency->symbol }}{{ number_format($today_income, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Bookings --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-red">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Overall Bookings') }}</div>
                                </div>
                                <div class="h1">{{ $overall_bookings }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Today Bookings --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-red">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 11h6m-3 -3v6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Today Bookings') }}</div>
                                </div>
                                <div class="h1">{{ $today_bookings }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Calendar --}}
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h3>{{ __('Bookings') }}</h3>
                                        </div>
                                    </div>
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--  Bookings Chart --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('Total Bookings Overview') }}</h3>
                                        </div>
                                    </div>
                                    <canvas id="bookings"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Users Chart --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('Total Income Overview') }}</h3>
                                        </div>
                                    </div>
                                    <canvas id="incomes"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('business-admin.includes.footer')
    </div>

    <!-- Event Details Modal -->
    <div class="modal modal-blur fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="eventModalLabel">{{ __('Booking Details') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>{{ __('Customer Name:') }}</strong> <span id="eventCustomer"></span></p>
                    <p><strong>{{ __('Phone:') }}</strong> <span id="eventPhone"></span></p>
                    <p><strong>{{ __('Service:') }}</strong> <span id="eventService"></span></p>
                    <p><strong>{{ __('Employee:') }}</strong> <span id="eventEmployee"></span></p>
                    <p><strong>{{ __('Date:') }}</strong> <span id="eventDate"></span></p>
                    <p><strong>{{ __('Time:') }}</strong> <span id="eventTime"></span></p>
                    <p><strong>{{ __('Price:') }}</strong> <span id="eventCurrency"></span> <span
                            id="eventPrice"></span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                displayEventTime: true,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                events: {!! $bookings !!},
                timeZone: '{{ $time_zone }}',
                eventClick: function(info) {
                    // Prevent default navigation
                    info.jsEvent.preventDefault();

                    $.ajax({
                        url: "{{ route('business-admin.booking.details') }}",
                        data: {
                            booking_id: info.event.extendedProps.booking_id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        success: function(data) {
                            // Populate modal or handle the response
                            $('#eventModal').modal('show');
                            $('#eventCustomer').text(data.customer);
                            $('#eventService').text(data.service);
                            $('#eventEmployee').text(data.employee);
                            $('#eventDate').text(data.date);
                            $('#eventTime').text(data.time);
                            $('#eventPhone').text(data.phone);
                            $('#eventPrice').text(data.price);
                            $('#eventCurrency').text(data.currency);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching event details:', error);
                        }
                    });

                }
            });
            calendar.render();
        });

        const labels = [
            `{{ __('Jan') }}`,
            `{{ __('Feb') }}`,
            `{{ __('Mar') }}`,
            `{{ __('Apr') }}`,
            `{{ __('May') }}`,
            `{{ __('Jun') }}`,
            `{{ __('Jul') }}`,
            `{{ __('Aug') }}`,
            `{{ __('Sep') }}`,
            `{{ __('Oct') }}`,
            `{{ __('Nov') }}`,
            `{{ __('Dec') }}`,
        ];

        const bookingsData = {
            labels: labels,
            datasets: [{
                label: `{{ __('Total Bookings') }}`,
                backgroundColor: 'rgb(246, 149, 69)',
                borderColor: 'rgb(246, 149, 69)',
                data: [{{ $monthBookings }}],
            }]
        };

        const incomesData = {
            labels: labels,
            datasets: [{
                label: `{{ __('Total Income') }}`,
                backgroundColor: 'rgb(246, 149, 69)',
                borderColor: 'rgb(246, 149, 69)',
                data: [{{ $monthIncome }}],
            }]
        };


        const bookingsConfig = {
            type: 'line',
            data: bookingsData,
            options: {
                animation: true,
                scales: {
                    y: {
                        ticks: {
                            stepSize: 1,
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                weight: 600
                            }
                        }
                    }
                }
            }
        };

        const incomesConfig = {
            type: 'line',
            data: incomesData,
            options: {
                animation: true,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                weight: 600
                            }
                        }
                    }
                }
            }
        };

        const bookingsChart = new Chart(document.getElementById('bookings'), bookingsConfig);
        const incomesChart = new Chart(document.getElementById('incomes'), incomesConfig);
    </script>
@endsection
@endsection
