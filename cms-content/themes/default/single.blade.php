@extends('theme::layouts.app')

@section('title', $post->title . ' - ' . cms_option('site_title', 'LaraCMS'))

@section('content')
<div class="cms-page-wrap has-sidebar">
    <div>
        <article class="cms-single-post">
            @if($post->featuredImage)
                <div class="cms-featured-image">
                    <img src="{{ $post->featuredImage->thumbnailUrl('large') }}" alt="{{ $post->title }}">
                </div>
            @endif
            <h1>{{ $post->title }}</h1>
            <p class="cms-post-meta">
                {{ $post->author?->name }} &middot; {{ $post->published_at?->format('M j, Y') }}
                @if($post->terms->isNotEmpty())
                    &middot; {{ $post->terms->pluck('name')->join(', ') }}
                @endif
            </p>
            <div class="cms-post-content">
                {!! apply_filters('the_content', $post->content) !!}
            </div>
        </article>

        @include('theme::partials.comments')
    </div>

    <aside>
        {!! cms_widget_area('primary_sidebar') !!}
    </aside>
</div>
@endsection
