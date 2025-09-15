@extends('admin.layouts.app')

{{-- Custom CSS & JS --}}
@section('custom-css')
    {{-- Style --}}
    <style>
        .custom-pagination {
            position: absolute;
            bottom: 0;
            width: 97%;
        }

        .card-footer.d-flex.align-items-center {
            padding: 21px 0px;
        }

        p {
            font-size: 13px !important;
        }
    </style>
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
                            {{ __('View Customer') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    {{-- User details --}}
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="card">
                                <div class="card-body p-4 text-center">
                                    <span class="avatar avatar-xl bg-white mb-3"
                                        style="background-image: url({{ $user_details->profile_image == '' ? asset('images/profile.png') : $user_details->profile_image }})"></span>
                                    <h3 class="m-0 mb-1 text-center">{{ $user_details->name }}</h3>
                                    <div class="text-muted">
                                        {{ $user_details->email == '' ? __('Not Available') : $user_details->email }}</div>
                                    <div class="mt-3">
                                        <span
                                            class="badge bg-green text-white">{{ $user_details->role_id == 2 ? __('Customer') : '' }}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <a href="mailto:{{ $user_details->email == '' ? __('Not Available') : $user_details->email }}"
                                        class="card-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-muted" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <rect x="3" y="5" width="18" height="14" rx="2" />
                                            <polyline points="3 7 12 13 21 7" />
                                        </svg>
                                        {{ __('Email') }}</a>
                                    <a href="#" class="card-btn" id="login"
                                        onclick="loginUser('{{ $user_details->user_id }}'); return false;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                            <path d="M20 12h-13l3 -3m0 6l-3 -3" />
                                        </svg>
                                        {{ __('Login via Admin') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>

    {{-- User login modal --}}
    <div class="modal modal-blur fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure login into the user?') }}</div>
                    <div class="text-muted">{{ __('Note : If you proceed, you will lose your admin session.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a href="{{ route('admin.switch.account') }}?user_id={{ $user_details->user_id }}" target="_blank"
                        class="btn btn-danger">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        // Login user
        function loginUser(parameter) {
            "use strict";

            $("#login-modal").modal("show");
        }
    </script>
@endsection
@endsection
