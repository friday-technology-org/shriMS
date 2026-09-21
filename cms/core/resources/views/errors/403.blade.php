@extends('cms-core::layouts.admin')

@section('title', '403 Forbidden - Shri-ms')

@section('content')
    <div class="flex items-center text-xs gap-x-[11px] mb-[34px]">
        <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}"
                alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img
            src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span
            class="capitalize text-color-brands">Error 403</span>
    </div>

    <div class="flex flex-col items-center justify-center min-h-[50vh] text-center bg-white dark:bg-[#313442] rounded-[16px] border border-neutral dark:border-dark-neutral-border p-10">
        <h1 class="text-[120px] font-bold text-gray-200 dark:text-gray-dark-1100 leading-none mb-4">403</h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Access Denied</h2>
        <p class="text-gray-500 dark:text-gray-dark-500 max-w-md mb-8">You don't have permission to access this page. Please contact your administrator if you believe this is a mistake.</p>
        <a href="{{ url('admin') }}" class="px-8 py-3 bg-color-brands text-white rounded-xl font-semibold hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Dashboard
        </a>
    </div>
@endsection
