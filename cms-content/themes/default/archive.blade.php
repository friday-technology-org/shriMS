@extends('theme::layouts.app')

@php
    $archiveTitle = isset($term) ? $term->name : ($postType->plural_label ?? 'Archive');
@endphp

@section('title', $archiveTitle . ' - ' . cms_option('site_title', 'LaraCMS'))

@section('content')
<div class="cms-page-wrap has-sidebar">
    <div>
        <div class="cms-archive-header">
            <h1>{{ $archiveTitle }}</h1>
            @if(isset($term) && $term->description)
                <p>{{ $term->description }}</p>
            @endif
        </div>

        @forelse($posts as $post)
            <article class="cms-post-card">
                @if($post->featuredImage)
                    <div class="cms-featured-image">
                        <img src="{{ $post->featuredImage->thumbnailUrl('large') }}" alt="{{ $post->title }}">
                    </div>
                @endif
                <h2><a href="{{ $post->permalink }}">{{ $post->title }}</a></h2>
                <p class="cms-post-meta">{{ $post->author?->name }} &middot; {{ $post->published_at?->format('M j, Y') }}</p>
                <p class="cms-post-excerpt">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}</p>
                <a href="{{ $post->permalink }}">Read more &rarr;</a>
            </article>
        @empty
            <p>Nothing found.</p>
        @endforelse

        @if($posts->hasPages())
            <div class="cms-pagination">{{ $posts->links() }}</div>
        @endif
    </div>

    <aside>
        {!! cms_widget_area('primary_sidebar') !!}
    </aside>
</div>
@endsection
