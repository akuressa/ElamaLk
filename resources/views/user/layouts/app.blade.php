@php

    use Artesaos\SEOTools\Facades\JsonLd;
    use Artesaos\SEOTools\Facades\OpenGraph;
    use Artesaos\SEOTools\Facades\SEOMeta;
    use Artesaos\SEOTools\Facades\SEOTools;
    use App\Models\Page;
    use App\Models\Setting;
    // Seo Tools
    $page = Page::where('status', 1)->first();
    $setting = Setting::where('status', 1)->first();

   

    // Seo Tools
    SEOTools::setTitle($page->meta_title);
    SEOTools::setDescription($page->meta_description);

    SEOMeta::setTitle($page->meta_title);
    SEOMeta::setDescription($page->meta_description);
    SEOMeta::addMeta('article:section', $page->page_name . ' - ' . $page->meta_description, 'property');
    SEOMeta::addKeyword([$page->meta_keywords]);

    OpenGraph::setTitle($page->meta_title);
    OpenGraph::setDescription($page->meta_description);
    OpenGraph::setUrl(URL::full());
    OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

    JsonLd::setTitle($page->meta_title);
    JsonLd::setDescription($page->meta_description);
    JsonLd::addImage(asset($setting->site_logo));
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ App::isLocale('ar') || App::isLocale('ur') || App::isLocale('he') || App::isLocale('fa') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Site Description -->
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}


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

    {{-- jQuery --}}
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
    {{--Flowbite --}}
    <script type="text/javascript" src="{{ asset('assets/js/flowbite.min.js') }}"></script>

    {{-- Custom Styles --}}
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

    <!-- Custom Styles -->
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

        @if ($setting->google_tag != '' && Cookie::get('laravel_cookie_consent'))
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

<body class="antialiased bg-body text-body font-body flex flex-col justify-center items-center min-h-screen">

    {{-- Preloader --}}
    <div class="flex items-center justify-center min-h-screen preloader-wrapper">
        <div class="max-w-xs mx-auto py-4">
            <div class="text-center">
                <div class="animate-spin border-4 border-t-4 border-red-500 rounded-full custom-spinner" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    @if (isset($setting))
        @if ($setting->google_tag != '' && Cookie::get('laravel_cookie_consent'))
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting->google_tag }}"
                    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif
    @endif

    {{-- Header --}}
    @include('website.includes.header')

    <!-- Centering container -->
    <div class="flex container" x-data="{ isSidebarOpen: false }" id="app">
        <!-- Sidebar -->
        <aside :class="{ 'block': isSidebarOpen, 'hidden': !isSidebarOpen }"
            class="w-64 bg-{{ $config[11]->config_value }}-300 z-50 fixed top-0 left-0 bottom-0 h-full transition-transform duration-200 lg:relative lg:top-auto lg:left-auto lg:bottom-auto lg:h-auto lg:w-1/4 lg:block">
            <div class="p-5 relative">
                <button @click="isSidebarOpen = !isSidebarOpen"
                    class="focus:outline-none absolute right-4 top-4 lg:hidden">
                    <svg width="22" height="22" viewbox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
                <div class="h-16">
                    <a href="{{ route('user.my-bookings') }}">
                        <img src="{{ asset($setting->favicon) }}" alt="{{ $setting->site_name }}" class="h-16 w-auto" />
                    </a>
                </div>
                <nav class="space-y-4 mt-6">
                    <ul>
                        <li onclick="location.href='{{ route('user.my-bookings') }}'"
                            class="p-2 flex items-center font-medium cursor-pointer hover:bg-{{ $config[11]->config_value }}-200 {{ request()->is('user/my-bookings') ? 'bg-' . $config[11]->config_value . '-200' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-ticket-check mx-2">
                                <path
                                    d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>{{ __('My Bookings') }}
                        </li>
                        <li onclick="location.href='{{ route('user.my-transactions') }}'"
                            class="p-2 flex items-center font-medium cursor-pointer hover:bg-{{ $config[11]->config_value }}-200 {{ request()->is('user/my-transactions') ? 'bg-' . $config[11]->config_value . '-200' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-cash-register mx-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M21 15h-2.5c-.398 0 -.779 .158 -1.061 .439c-.281 .281 -.439 .663 -.439 1.061c0 .398 .158 .779 .439 1.061c.281 .281 .663 .439 1.061 .439h1c.398 0 .779 .158 1.061 .439c.281 .281 .439 .663 .439 1.061c0 .398 -.158 .779 -.439 1.061c-.281 .281 -.663 .439 -1.061 .439h-2.5" />
                                <path d="M19 21v1m0 -8v1" />
                                <path
                                    d="M13 21h-7c-.53 0 -1.039 -.211 -1.414 -.586c-.375 -.375 -.586 -.884 -.586 -1.414v-10c0 -.53 .211 -1.039 .586 -1.414c.375 -.375 .884 -.586 1.414 -.586h2m12 3.12v-1.12c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2" />
                                <path
                                    d="M16 10v-6c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-4c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414v6m8 0h-8m8 0h1m-9 0h-1" />
                                <path d="M8 14v.01" />
                                <path d="M8 17v.01" />
                                <path d="M12 13.99v.01" />
                                <path d="M12 17v.01" />
                            </svg>{{ __('My Transactions') }}
                        </li>
                        <li onclick="location.href='{{ route('user.account.index') }}'"
                            class="p-2 flex items-center font-medium cursor-pointer hover:bg-{{ $config[11]->config_value }}-200 {{ request()->is('user/account') ? 'bg-' . $config[11]->config_value . '-200' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-circle-user mx-2">
                                <circle cx="12" cy="12" r="10" />
                                <circle cx="12" cy="10" r="3" />
                                <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
                            </svg>{{ __('Account') }}
                        </li>
                        <li onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="p-2 flex items-center font-medium cursor-pointer hover:bg-{{ $config[11]->config_value }}-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-log-out mx-2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>{{ __('Logout') }}
                        </li>

                        {{-- Logout Form --}}
                        <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col w-screen">
            <div x-on:click="isSidebarOpen = !isSidebarOpen" :class="{ 'block': isSidebarOpen, 'hidden': !isSidebarOpen }" class="fixed inset-0 bg-white opacity-90"></div>
            <!-- Internal Header -->
            <header class="bg-{{ $config[11]->config_value }}-600 text-white p-4 flex justify-end items-center">
                <div class="lg:hidden">
                    <button @click="isSidebarOpen = !isSidebarOpen" class="text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <div class="text-xl font-semibold hidden lg:flex justify-between items-center w-full px-6">
                    <div class="flex justify-start">
                        {{ __($title) }}
                    </div>
                    <div class="flex items-center">
                        <div class="flex flex-col text-right mr-4">
                            <h4 class="font-semibold">{{ __('User') }}</h4>
                            <p class="text-sm">{{ Auth::user()->name }}</p>
                        </div>
                        <img src="{{ Auth::user()->profile_image == null ? asset('images/profile.png') : asset(Auth::user()->profile_image) }}"
                            alt="{{ Auth::user()->name }}" class="h-12 w-12 rounded-full object-cover" />
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 p-2 lg:p-6 overflow-auto">
                @php
                    $config = App\Models\Configuration::get();
                @endphp

                {{-- Check email verification --}}
                @if ($config[52]->config_value == '1' && Auth::user()->email_verified_at == null)
                    <div class="container-xl">
                        <div class="mt-2">
                            @include('user.includes.verify')
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>

            {{-- WhatsApp Chatbot --}}
            @if ($config[49]->config_value == '1')
                <a href="https://api.whatsapp.com/send?phone={{ $config[50]->config_value }}&text={{ urlencode($config[51]->config_value) }}"
                    class="whatapp-chatbot" target="_blank">
                    <i class="fab fa-whatsapp whatapp-chatbot-icon"></i>
                </a>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    @include('website.includes.footer')

    {{-- Scripts --}}
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/alpinejs.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/smooth-scroll.js') }}"></script>

    {{-- Animation --}}
    <script>
        // Preloader
        $(document).ready(function() {
            "use strict";
            $('.preloader-wrapper').fadeOut();
        });
    </script>

    <!-- Tawk Chat -->
    @if (isset($setting))
        @if ($setting->tawk_chat_key != '' && Cookie::get('laravel_cookie_consent'))
            <!-- Start of Tawk.to Script -->
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
            <!-- End of Tawk.to Script -->
        @endif
    @endif

    <!-- Custom JS -->
    @yield('custom-js')
</body>

</html>
