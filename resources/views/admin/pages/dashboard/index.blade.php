@extends('admin.layouts.app')

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

                    {{-- Overall Users --}}
                    <div class="col-sm-6 col-lg-3">
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
                                    <div class="subheader">{{ __('Overall Users') }}</div>
                                </div>
                                <div class="h1">{{ $overall_users }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Today Users --}}
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
                                    <div class="subheader">{{ __('Today Users') }}</div>
                                </div>
                                <div class="h1">{{ $today_users }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Booking Transactions --}}
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
                                    <div class="subheader">{{ __('Overall Booking Transactions') }}</div>
                                </div>
                                <div class="h1">{{ $currency->symbol }}{{ number_format($booking_transactions, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- This Month Booking Transactions --}}
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
                                    <div class="subheader">{{ __('This Month Booking Transactions') }}</div>
                                </div>
                                <div class="h1">
                                    {{ $currency->symbol }}{{ number_format($this_month_booking_transactions, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Wallet Transactions --}}
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-red">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-businessplan" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <ellipse cx="16" cy="6" rx="5" ry="3">
                                            </ellipse>
                                            <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                            <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                            <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                            <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                            <path d="M5 15v1m0 -8v1"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Overall Wallet Transactions') }}</div>
                                </div>
                                <div class="h1">{{ $currency->symbol }}{{ number_format($wallet_transactions, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- This Month Wallet Transactions --}}
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
                                    <div class="subheader">{{ __('This Month Wallet Transactions') }}</div>
                                </div>
                                <div class="h1">
                                    {{ $currency->symbol }}{{ number_format($this_month_wallet_transactions, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    {{--  Sales Chart --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="row">
                                        <div class="col-9">
                                            <h3>{{ __('Total Sales Overview') }}</h3>
                                        </div>
                                    </div>
                                    <canvas id="sales"></canvas>
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
                                            <h3>{{ __('New Users Overview') }}</h3>
                                        </div>
                                    </div>
                                    <canvas id="users"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
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

        const salesData = {
            labels: labels,
            datasets: [{
                label: `{{ __('Total Sales') }}`,
                backgroundColor: 'rgb(220,20,60)',
                borderColor: 'rgb(220,20,60)',
                data: [{{ $monthIncome }}],
            }]
        };

        const usersData = {
            labels: labels,
            datasets: [{
                label: `{{ __('New Users') }}`,
                backgroundColor: 'rgb(220,20,60)',
                borderColor: 'rgb(220,20,60)',
                data: [{{ $monthUsers }}],
            }]
        };

        const salesConfig = {
            type: 'line',
            data: salesData,
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

        const usersConfig = {
            type: 'line',
            data: usersData,
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
        const salesChart = new Chart(document.getElementById('sales'), salesConfig);
        const usersChart = new Chart(document.getElementById('users'), usersConfig);
    </script>
@endsection
@endsection
