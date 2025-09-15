@php
    // Settings
    use App\Models\Setting;
    use App\Models\Configuration;
    use App\Models\Page;

    $setting = Setting::where('status', 1)->first();
    $page = Page::where('page_slug', 'home')->where('status', 1)->get();
    $config = Configuration::get();
@endphp

@extends('layouts.classic', ['meta_title' => '429' . ' - ' . $page[0]->meta_description])

@section('content')
    <section class="pt-32 pb-36 bg-white overflow-hidden">
        <div class="container px-4 mx-auto">
            <div class="flex flex-wrap -m-8">
                <div class="w-full md:w-1/2 p-8">
                    <div class="flex flex-col justify-between h-full">
                        <div class="mb-8">
                            <h2
                                class="mb-6 text-7xl text-{{ $config[11]->config_value }}-600 font-bold tracking-px-2n leading-none">
                                {{ __('429') }}</h2>
                            <h3 class="mb-4 text-3xl font-bold font-heading leading-snug">
                                {{ __('Too Many Requests') }}</h3>
                            <p class="text-lg text-gray-600 font-medium leading-normal md:max-w-md">
                                {{ __('Too many requests have been made to this page!') }}
                            </p>
                        </div>
                        <div>
                            <a class="inline-flex items-center text-center font-semibold text-{{ $config[11]->config_value }}-600 hover:text-{{ $config[11]->config_value }}-700 leading-normal"
                                href="/">
                                <svg class="mr-2.5" width="16" height="16" viewbox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.66667 12.6667L2 8.00004M2 8.00004L6.66667 3.33337M2 8.00004L14 8.00004"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span>{{ __('Go Back to Homepage') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-8 self-end">
                    <img class="mx-auto transform hover:-translate-x-4 transition ease-in-out duration-1000"
                        src="{{ asset('home-assets/images/http-codes/illustration.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection
