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
                            {{ __('Pages') }}
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
                                            <th class="text-start">{{ __('Page Name') }}</th>
                                            <th class="text-start">{{ __('Slug') }}</th>
                                            <th class="text-start">{{ __('Status') }}</th>
                                            <th class="w-1 text-start">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pages as $page)
                                            <tr>
                                                <td class="text-start">{{ $loop->iteration }}</td>
                                                <td class="text-start text-capitalize">
                                                    {{ __(str_replace('-', ' ', $page->page_name)) }}</td>
                                                <td class="text-start">
                                                    {{-- Check home page --}}
                                                    @if ($page->page_slug == 'home')
                                                        <a href="{{ url('/') }}" target="_blank"
                                                            rel="noopener noreferrer">/</a>
                                                    @else
                                                        <a href="{{ url($page->page_slug) }}" target="_blank"
                                                            rel="noopener noreferrer">/{{ __($page->page_slug) }}</a>
                                                    @endif
                                                </td>
                                                <td class="text-start">
                                                    @if ($page->status == 0)
                                                        <span class="badge bg-red">{{ __('In Active') }}</span>
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
                                                            @if ($page->page_slug != 'home')
                                                                @if ($page->status == 1)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.page.status', ['page_name' => $page->page_name, 'status' => 0]) }}">{{ __('Disable') }}</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.page.status', ['page_name' => $page->page_name, 'status' => 1]) }}">{{ __('Enable') }}</a>
                                                                @endif
                                                            @endif
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.edit.page', ['page_name' => $page->page_name]) }}">{{ __('Edit') }}</a>
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

    {{-- Get page --}}
    <script>
        function getPage(parameter) {
            "use strict";
            $("#status-modal").modal("show");
            var link = document.getElementById("user_id");
            link.getAttribute("href");
            link.setAttribute("href", "/admin/update-status?id=" + parameter);
        }
    </script>
@endsection
@endsection
