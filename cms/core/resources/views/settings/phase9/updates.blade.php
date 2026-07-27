@extends('cms-core::layouts.admin')

@section('title', 'LaraCMS Core Updates')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">One-Click Core Updates</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Updates</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">LaraCMS Update Status</h3>
        
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                    i
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-1100 dark:text-white">Current Version: <code>{{ $info['current_version'] }}</code></p>
                    <p class="text-sm text-gray-500">Latest Available: <code>{{ $info['latest_version'] }}</code></p>
                </div>
            </div>

            @if($info['has_update'])
            <div class="p-4 bg-blue-50 dark:bg-[#1e202c] border border-blue-200 dark:border-blue-900 rounded-xl">
                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-400 mb-1">Release Notes:</h4>
                <p class="text-xs text-blue-950 dark:text-blue-200">{{ $info['release_notes'] }}</p>
            </div>

            <form action="{{ route('cms.updates.run') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-semibold py-3 px-6 rounded-xl bg-color-brands text-white hover:opacity-90">
                    Update Now (One-Click)
                </button>
            </form>
            @else
            <p class="text-sm text-green-600 font-semibold">Your LaraCMS core engine is fully up to date!</p>
            @endif
        </div>
    </div>
</div>
@endsection
