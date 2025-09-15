@extends('user.layouts.app')

@section('content')
    <div class="page-wrapper h-screen">
        <div class="page-body">
            <div class="container mx-auto">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="bg-red-500 text-white p-4 rounded-md mb-4 relative">
                        <div class="flex justify-between items-center">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                            <button class="text-white" onclick="this.parentElement.parentElement.remove()">x</button>
                        </div>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="bg-green-500 text-white p-4 rounded-md mb-4 relative">
                        <div class="flex justify-between items-center">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                            <button class="text-white" onclick="this.parentElement.parentElement.remove()">x</button>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4">
                    <div class="col-span-1">
                        <div class="bg-white border rounded-lg">
                            <div class="p-4 text-center">
                                {{-- Account Details --}}
                                <span class="block rounded-full bg-white w-20 h-20 mx-auto mb-3 overflow-hidden">
                                    <img src="{{ asset(Auth::user()->profile_image == null ? 'images/profile.png' : Auth::user()->profile_image) }}"
                                        alt="{{ Auth::user()->name }}" class="object-cover w-full h-full">
                                </span>
                                <h3 class="text-lg font-semibold mb-1">{{ __($user->name) }}</h3>
                                <div class="text-gray-500">
                                    {{ $user->email == '' ? __('Not Available') : $user->email }}
                                </div>
                                <div class="mt-3">
                                    <span class="bg-{{ $config[11]->config_value }}-500 text-white px-3 py-1 rounded-full text-sm">
                                        {{ __('User') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex justify-between p-6 border-t">
                                <a href="{{ route('user.edit.account') }}"
                                    class="flex items-center space-x-2 text-{{ $config[11]->config_value }}-500 w-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                        <line x1="16" y1="5" x2="19" y2="8"></line>
                                    </svg>
                                    <span>{{ __('Edit') }}</span>
                                </a>
                                <a href="{{ route('user.change.password') }}"
                                    class="flex items-center justify-end space-x-2 text-{{ $config[11]->config_value }}-500 w-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="8" cy="15" r="4"></circle>
                                        <line x1="10.85" y1="12.15" x2="19" y2="4"></line>
                                        <line x1="18" y1="5" x2="20" y2="7"></line>
                                        <line x1="15" y1="8" x2="17" y2="10"></line>
                                    </svg>
                                    <span>{{ __('Change Password') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
