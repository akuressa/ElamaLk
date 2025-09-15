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
                            {{ __('Account Details') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    {{-- Edit --}}
                    <div class="col-sm-12 col-lg-12">

                        <form action="{{ route('admin.update.account') }}" method="post" enctype="multipart/form-data" class="card">
                            @csrf

                            <div class="card-body">
                                {{-- Error --}}
                                @if ($errors->any())
                                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                                        <div class="d-flex">
                                            @foreach ($errors->all() as $error)
                                                <div>
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                                    </div>
                                @endif

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
                                            {{-- Profile Image --}}
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <div class="form-label required">{{ __('Profile Picture') }} <span
                                                            class='text-muted h6'>({{ __('Recommended : 160 x 160 pixels') }})</span>
                                                    </div>
                                                    <input type="file" class="form-control" name="profile_picture"
                                                        placeholder="{{ __('Profile Picture') }}"
                                                        accept=".jpeg,.jpg,.png,.gif,.svg" />
                                                </div>
                                            </div>

                                            {{-- Name --}}
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Name') }}</label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="{{ __('Name') }}"
                                                        value="{{ $account_details->name }}" required />
                                                </div>
                                            </div>

                                            {{-- Email --}}
                                            <div class="col-md-4 col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label required">{{ __('Email') }}</label>
                                                    <input type="text" class="form-control" name="email"
                                                        placeholder="{{ __('Email') }}"
                                                        value="{{ $account_details->email }}" required />
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
                                                        {{ __('Update') }}
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
