<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site Description -->
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    @isset($meta_title)
        <title>{{ __($meta_title) }}</title>
    @endisset

    @if (isset($setting))
        <!-- Favicon -->
        <link rel="icon" href="{{ asset($setting->favicon) }}" sizes="96x96" type="image/png" />
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

    <!-- Custom Styles -->
    <style>
        body * {
            font-family: "DM Sans", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            letter-spacing: -0.03em;
        }
      
      .custom-spinner {
            width: 4rem;
            height: 4rem;
            border-width: 4px;
            border-top-color: transparent;
        }
    </style>
    @yield('custom-css')

    <!-- Google Recaptcha -->
    {!! htmlScriptTagJsApi() !!}

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

<body class="antialiased bg-body text-body font-body">
  
  
    @if (isset($setting))
        @if ($setting->google_tag != '' && Cookie::get('laravel_cookie_consent') === '1')
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting->google_tag }}"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif
    @endif

    {{-- Page Content --}}
    <div class="" id="app">
        {{-- Header --}}
        @include('website.includes.header')
        @yield('content')
        {{-- Footer --}}
        @include('website.includes.footer')

        {{-- WhatsApp Chatbot --}}
        @if ($config[49]->config_value == '1')
            <a href="https://api.whatsapp.com/send?phone={{ $config[50]->config_value }}&text={{ urlencode($config[51]->config_value) }}"
                class="whatapp-chatbot" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="whatapp-chatbot-icon" width="40" height="40" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-brand-whatsapp">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                    <path
                        d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                </svg>
            </a>
        @endif

        {{-- Cookie consent --}}
        @include('cookie-consent::index')
    </div>

    {{-- Scripts --}}
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/alpinejs.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/smooth-scroll.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/flowbite.min.js') }}"></script>

    <!-- Tawk Chat -->
    @if (isset($setting))
        @if ($setting->tawk_chat_key != '' && Cookie::get('laravel_cookie_consent') === '1')
            <!--Start of Tawk.to Script-->
            <script type="text/javascript">
                var Tawk_API = Tawk_API || {},
                    Tawk_LoadStart = new Date();
                (function() {
                    "use strict";
                    var s1 = document.createElement("script"),
                        s0 = document.getElementsByTagName("script")[0];
                    s1.async = true;
                    s1.src = 'https://embed.tawk.to/{{ $setting->tawk_chat_key }}';
                    s1.charset = 'UTF-8';
                    s1.setAttribute('crossorigin', '*');
                    s0.parentNode.insertBefore(s1, s0);
                })();
            </script>
            <!--End of Tawk.to Script-->
        @endif
    @endif

    <!-- Custom JS -->
    @yield('custom-js')
</body>

</html>
