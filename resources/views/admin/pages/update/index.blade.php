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
                            {{ __('License') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                {{-- Failed --}}
                @if(Session::has("failed"))
                <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                    <div class="d-flex">
                        <div>
                            {{Session::get('failed')}}
                        </div>
                    </div>
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif
    
                {{-- Success --}}
                @if(Session::has("success"))
                <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                    <div class="d-flex">
                        <div>
                            {{Session::get('success')}}
                        </div>
                    </div>
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif
                
                <div class="row row-cards">
                    {{-- Check update --}}
                    <div class="col-sm-12 col-lg-8">
                        <div class="card">
                            <form action="{{ route('admin.check.update') }}" method="post" class="card">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        {{-- License --}}
                                        <div class="col-md-6 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Envato Purchase Code')
                                                    }}</label>
                                                <input type="text" class="form-control" name="purchase_code"
                                                    placeholder="{{ __('Envato Purchase Code') }}..."
                                                    value="{{ $purchase_code }}" required>
                                                <small class="form-hint">
                                                    <p><a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
                                                            target="_blank">{{ __("Where is my purchase code?")
                                                            }}</a>
                                                    </p>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xl-12 mt-2">
                                            <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-refresh" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                                                </svg>
                                                {{ __('Check update') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
    
                            </form>
                        </div>
    
    
                        {{-- Check response --}}
                        @if (isset($response))
                        <div class="col-sm-12 col-lg-12 p-2 mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="row">
                                            <div class="alert alert-success bg-white">
                                                <h1 class="display-5 ">{{ $response['version'] }}</h1>
                                                <p class="mb-3 h1">{{ $response['message'] }}</p>
                                                @if ($response['update'] == true)
                                                <p class="text-dark mb-4">{!! str_replace("\n", "<br>", $response['notes']) !!}</p>
                                                <p>{{ __('IMPORTANT: Before starting this process, we recommend you to take a backup of your files.')}}</p>
                                                @endif
                                            </div>
    
                                            {{-- Check update --}}
                                            @if ($response['update'] == true)
                                            <form action="{{ route('admin.update.code') }}" method="post">
                                                @csrf
                                                <input type="hidden" class="form-control" name="app_version"
                                                    value="{{ $response['version'] }}">
                                                <div class="col-md-12 col-xl-12">
                                                    <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                        {{ __('Install') }}
                                                    </button>
                                                </div>
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
    
                    <div class="col-sm-12 col-lg-4 d-block">
                        <img src="{{ asset('images/piracy.png') }}" alt="Piracy">

                        {{-- Check regular license --}}
                        @if ($config[13]->config_value == '0')
                            <a href="https://codecanyon.net/cart/configure_before_adding/43406963?license=extended&ref=nativecode&size=source"
                                target="_blank" rel="noopener noreferrer">
                                <img class="mt-3" src="{{ asset('images/admin/upgrade-to-extended-license.png') }}"
                                    alt="Upgrade to Extended License">
                            </a>
                        @endif
                        @if ($config[13]->config_value == '1')
                            <a href="https://codecanyon.net/user/nativecode" target="_blank" rel="noopener noreferrer">
                                <img class="mt-3" src="{{ asset('images/admin/get-support.png') }}" alt="Get Support">
                            </a>
                        @endif
                    </div>
    
                </div>
            </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>
@endsection
