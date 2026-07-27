@extends('theme::layouts.app')

@section('title', cms_option('site_title', 'LaraCMS'))

@section('content')
<div class="cms-page-wrap has-sidebar">
    <div>
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
            <p>No posts published yet.</p>
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
