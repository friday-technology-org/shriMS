@extends('cms-core::layouts.admin')

@section('title', '419 Page Expired - Shri-ms')

@section('content')
    <div class="flex items-center text-xs gap-x-[11px] mb-[34px]">
        <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}"
                alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img
            src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span
            class="capitalize text-color-brands">Error 419</span>
    </div>

    <div class="flex flex-col items-center justify-center min-h-[50vh] text-center bg-white dark:bg-[#313442] rounded-[16px] border border-neutral dark:border-dark-neutral-border p-10">
        <h1 class="text-[120px] font-bold text-gray-200 dark:text-gray-dark-1100 leading-none mb-4">419</h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Page Expired</h2>
        <p class="text-gray-500 dark:text-gray-dark-500 max-w-md mb-8">Your session has expired due to inactivity or a security mismatch. Please refresh the page and try again.</p>
        <button onclick="window.location.reload(true)" class="px-8 py-3 bg-color-brands text-white rounded-xl font-semibold hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh Page
        </button>
    </div>
@endsection
