@extends('theme::layouts.app')

@section('title', $post->title . ' - ' . cms_option('site_title', 'LaraCMS'))

@section('content')
<div class="cms-page-wrap">
    <article class="cms-single-post">
        @if($post->featuredImage)
            <div class="cms-featured-image">
                <img src="{{ $post->featuredImage->thumbnailUrl('large') }}" alt="{{ $post->title }}">
            </div>
        @endif
        <h1>{{ $post->title }}</h1>
        <div class="cms-post-content">
            {!! apply_filters('the_content', $post->content) !!}
        </div>
    </article>
</div>
@endsection
