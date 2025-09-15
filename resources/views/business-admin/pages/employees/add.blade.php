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
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title">
                            {{ __('Add Employee') }}
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
                    {{-- Save Employee --}}
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('business-admin.save.employee', ['business_id' => $business_id]) }}"
                            method="post" class="card" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h4 class="page-title">{{ __('Employee Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            {{-- Employee Name --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Employee Name') }}</label>
                                                    <input type="text" class="form-control" name="business_employee_name"
                                                        placeholder="{{ __('Employee Name') }}" required />
                                                </div>
                                            </div>

                                            {{-- Employee Email --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Employee Email') }}</label>
                                                    <input type="email" class="form-control"
                                                        name="business_employee_email"
                                                        placeholder="{{ __('Employee Email') }}" required
                                                        autocomplete="new-email" />
                                                </div>
                                            </div>

                                            {{-- Employee Phone Number --}}
                                            <div class="col-md-6 col-xl-4">
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label required">{{ __('Employee Phone Number') }}</label>
                                                    <input type="number" class="form-control"
                                                        name="business_employee_phone"
                                                        placeholder="{{ __('Employee Phone Number') }}" required
                                                        autocomplete="new-phone" />
                                                </div>
                                            </div>

                                            {{-- Is Login --}}
                                            <div class="col-md-6 col-xl-12">
                                                <div class="mb-3">
                                                    <div class="form-label">{{ __('Enable Login') }}</div>
                                                    <label class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="is_login"
                                                            name="is_login" onchange="toggleLoginFields()" />
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Login Fields -->
                                            <h4 class="page-title mt-3 mb-3" id="login-field1" style="display: none;">
                                                {{ __('Login Credentials') }}</h4>

                                            {{-- User Name --}}
                                            <div class="col-md-4 col-xl-4" id="login-field2" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('User Name') }}</label>
                                                    <input type="text" class="form-control" name="user_name"
                                                        placeholder="{{ __('User Name') }}" />
                                                </div>
                                            </div>

                                            {{-- User Email --}}
                                            <div class="col-md-4 col-xl-4" id="login-field3" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('User Email') }}</label>
                                                    <input type="text" class="form-control" name="user_email"
                                                        placeholder="{{ __('User Email') }}" />
                                                </div>
                                            </div>

                                            {{-- User Password --}}
                                            <div class="col-md-4 col-xl-4" id="login-field4" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('User Password') }}</label>
                                                    <input type="password" class="form-control" name="user_password"
                                                        placeholder="{{ __('Password') }}" minlength="8"
                                                        autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-plus" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <line x1="12" y1="5" x2="12"
                                                                y2="19"></line>
                                                            <line x1="5" y1="12" x2="19"
                                                                y2="12"></line>
                                                        </svg>
                                                        {{ __('Add') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('business-admin.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    <script>
        // Toggle login fields
        function toggleLoginFields() {
            "use strict";

            const isLogin = document.getElementById('is_login').checked;
            const loginFields = [
                document.getElementById('login-field1'),
                document.getElementById('login-field2'),
                document.getElementById('login-field3'),
                document.getElementById('login-field4')
            ];
            const requiredFields = [
                document.querySelector('input[name="user_name"]'),
                document.querySelector('input[name="user_email"]'),
                document.querySelector('input[name="user_password"]')
            ];

            // Show or hide login fields based on the checkbox state
            loginFields.forEach(field => {
                field.style.display = isLogin ? 'block' : 'none';
            });

            // Add or remove 'required' attribute from input fields
            requiredFields.forEach(field => {
                if (isLogin) {
                    field.setAttribute('required', 'required');
                } else {
                    field.removeAttribute('required');
                }
            });
        }
    </script>
@endsection
@endsection
