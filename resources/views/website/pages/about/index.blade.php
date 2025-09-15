@extends('layouts.classic')
<?php $page->page_content = htmlspecialchars_decode($page->page_content); ?>

@section('content')
    <div>

        {{-- Render the page content safely --}}
        @foreach (preg_split('/(<[^>]*>)/', $page->page_content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $part)
            @if (strpos($part, '<') === 0)
                {!! __($part) !!}
            @else
                {{ __($part) }}
            @endif
        @endforeach
    </div>
@endsection
