@extends('business.layouts.app')

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
                    {{-- Overall Businesses --}}
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-red">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-buildings">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 21v-15c0 -1 1 -2 2 -2h5c1 0 2 1 2 2v15" />
                                            <path d="M16 8h2c1 0 2 1 2 2v11" />
                                            <path d="M3 21h18" />
                                            <path d="M10 12v0" />
                                            <path d="M10 16v0" />
                                            <path d="M10 8v0" />
                                            <path d="M7 12v0" />
                                            <path d="M7 16v0" />
                                            <path d="M7 8v0" />
                                            <path d="M17 12v0" />
                                            <path d="M17 16v0" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Overall Businesses') }}</div>
                                </div>
                                <div class="h1">{{ $overall_businesses }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Services --}}
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-red">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-settings-star">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M10.325 19.683a1.723 1.723 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572a1.67 1.67 0 0 1 1.106 .831" />
                                        <path d="M14.89 11.195a3.001 3.001 0 1 0 -4.457 3.364" />
                                        <path
                                            d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Overall Services') }}</div>
                                </div>
                                <div class="h1">{{ $overall_services }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Employees --}}
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-red">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
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
                                    <div class="subheader">{{ __('Overall Employees') }}</div>
                                </div>
                                <div class="h1">{{ $overall_employees }}</div>
                            </div>
                        </div>
                    </div>

                    {{--  Overall Bookings --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('Bookings Overview') }}</h3>
                                        </div>
                                    </div>
                                    <canvas id="bookings"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Transactions --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('Transactions Overview') }}</h3>
                                        </div>
                                    </div>
                                    <canvas id="transactions"></canvas>
                                </div>
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
        "use strict";
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
                label: `{{ __('Overall Bookings') }}`,
                backgroundColor: 'rgb(220,20,60)',
                borderColor: 'rgb(220,20,60)',
                data: [{{ $overall_bookings }}],
            }]
        };

        const transactionsData = {
            labels: labels,
            datasets: [{
                label: `{{ __('Overall Transactions') }}`,
                backgroundColor: 'rgb(220,20,60)',
                borderColor: 'rgb(220,20,60)',
                data: [{{ $overall_transactions }}],
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

        const transactionsConfig = {
            type: 'line',
            data: transactionsData,
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
        const transactionsChart = new Chart(document.getElementById('transactions'), transactionsConfig);
    </script>
@endsection
@endsection
