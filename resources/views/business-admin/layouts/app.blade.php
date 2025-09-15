<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @php
        // Settings
        use App\Models\Setting;
        $setting = Setting::where('status', 1)->first();
    @endphp

    @if (isset($setting))
        <!-- Favicon -->
        <link rel="icon" href="{{ asset($setting->favicon) }}" sizes="96x96" type="image/png" />
    @endif

    @php
        $ir = App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'rtl' : 'ltr';
    @endphp

    <!-- CSS files -->
    @if ($ir === 'rtl')
        <link rel="stylesheet" href="{{ asset('assets/css/tabler.rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/tabler-payments.rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.rtl.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/tabler-payments.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.css') }}">

    <!-- Charts -->
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>

    {{-- Custom CSS --}}
    <style>
        .custom-spinner {
            width: 4rem !important;
            height: 4rem !important;
            border-width: 4px !important;
        }
    </style>
    @yield('custom-css')
    <!-- Google Analytics -->
    @if (isset($setting))
        @if ($setting->analytics_id != '' && Cookie::get('laravel_cookie_consent') === '1')
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->analytics_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    "use strict";
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', '{{ $setting->analytics_id }}');
            </script>
        @endif

        @if ($setting->google_tag != '' && Cookie::get('laravel_cookie_consent') === '1')
            <!-- Google Tag Manager -->
            <script>
                (function(w, d, s, l, i) {
                    "use strict";
                    w[l] = w[l] || [];
                    w[l].push({
                        'gtm.start': new Date().getTime(),
                        event: 'gtm.js'
                    });
                    var f = d.getElementsByTagName(s)[0],
                        j = d.createElement(s),
                        dl = l != 'dataLayer' ? '&l=' + l : '';
                    j.async = true;
                    j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                    f.parentNode.insertBefore(j, f);
                })(window, document, 'script', 'dataLayer', '{{ $setting->google_tag }}');
            </script>
            <!-- End Google Tag Manager -->
        @endif
    @endif
</head>

<body class="antialiased" data-bs-theme="{{ Auth::user()->choosed_theme === 'dark' ? 'dark' : 'light' }}">

    {{-- Preloader --}}
    <div class="page page-center preloader-wrapper">
        <div class="container container-slim py-4">
            <div class="text-center">
                <div class="spinner-border spinner-border-sm text-danger custom-spinner" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <div id="wrapper" class="page">
        @php
            $userRole = Auth::user()->role; // Fetch the user's role properly
        @endphp

        @if ($userRole == 2)
            @include('business-admin.includes.banner') {{-- Include the banner only if role is 2 --}}
        @endif

        {{-- Topbar --}}
        @include('business-admin.includes.topbar')

        {{-- Navbar --}}
        @include('business-admin.includes.navbar')

        @php
            $config = App\Models\Configuration::get();
        @endphp

        {{-- Check email verification --}}
        @if ($config[52]->config_value == '1' && Auth::user()->email_verified_at == null)
            <div class="container-xl">
                <div class="mt-3">
                    @include('business-admin.includes.verify')
                </div>
            </div>
        @endif

        {{-- Page Content --}}
        @yield('content')
    </div>

    {{-- Scripts --}}
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/tabler.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dataTables.bootstrap5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/tom-select.base.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Preloader
        $(document).ready(function() {
            "use strict"; 

            $('.preloader-wrapper').fadeOut();
        });
    </script>

    {{-- Custom JS --}}
    @yield('custom-js')
</body>

</html>
