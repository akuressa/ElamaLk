@extends('layouts.classic')
<?php $blog->long_description = htmlspecialchars_decode($blog->long_description); ?>

@section('content')
    <div class="px-3 md:px-10 lg:px-24">

        {{-- Blog Post --}}
        @foreach (preg_split('/(<[^>]*>)/', $blog->long_description, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
            @if (strpos($part, '<') === 0)
                {!! __($part) !!}
            @else
                {{ __($part) }}
            @endif
        @endforeach

        {{-- Share This Post --}}
        <section class="pt-16 overflow-hidden relative">
            <div class="container mx-auto">
                <h2 class="text-4xl pb-2 font-bold font-heading tracking-px-n leading-none">
                    {{ __('Share This Blog Post') }}</h2>
                <div class="flex space-x-4 mt-5">
                    <!-- Facebook Share Button -->
                    <a href="{{ route('sharetofacebook', ['blog_slug' => $blog->blog_slug]) }}" target="_blank"
                        class="flex items-center px-2 py-2 bg-blue-700 text-white rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 rounded-full" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                        </svg>
                    </a>

                    <!-- Twitter Share Button -->
                    <a href="{{ route('sharetotwitter', ['blog_slug' => $blog->blog_slug]) }}" target="_blank"
                        class="flex items-center px-2 py-2 bg-gray-800 text-white rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 rounded-full" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                            <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                        </svg>
                    </a>

                    <!-- LinkedIn Share Button -->
                    <a href="{{ route('sharetolinkedin', ['blog_slug' => $blog->blog_slug]) }}" target="_blank"
                        class="flex items-center px-2 py-2 bg-blue-600 text-white rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 rounded-full" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                            <path d="M8 11l0 5" />
                            <path d="M8 8l0 .01" />
                            <path d="M12 16l0 -5" />
                            <path d="M16 16v-3a2 2 0 0 0 -4 0" />
                        </svg>
                    </a>

                    <!-- Instagram Share Button -->
                    <a href="{{ route('sharetoinstagram', ['blog_slug' => $blog->blog_slug]) }}" target="_blank"
                        class="flex items-center px-2 py-2 bg-pink-600 text-white rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 rounded-full" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M16.5 7.5l0 .01" />
                        </svg>
                    </a>

                    <!-- WhatsApp Share Button -->
                    <a href="{{ route('sharetowhatsapp', ['blog_slug' => $blog->blog_slug]) }}" target="_blank"
                        class="flex items-center px-2 py-2 bg-green-600 text-white rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 rounded-full" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                            <path
                                d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- Recent Blogs --}}
        @if ($recentBlogs)
            <section class="pt-16 pb-36 overflow-hidden relative">
                <img class="absolute top-0 left-0" src="../../home-assets/images/headers/gradient4.svg" alt="">
                <div class="container mx-auto">
                    <h2 class="text-4xl pb-5 font-bold font-heading tracking-px-n leading-none">
                        {{ __('Recent Blogs') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($recentBlogs as $blog)
                            <div class="pt-4">
                                <div class="h-full">
                                    <a class="inline-block flex flex-col justify-between h-full"
                                        href="{{ route('web.view.blog', ['blog_slug' => $blog->blog_slug]) }}">
                                        <div class="mb-8">
                                            <div class="mb-6 w-full overflow-hidden">
                                                <img class="w-full" src="{{ asset($blog->blog_cover) }}" alt="">
                                            </div>
                                            <p class="text-lg mb-1">
                                                {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</p>
                                            <h2 class="text-xl font-bold font-heading leading-normal text-lg text-justify">
                                                {{ __($blog->blog_name) }}</h2>
                                            <p class="mb-3 text-gray-600 text-justify mt-2">
                                                {{ $blog->short_description }}</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection
