@extends('admin.layouts.app')

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
                            {{ __($business->business_name) }}
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

                <div class="row row-deck row-cards mb-5">
                    {{-- Services --}}
                    <div class="col-sm-6 col-lg-6">
                        <div class="card">
                            <div class="card-stamp">
                                <div class="card-stamp-icon bg-red">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-building-community">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M8 9l5 5v7h-5v-4m0 4h-5v-7l5 -5m1 1v-6a1 1 0 0 1 1 -1h10a1 1 0 0 1 1 1v17h-8" />
                                        <path d="M13 7l0 .01" />
                                        <path d="M17 7l0 .01" />
                                        <path d="M17 11l0 .01" />
                                        <path d="M17 15l0 .01" />
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="subheader">{{ __('Services') }}</div>
                                </div>
                                <div class="h1">{{ $service_count }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Employees --}}
                    <div class="col-sm-6 col-lg-6">
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
                                    <div class="subheader">{{ __('Employees') }}</div>
                                </div>
                                <div class="h1">{{ $employee_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Services --}}
                <div class="card-header">
                    <h4 class="page-title">{{ __('Services') }}</h4>
                </div>

                <div class="row row-deck row-cards mb-5 mt-1">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table table-vcenter card-table" id="table1">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('Service Name') }}</th>
                                            <th class="text-start">{{ __('Category') }}</th>
                                            <th class="text-start">{{ __('Created At') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="w-1 text-start">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($business_services as $business_service)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start">{{ __($business_service->business_service_name) }}
                                                </td>
                                                <td class="text-start">{{ __($business_service->business_category_name) }}
                                                </td>
                                                <td class="text-start">{{ $business_service->created_at }}</td>
                                                <td class="text-start">
                                                    @if ($business_service->status == 0)
                                                        <span class="badge bg-red">{{ __('Discontinued') }}</span>
                                                    @else
                                                        <span class="badge bg-green">{{ __('Active') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">

                                                            @if ($business_service->status == 0)
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getBusinessService('{{ $business_service->business_service_id }}'); return false;">{{ __('Activate') }}</a>
                                                            @else
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getBusinessService('{{ $business_service->business_service_id }}'); return false;">{{ __('Deactivate') }}</a>
                                                            @endif
                                                            <a class="dropdown-item" href="#"
                                                                onclick="deleteBusinessService('{{ $business_service->business_service_id }}'); return false;">{{ __('Delete') }}</a>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Users --}}
                <div class="card-header">
                    <h4 class="page-title">{{ __('Users') }}</h4>
                </div>

                <div class="row row-deck row-cards mt-1">
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            <div class="table-responsive px-2 py-2">
                                <table class="table table-vcenter card-table" id="table2">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('#') }}</th>
                                            <th class="text-start">{{ __('User Name') }}</th>
                                            <th class="text-start">{{ __('Email') }}</th>
                                            <th class="text-start">{{ __('Role') }}</th>
                                            <th class="text-start">{{ __('Joined') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="w-1 text-start">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ 1 }}</td>
                                            <td class="text-start">{{ __($master_business_user->name) }}</td>
                                            <td class="text-start">{{ __($master_business_user->email) }}</td>
                                            <td class="text-start">
                                                <span class="badge bg-yellow">{{ __('Business Admin') }}</span>
                                            </td>
                                            <td class="text-start">{{ $master_business_user->created_at }}</td>
                                            <td class="text-start">
                                                @if ($master_business_user->status == 0)
                                                    <span class="badge bg-red">{{ __('Discontinued') }}</span>
                                                @else
                                                    <span class="badge bg-green">{{ __('Active') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="dropdown">
                                                    <button class="btn small-btn dropdown-toggle align-text-top"
                                                        data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                        aria-expanded="false">{{ __('Actions') }}</button>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a class="dropdown-item" href="#"
                                                            onclick="loginBusiness('{{ $master_business_user->user_id }}'); return false;">{{ __('Login') }}</a>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration + 1 }}</td>
                                                <td class="text-start">{{ __($user->name) }}</td>
                                                <td class="text-start">{{ __($user->email) }}</td>
                                                <td class="text-start">
                                                    <span class="badge bg-green">{{ __('User') }}</span>
                                                </td>
                                                <td class="text-start">{{ $user->created_at }}</td>
                                                <td class="text-start">
                                                    @if ($user->status == 0)
                                                        <span class="badge bg-red">{{ __('Discontinued') }}</span>
                                                    @else
                                                        <span class="badge bg-green">{{ __('Active') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="dropdown">
                                                        <button class="btn small-btn dropdown-toggle align-text-top"
                                                            data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                            aria-expanded="false">{{ __('Actions') }}</button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                            <a class="dropdown-item" href="#"
                                                                onclick="loginBusiness('{{ $user->user_id }}'); return false;">{{ __('Login') }}</a>
                                                            @if ($user->status == 0)
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getUser('{{ $user->user_id }}'); return false;">{{ __('Activate') }}</a>
                                                            @else
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="getUser('{{ $user->user_id }}'); return false;">{{ __('Deactivate') }}</a>
                                                            @endif
                                                            <a class="dropdown-item" href="#"
                                                                onclick="deleteUser('{{ $user->user_id }}'); return false;">{{ __('Delete') }}</a>
                                                        </div>

                                                    </span>
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
        @include('admin.includes.footer')
    </div>

    {{-- Login Modal --}}
    <div class="modal modal-blur fade" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will logged out admin and login as business.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="login_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Activation Modal --}}
    <div class="modal modal-blur fade" id="activation-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will active/deactivate this business data.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="activation_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal modal-blur fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">{{ __('Are you sure?') }}</div>
                    <div>{{ __('If you proceed, you will delete this business data.') }}</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-auto"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" id="delete_id">{{ __('Yes, proceed') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    {{-- Datatable --}}
    <script>
        $('#table1 , #table2').DataTable({
            "order": [
                [0, "asc"]
            ]
        });
    </script>

    <script>
        // Login business
        function loginBusiness(parameter) {
            "use strict";

            $("#login-modal").modal("show");
            var link = document.getElementById("login_id");
            link.setAttribute("href", "{{ route('admin.switch.account') }}?user_id=" + parameter);
        }

        // Activate business service
        function getBusinessService(parameter) {
            "use strict";

            console.log(parameter);
            $("#activation-modal").modal("show");
            var link = document.getElementById("activation_id");
            link.setAttribute("href", "{{ route('admin.activation.business-service') }}?business_service_id=" + parameter);
        }

        // Delete business service
        function deleteBusinessService(parameter) {
            "use strict";

            $("#delete-modal").modal("show");
            var link = document.getElementById("delete_id");
            link.setAttribute("href", "{{ route('admin.delete.business-service') }}?business_service_id=" + parameter);
        }

        // Activate user
        function getUser(parameter) {
            "use strict";

            console.log(parameter);
            $("#activation-modal").modal("show");
            var link = document.getElementById("activation_id");
            link.setAttribute("href", "{{ route('admin.activation.user') }}?user_id=" + parameter);
        }

        // Delete user
        function deleteUser(parameter) {
            "use strict";

            $("#delete-modal").modal("show");
            var link = document.getElementById("delete_id");
            link.setAttribute("href", "{{ route('admin.delete.user') }}?user_id=" + parameter);
        }
    </script>
@endsection
@endsection
