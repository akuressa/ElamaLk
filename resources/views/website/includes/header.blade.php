@php
    use App\Models\Page;
    $pages = Page::get();
@endphp

<style>
    /* Default: Hide the menu */
    .mobile-menu {
        display: none;
    }

    /* Hide scrollbar but allow scrolling */
    .scrollbar-hidden {
        overflow-y: scroll;
        /* Enable vertical scrolling */
        scrollbar-width: none;
        /* For Firefox */
        -ms-overflow-style: none;
        /* For Internet Explorer */
    }

    /* For WebKit-based browsers (Chrome, Safari) */
    .scrollbar-hidden::-webkit-scrollbar {
        display: none;
    }

    /* Show the menu between 1024px and 1435px */
    @media screen and (min-width: 1024px) and (max-width: 1435px) {
        .mobile-menu {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 66.67%;
            /* 4/6 width */
            max-width: 320px;
            /* Tailwind sm:max-w-xs equivalent */
            z-index: 50;
            background: white;
        }

        .mobile-menu-backdrop {
            position: fixed;
            inset: 0;
            /* shorthand for top: 0; left: 0; bottom: 0; right: 0 */
            background: rgba(31, 41, 55, 0.8);
            /* bg-gray-800 with 80% opacity */
            z-index: 49;
        }
    }
</style>


<div class="container mx-auto" x-data="{ mobileNavOpen: false }">
    <div class="z-20 flex items-center justify-between px-4 py-5 bg-transparent">
        <div class="flex items-center flex-nowrap">
            <div class="w-auto mr-14">
                <a href="{{ route('web.index') }}">
                    <img src="{{ asset($setting->site_logo) }}" alt="{{ $setting->site_name }}" class="h-16 w-auto">
                </a>
            </div>
            <div class="w-auto hidden lg:block">
                <ul class="flex items-center">
                    <li class="mr-9 font-medium hover:text-{{ $config[11]->config_value }}-700 ">
                        <a href="{{ route('web.index') }}">{{ __('Home') }}</a>
                    </li>
                    @if ($pages[5]->page_slug == 'features' && $pages[5]->status == 1)
                        <li class="mr-9 font-medium hover:text-{{ $config[11]->config_value }}-700">
                            <a href="{{ route('web.features') }}">{{ __('Features') }}</a>
                        </li>
                    @endif
                    <li class="mr-9 font-medium hover:text-{{ $config[11]->config_value }}-700">
                        <a href="/#pricing">{{ __('Pricing') }}</a>
                    </li>
                    @if ($pages[6]->page_slug == 'contact' && $pages[6]->status == 1)
                        <li class="mr-9 font-medium hover:text-{{ $config[11]->config_value }}-700">
                            <a href="{{ route('web.contact') }}">{{ __('Contact Us') }}</a>
                        </li>
                    @endif
                    @if ($pages[7]->page_slug == 'about' && $pages[7]->status == 1)
                        <li class="mr-9 font-medium hover:text-{{ $config[11]->config_value }}-700">
                            <a href="{{ route('web.about') }}">{{ __('About Us') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="flex flex-nowrap items-center ">
            <div class="w-auto hidden lg:block">
                <div class="inline-block">
                    {{-- Languages --}}
                    @if (count(config('app.languages')) > 1)
                        <div>
                            <button
                                class="font-heading mt text-gray-900 border-2 py-2.5 px-3 rounded-xl text-lg text-center inline-flex items-center"
                                type="button"
                                data-dropdown-toggle="languageDropdown">{{ strtoupper(app()->getLocale()) }}<svg
                                    class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="hidden bg-white text-base z-50 list-none divide-y divide-gray-100 rounded shadow my-4 w-40 h-60 scrollbar-hidden"
                                id="languageDropdown">
                                <ul class="py-1" aria-labelledby="dropdown1">
                                    @foreach (config('app.languages') as $langLocale => $langName)
                                        <li>
                                            <a class="block px-4 py-2 mt-2 text-base capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langName) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-auto hidden mx-3 lg:block">
                <div class="inline-block">
                    @if (!Auth::check())
                        <button
                            class="py-3 px-5 w-auto text-{{ $config[11]->config_value }}-500 border-2 border-{{ $config[11]->config_value }} hover:border-{{ $config[11]->config_value }}-700 font-medium rounded-xl bg-transparent transition ease-in-out duration-200"
                            type="button" onclick="window.location.href='/register?type=business'">
                            {{ __('For Business') }}
                        </button>
                    @endif
                </div>
            </div>
            <div class="w-auto hidden lg:block">
                <div class="inline-block">
                    @if (Auth::check() && Auth::user()->role == 1)
                        <a href="{{ route('admin.dashboard.index') }}"
                            class="py-3 px-5 w-full text-white font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200">{{ __('Account') }}</a>
                    @endif
                    @if (Auth::check() && Auth::user()->role == 2)
                        <a href="{{ route('business.dashboard.index') }}"
                            class="py-3 px-5 w-full text-white font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200">{{ __('Account') }}</a>
                    @endif
                    @if (Auth::check() && Auth::user()->role == 3)
                        <a href="{{ route('business-admin.dashboard.index', ['business_id' => Auth::user()->business_id]) }}"
                            class="py-3 px-5 w-full text-white font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200">{{ __('Account') }}</a>
                    @endif
                    @if (Auth::check() && Auth::user()->role == 4)
                        <a href="{{ route('user.my-bookings') }}"
                            class="py-3 px-5 w-full text-white font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200">{{ __('Account') }}</a>
                    @endif
                    @if (!Auth::check())
                        <button
                            class="py-3 px-5 w-full text-white font-semibold border border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200"
                            type="button" onclick="window.location.href='/login'">
                            {{ __('Log In') }}
                        </button>
                    @endif
                </div>
            </div>
            <div class="w-auto lg:hidden">
                <button x-on:click="mobileNavOpen = !mobileNavOpen">
                    <svg class="text-{{ $config[11]->config_value }}-600" width="51" height="51"
                        viewbox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="56" height="56" rx="28" fill="currentColor"></rect>
                        <path d="M37 32H19M37 24H19" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div :class="{ 'block': mobileNavOpen, 'hidden': !mobileNavOpen }"
        class="hidden fixed top-0 left-0 bottom-0 w-4/6 sm:max-w-xs z-50">
        <div x-on:click="mobileNavOpen = !mobileNavOpen" class="fixed inset-0 bg-gray-800 opacity-80"></div>
        <nav class="relative z-10 px-9 pt-8 bg-white h-full overflow-y-auto">
            <div class="flex flex-wrap justify-between h-full">
                <div class="w-full">
                    <div class="flex items-center justify-between -m-2">
                        <div class="w-auto p-2">
                            <a class="inline-block" href="#">
                                <img src="{{ asset($setting->site_logo) }}" alt="{{ $setting->site_name }}" />
                            </a>
                        </div>
                        <div class="w-auto p-2">
                            <button x-on:click="mobileNavOpen = !mobileNavOpen">
                                <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center py-5 w-full">
                    <ul>
                        <li class="mb-8">
                            <a class="font-medium hover:text-{{ $config[11]->config_value }}-700"
                                href="{{ route('web.index') }}">{{ __('Home') }}</a>
                        </li>
                        @if ($pages[5]->page_slug == 'features' && $pages[5]->status == 1)
                            <li class="mb-8">
                                <a class="font-medium hover:text-{{ $config[11]->config_value }}-700"
                                    href="{{ route('web.features') }}">{{ __('Features') }}</a>
                            </li>
                        @endif
                        <li class="mb-8">
                            <a class="font-medium hover:text-{{ $config[11]->config_value }}-700"
                                href="/#pricing">{{ __('Pricing') }}</a>
                        </li>
                        @if ($pages[6]->page_slug == 'contact' && $pages[6]->status == 1)
                            <li class="mb-8">
                                <a class="font-medium hover:text-{{ $config[11]->config_value }}-700"
                                    href="{{ route('web.contact') }}">{{ __('Contact Us') }}</a>
                            </li>
                        @endif
                        @if ($pages[7]->page_slug == 'about' && $pages[7]->status == 1)
                            <li class="mb-8">
                                <a class="font-medium hover:text-{{ $config[11]->config_value }}-700"
                                    href="{{ route('web.about') }}">{{ __('About Us') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="flex flex-col justify-end w-full pb-8">
                    <div class="flex flex-wrap">
                        <div class="w-full mb-3">
                            <div class="inline-block w-full">
                                {{-- Languages --}}
                                @if (count(config('app.languages')) > 1)
                                    <div>
                                        <button
                                            class="font-heading mr-4 mt-2 text-gray-900 text-lg text-center inline-flex items-center border-2 p-2.5 w-full rounded-lg"
                                            type="button"
                                            data-dropdown-toggle="languageDropdown1">{{ strtoupper(app()->getLocale()) }}<svg
                                                class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <div class="hidden bg-{{ $config['11']->config_value }}-100 text-base z-50 list-none divide-y divide-gray-100 rounded shadow my-4 w-48 h-60 scrollbar-hidden"
                                            id="languageDropdown1">
                                            <ul class="py-1" aria-labelledby="dropdown1">
                                                @foreach (config('app.languages') as $langLocale => $langName)
                                                    <li>
                                                        <a class="block px-4 py-2 mt-2 text-base capitalize font-semi-bold bg-transparent rounded-sm dark-mode:bg-transparent md:mt-0 focus:outline-none focus:shadow-outline"
                                                            href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langName) }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="w-full mb-3">
                            <div class="block">
                                @if (!Auth::check())
                                    <button
                                        class="py-3 px-5 w-full text-white font-semibold border border-{{ $config[11]->config_value }}-700 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200"
                                        type="button" onclick="window.location.href='/register?type=business'">
                                        {{ __('For Business') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="block">
                                @if (!Auth::check())
                                    <button
                                        class="py-3 px-5 w-full text-white font-semibold border border-{{ $config[11]->config_value }}-700 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200"
                                        type="button" onclick="window.location.href='/login'">
                                        {{ __('Sign In') }}
                                    </button>
                                @else
                                    @if (Auth::check() && Auth::user()->role == 1)
                                        <a href="{{ route('admin.dashboard.index') }}"
                                            class="py-3 px-5 w-full text-white block font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700">{{ __('Account') }}</a>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role == 2)
                                        <a href="{{ route('business.dashboard.index') }}"
                                            class="py-3 px-5 w-full text-white block font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700">{{ __('Account') }}</a>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role == 3)
                                        <a href="{{ route('business-admin.dashboard.index', ['business_id' => Auth::user()->business_id]) }}"
                                            class="py-3 px-5 w-full text-white block font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700">{{ __('Account') }}</a>
                                    @endif
                                    @if (Auth::check() && Auth::user()->role == 4)
                                        <a href="{{ route('user.my-bookings') }}"
                                            class="py-3 px-5 w-full text-white block font-semibold border-2 border-{{ $config[11]->config_value }}-600 rounded-xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-500 hover:bg-{{ $config[11]->config_value }}-700">{{ __('Account') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
