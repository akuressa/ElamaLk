@extends('business-admin.layouts.app')


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
                            {{ __('My Account') }}
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
                            <div class="card">
                                {{-- Account Details --}}
                                <div class="card-body p-4 text-center">
                                    <span class="avatar avatar-xl bg-white mb-3">
                                        <img src="{{ asset($user->profile_image == null ? 'images/profile.png' : $user->profile_image) }}"
                                            alt="{{ $user->name }}">
                                    </span>
                                    <h3 class="m-0 mb-1">{{ $user->name }}</h3>
                                    <div class="text-muted">
                                        {{ $user->name == '' ? __('Not Available') : $user->email }}
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-success text-white">{{ __('Business Admin') }}</span>
                                    </div>
                                </div>
                                {{-- Actions --}}
                                <div class="d-flex">
                                    <a href="{{ route('business-admin.edit.account', $user->business_id) }}"
                                        class="card-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler me-2 icon-tabler-edit" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                            <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                            <line x1="16" y1="5" x2="19" y2="8"></line>
                                        </svg>
                                        {{ __('Edit') }}</a>
                                    <a href="{{ route('business-admin.change.password', $user->business_id) }}"
                                        class="card-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler me-2 icon-tabler-key" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <circle cx="8" cy="15" r="4"></circle>
                                            <line x1="10.85" y1="12.15" x2="19" y2="4"></line>
                                            <line x1="18" y1="5" x2="20" y2="7"></line>
                                            <line x1="15" y1="8" x2="17" y2="10"></line>
                                        </svg>
                                        {{ __('Change Password') }}</a>
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
@endsection
