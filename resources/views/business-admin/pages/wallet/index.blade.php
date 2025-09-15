@extends('business-admin.layouts.app')

@php
    $business_id = request()->route()->parameter('business_id');
@endphp

@section('content')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            {{ __('Wallet Balance') }}: <span class="px-1 fw-medium">
                                {{ $currency->symbol }}{{ $wallet_total }}</span>
                        </h2>
                    </div>

                    {{-- Redeem button --}}
                    <div class="col-auto ms-auto d-print-none">
                        <a type="button" href="#" onclick="withdrawReq('{{ $business_id }}'); return false;"
                            class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            {{ __('Withdraw Amount') }}
                        </a>
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

                <div class="alert alert-important alert-info alert-dismissible" role="alert">
                    <p>{{ __('Note: The balance reflects completed services and is calculated at the end of each day.') }}
                    </p>
                </div>

                <div class="row row-deck row-cards">
                    <div class="col-sm-12 col-lg-12">

                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table table-vcenter card-table" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('Withdraw Amount') }}</th>
                                            <th class="text-start">{{ __('Date') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payment_requests as $payment_request)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start">{{ $payment_request->amount }}</td>
                                                <td class="text-start">{{ $payment_request->created_at }}</td>
                                                <td class="text-start">
                                                    @if ($payment_request->status == 0)
                                                        <span class="badge bg-orange">{{ __('Pending') }}</span>
                                                    @else
                                                        <span class="badge bg-green">{{ __('Success') }}</span>
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
        @include('business-admin.includes.footer')
    </div>

    {{-- Withdraw Request modal --}}
    <div class="modal modal-blur fade" id="withdraw-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Enter Details:') }}</div>
                    <div>
                        <form action="{{ route('business-admin.withdraw-request', ['business_id' => $business_id]) }}"
                            method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="amount" class="form-label required">{{ __('Amount:') }}</label>
                                <input type="number" step="0.01" class="form-control" max="{{ $wallet_total }}"
                                    id="amount" name="amount" placeholder="{{ __('Enter Amount') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">{{ __('Note:') }}</label>
                                <input type="text" class="form-control" id="note" name="message"
                                    placeholder="{{ __('Enter a note (optional)') }}">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-danger">{{ __('Send') }}</button>
                            </div>
                        </form>
                    </div>
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

    <script>
        // Withdraw Request
        function withdrawReq(parameter) {
            "use strict";

            $("#withdraw-modal").modal("show");
            var link = document.getElementById("business_id");

        }
    </script>
@endsection
@endsection
