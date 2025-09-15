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
                            {{ __('Change Password') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    {{-- Change Password --}}
                    <div class="col-sm-12 col-lg-12">
                        <form action="{{ route('admin.update.password') }}" method="post" class="card">
                            @csrf
                            <div class="card-body">

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

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            {{-- New Password --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('New Password') }}</label>
                                                    <input type="password" class="form-control" name="new_password"
                                                        placeholder="{{ __('New Password') }}" required />
                                                </div>
                                            </div>

                                            {{-- Confirm Password --}}
                                            <div class="col-md-6 col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Confirm Password') }}</label>
                                                    <input type="password" class="form-control" name="confirm_password"
                                                        placeholder="{{ __('Confirm Password') }}" required />
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon icon-tabler icon-tabler-edit" width="24"
                                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3">
                                                            </path>
                                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3">
                                                            </path>
                                                            <line x1="16" y1="5" x2="19"
                                                                y2="8"></line>
                                                        </svg>
                                                        {{ __('Change Password') }}
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
        @include('admin.includes.footer')
    </div>
@endsection
