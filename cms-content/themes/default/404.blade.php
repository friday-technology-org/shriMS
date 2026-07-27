@extends('theme::layouts.app')

@section('title', 'Page Not Found - ' . cms_option('site_title', 'LaraCMS'))

@section('content')
<div class="cms-404">
    <h1>404</h1>
    <p>The page you're looking for doesn't exist.</p>
    <a href="{{ url('/') }}">&larr; Back to homepage</a>
</div>
@endsection
