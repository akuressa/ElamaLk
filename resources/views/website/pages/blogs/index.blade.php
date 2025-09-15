@extends('layouts.classic')

@section('custom-css')
<style>
  .line-clamp-3 {
      display: -webkit-box;
      -webkit-line-clamp: 4; /* Number of lines */
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
  }
</style>
@endsection

@section('content')
    <div>
        <section class="pt-10 pb-36 overflow-hidden relative">
            <img class="absolute top-0 left-0" src="../../home-assets/images/headers/gradient4.svg" alt="">
            @if ($blogs->isEmpty())
                <div class="flex flex-col justify-center items-center min-h-screen -my-40">
                    <div class="flex justify-center items-center flex-col ">
                        <img src="{{ asset('img/no-data.svg') }}" alt="" class="w-96 h-96">
                        <p class="text-xl font-bold -mt-10">{{ __('No Blogs') }}</p>
                        <p class="text-gray-600 text-justify mt-2">{{ __('There are no blogs available at the moment.') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="container px-4 mx-auto">
                    <h2
                        class="mb-6 text-6xl md:text-7xl pb-6 text-center font-bold font-heading tracking-px-n leading-none">
                        {{ __('Our Blogs') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 z-10">
                        @foreach ($blogs as $blog)
                            <a class="p-2 z-20 border rounded-2xl border-2 border-{{ $config[11]->config_value }}-100 shadow-md" href="{{ route('web.view.blog', ['blog_slug' => $blog->blog_slug]) }}">
                                <div class="p-4 h-full">
                                    <div class="inline-block flex flex-col justify-between h-full"
                                        href="{{ route('web.view.blog', ['blog_slug' => $blog->blog_slug]) }}">
                                        <div class="mb-2">
                                            <div class="mb-6 w-full overflow-hidden">
                                                <img class="w-full rounded-2xl" src="{{ asset($blog->blog_cover) }}" alt="">
                                            </div>
                                            <p class="text-lg mb-1">
                                                {{ \Carbon\Carbon::parse($blog->created_at)->format('d M Y') }}</p>
                                            <h2 class="text-xl font-bold font-heading leading-normal text-lg">
                                                {{ __($blog->blog_name) }}</h2>
                                            <p class="text-gray-600 mt-2 line-clamp-3">
                                                {{ $blog->short_description }}</p>
                                        </div>
                                     	 <!-- Read More Button -->
                                          <div class="py-2 flex items-center font-bold">
                                              <span class="">{{ __('Read More') }}</span>
                                              <svg width="19" height="18" viewbox="0 0 19 18" fill="none"
                                                  xmlns="http://www.w3.org/2000/svg">
                                                  <path d="M11 3.75L16.25 9M16.25 9L11 14.25M16.25 9L2.75 9" stroke="currentColor"
                                                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                              </svg>
                                          </div>
                                    </div>                                  
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
