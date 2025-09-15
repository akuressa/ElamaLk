@extends('layouts.classic')

@php
    $type = request()->query('type');
    
    use Artesaos\SEOTools\Facades\JsonLd;
    use Artesaos\SEOTools\Facades\OpenGraph;
    use Artesaos\SEOTools\Facades\SEOMeta;
    use Artesaos\SEOTools\Facades\SEOTools;
    use App\Models\Page;
    // Seo Tools
    $page = Page::where('status', 1)->first();

    SEOTools::setTitle(trans('Register') . ' - ' . $page->meta_title);
    SEOTools::setDescription($page->meta_description);

    SEOMeta::setTitle(trans('Register') . ' - ' . $page->meta_title);
    SEOMeta::setDescription($page->meta_description);
    SEOMeta::addMeta('article:section', 'Register' . ' - ' . $page->meta_description, 'property');
    SEOMeta::addKeyword([$page->meta_keywords]);

    OpenGraph::setTitle(trans('Register') . ' - ' . $page->meta_title);
    OpenGraph::setDescription($page->meta_description);
    OpenGraph::setUrl(URL::full());
    OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

    JsonLd::setTitle(trans('Register') . ' - ' . $page->meta_title);
    JsonLd::setDescription($page->meta_description);
    JsonLd::addImage(asset($setting->site_logo));
@endphp

@section('content')
    <div class="container mx-auto  min-h-screen p-2">
        <section class="relative bg-white overflow-hidden w-full">
            <img class="absolute left-0 top-0" src="flaro-assets/images/sign-in/gradient.svg" alt="">
            <div class="relative z-10 flex flex-wrap">
                <div class="w-full md:w-1/2 ">
                    <div class="container px-4 mx-auto">
                        <div class="flex flex-wrap">
                            <div class="w-full">
                                <div class="md:max-w-lg mx-auto lg:pt-16 pt-6 md:pb-32">
                                    <h2
                                        class="mb-12 text-3xl md:text-7xl font-bold font-heading tracking-px-n leading-tight">
                                        {{ __('Greetings on your return! We kindly request you to enter your details..') }}
                                    </h2>
                                    <h3 class="mb-9 text-xl font-bold font-heading leading-normal">
                                        {{ __('Why should you join us?') }}
                                    </h3>
                                    <ul class="md:max-w-xs">
                                        <li class="mb-5 flex flex-wrap">
                                            <svg class="mr-2" width="25" height="26" viewbox="0 0 25 26"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12.5 23C18.0228 23 22.5 18.5228 22.5 13C22.5 7.47715 18.0228 3 12.5 3C6.97715 3 2.5 7.47715 2.5 13C2.5 18.5228 6.97715 23 12.5 23ZM17.1339 11.3839C17.622 10.8957 17.622 10.1043 17.1339 9.61612C16.6457 9.12796 15.8543 9.12796 15.3661 9.61612L11.25 13.7322L9.63388 12.1161C9.14573 11.628 8.35427 11.628 7.86612 12.1161C7.37796 12.6043 7.37796 13.3957 7.86612 13.8839L10.3661 16.3839C10.8543 16.872 11.6457 16.872 12.1339 16.3839L17.1339 11.3839Z"
                                                    fill="#4F46E5"></path>
                                            </svg>
                                            <span
                                                class="flex-1 font-medium leading-relaxed">{{ __('The best you can do in no time at all, amazing feature goes here') }}</span>
                                        </li>
                                        <li class="mb-5 flex flex-wrap">
                                            <svg class="mr-2" width="25" height="26" viewbox="0 0 25 26"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M12.5 23C18.0228 23 22.5 18.5228 22.5 13C22.5 7.47715 18.0228 3 12.5 3C6.97715 3 2.5 7.47715 2.5 13C2.5 18.5228 6.97715 23 12.5 23ZM17.1339 11.3839C17.622 10.8957 17.622 10.1043 17.1339 9.61612C16.6457 9.12796 15.8543 9.12796 15.3661 9.61612L11.25 13.7322L9.63388 12.1161C9.14573 11.628 8.35427 11.628 7.86612 12.1161C7.37796 12.6043 7.37796 13.3957 7.86612 13.8839L10.3661 16.3839C10.8543 16.872 11.6457 16.872 12.1339 16.3839L17.1339 11.3839Z"
                                                    fill="#4F46E5"></path>
                                            </svg>
                                            <span
                                                class="flex-1 font-medium leading-relaxed">{{ __('24/7 Support of our dedicated, highly professional team') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 p-8 bg-{{ $config[11]->config_value }}-100 ">
                    <div class="p-4 py-16 flex flex-col justify-center h-full md:max-w-lg mx-auto">
                        {{-- Register form --}}
                        <form method="POST" action="{{ route('register', ['type' => $type]) }}">
                            @csrf
                            <!-- User Name -->
                            <label class="block mb-4">
                                <p class="mb-2 text-gray-900 font-semibold leading-normal">{{ __('User Name') }} <span
                                        style="color: red;">*</span></p>
                                <input
                                    class="px-4 py-3.5 w-full text-gray-400 font-medium placeholder-gray-400 bg-white focus:border-none outline-none border border-gray-300 rounded-lg focus:ring focus:ring-{{ $config[11]->config_value }}-300"
                                    id="name" type="text" name="name" :value="old('name')" required autofocus
                                    autocomplete="username" placeholder="{{ __('Enter user name') }}">
                            </label>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" style="color: red;" />

                            <!-- Email Address -->
                            <label class="block mb-4">
                                <p class="mb-2 text-gray-900 font-semibold leading-normal">{{ __('Email Address') }} <span
                                        style="color: red;">*</span></p>
                                <input
                                    class="px-4 py-3.5 w-full text-gray-400 font-medium placeholder-gray-400 focus:border-none bg-white outline-none border border-gray-300 rounded-lg focus:ring focus:ring-{{ $config[11]->config_value }}-300"
                                    id="email" type="email" name="email" :value="old('email')" required
                                    autocomplete="email" placeholder="{{ __('Enter Email Address') }}">
                            </label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red;" />

                            <!-- Password -->
                            <label class="block mb-5">
                                <p class="mb-2 text-gray-900 font-semibold leading-normal">{{ __('Password') }} <span
                                        style="color: red;">*</span></p>
                                <input
                                    class="px-4 py-3.5 w-full text-gray-400 font-medium placeholder-gray-400 focus:border-none bg-white outline-none border border-gray-300 rounded-lg focus:ring focus:ring-{{ $config[11]->config_value }}-300"
                                    id="password" type="password" name="password" required autocomplete="new-password"
                                    placeholder="********">
                            </label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: red;" />

                            <!-- Confirm Password -->
                            <label class="block mb-5">
                                <p class="mb-2 text-gray-900 font-semibold leading-normal">{{ __('Confirm Password') }}
                                    <span style="color: red;">*</span>
                                </p>
                                <input
                                    class="px-4 py-3.5 w-full text-gray-400 font-medium placeholder-gray-400 focus:border-none bg-white outline-none border border-gray-300 rounded-lg focus:ring focus:ring-{{ $config[11]->config_value }}-300"
                                    id="password_confirmation" type="password" name="password_confirmation" required
                                    autocomplete="new-password" placeholder="********">
                            </label>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: red;" />

                            {{-- Recaptcha --}}
                            @if ($settings['recaptcha_configuration']['RECAPTCHA_ENABLE'] == 'on')
                                <div class="mb-3 w-full px-2 mt-8">
                                    {!! htmlFormSnippet() !!}
                                </div>
                            @endif

                            <!-- Submit Button -->
                            <button
                                class="mt-4 mb-9 py-4 px-9 w-full text-white font-semibold border border-{{ $config[11]->config_value }}-700 rounded-xl shadow-4xl focus:ring focus:ring-{{ $config[11]->config_value }}-300 bg-{{ $config[11]->config_value }}-600 hover:bg-{{ $config[11]->config_value }}-700 transition ease-in-out duration-200"
                                type="submit">{{ __('Register') }}</button>
                        </form>

                        {{-- Alternative Sign-In Options --}}
                        @if (ENV('GOOGLE_ENABLE') == 'on')
                            <!-- Alternative Sign-In Options -->
                            <p class="mb-5 text-sm text-gray-500 font-medium text-center">{{ __('Or continue with') }}</p>
                            <div class="flex flex-wrap justify-center -m-2">
                                <div class="w-full p-2 ">
                                    <button
                                        onclick="window.location.href='/auth/google?type={{ request()->query('type') }}'"
                                        class="flex items-center w-full p-4 bg-white hover:bg-gray-50 border rounded-lg transition ease-in-out duration-200 flex justify-center">
                                        <img class="mr-3" src="{{ asset('home-assets/logos/brands/google.svg') }}"
                                            alt="Google logo">
                                        <span class="font-semibold leading-normal">{{ __('Sign up with Google') }}</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="text-center text-sm text-gray-500 font-medium mt-4 ">
                            <p>{{ __('Already have an account?') }} <a href="{{ route('login') }}"
                                    class="text-{{ $config[11]->config_value }}-600 font-medium">
                                    {{ __('Sign In') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
