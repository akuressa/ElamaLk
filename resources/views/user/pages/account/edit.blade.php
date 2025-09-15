@extends('user.layouts.app')

@section('content')
    <div class="page-wrapper h-screen">
        <div class="page-body">
            {{-- Edit --}}
            <div class="w-full">
                <form action="{{ route('user.update.account') }}" method="post" enctype="multipart/form-data" class="p-6">
                    @csrf

                    {{-- Failed --}}
                    @if (Session::has('failed'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <div>{{ Session::get('failed') }}</div>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" data-dismiss="alert"
                                aria-label="close">
                                <span class="text-red-500">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Success --}}
                    @if (Session::has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            <div>{{ Session::get('success') }}</div>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" data-dismiss="alert"
                                aria-label="close">
                                <span class="text-green-500">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        {{-- Profile Image --}}
                        <div>
                            <label class="block mb-2 font-bold text-lg">{{ __('Profile Picture') }}

                            </label>
                            <input type="file" name="profile_picture" accept=".jpeg,.jpg,.png,.gif,.svg"
                                class="w-full p-1.5 text-sm placeholder-gray-500 border focus:border-none border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md outline-none" />
                        </div>

                        {{-- Name --}}
                        <div>
                            <label class="block mb-2 font-bold text-lg" for="name">{{ __('User Name') }}</label>
                            <input type="text" name="name" placeholder="{{ __('Name') }}"
                                value="{{ $user->name }}"
                                class="w-full p-4 text-sm placeholder-gray-500 focus:border-none border border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                                required />
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block mb-2 font-bold text-lg" for="name">{{ __('Email') }}</label>
                            <input type="email" name="email" placeholder="{{ __('Email') }}"
                                value="{{ $user->email }}"
                                class="w-full p-4 text-sm placeholder-gray-500 focus:border-none border border-gray-300 focus:ring focus:ring-{{ $config[11]->config_value }}-200 rounded-md appearance-none outline-none"
                                required />
                        </div>
                    </div>


                    <div class="mt-6 text-right">
                        <button type="submit"
                            class="w-full md:w-auto bg-{{ $config[11]->config_value }}-500 text-white font-bold py-3 px-6 rounded-md hover:bg-{{ $config[11]->config_value }}-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 24 24"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2">
                                <path d="M9 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-3" />
                                <path d="M9 15h3l8.5-8.5a1.5 1.5 0 1 0-3-3L9 12v3z" />
                                <line x1="16" y1="5" x2="19" y2="8" />
                            </svg>
                            {{ __('Update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
