@extends('cms-core::layouts.admin')

@section('title', 'Shri-ms Core Updates')

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

    <div class="bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-gray-1100 dark:text-white border-b border-[#E8EDF2] dark:border-[#313442] pb-3 mb-4">Shri-ms Update Status</h3>
        
        <div>
            <div class="flex items-center gap-6 p-6 bg-neutral dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border rounded-xl mb-6">
                <div class="w-12 h-12 rounded-full bg-color-brands flex flex-shrink-0 items-center justify-center text-white font-bold shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-neutral-bg dark:bg-dark-neutral-border border border-neutral dark:border-dark-neutral-border p-3 rounded-lg flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-dark-1100">Current Version</span>
                            <span class="bg-color-brands text-white px-5 py-1.5 rounded-md text-xs font-bold">{{ $info['current_version'] }}</span>
                        </div>
                        <div class="bg-neutral-bg dark:bg-dark-neutral-border border border-neutral dark:border-dark-neutral-border p-3 rounded-lg flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-dark-1100">Latest Available</span>
                            <span class="bg-orange text-white px-5 py-1.5 rounded-md text-xs font-bold">{{ $info['latest_version'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($info['has_update'])
            <div class="p-6 bg-neutral-bg dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border rounded-xl shadow-sm mb-6">
                <h4 class="text-base font-bold text-color-brands dark:text-color-brands mb-3">Release Notes:</h4>
                <p class="text-sm leading-relaxed text-gray-800 dark:text-gray-dark-500">{{ $info['release_notes'] }}</p>
            </div>
            @else
            <div class="p-6 bg-green-50 dark:bg-dark-neutral-bg border border-green-200 dark:border-dark-neutral-border rounded-xl shadow-sm flex items-center gap-3 mb-6">
                <svg class="w-6 h-6 text-green" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-base text-green font-semibold m-0">Your Shri-ms core engine is fully up to date!</p>
            </div>
            @endif

            <form action="{{ route('cms.updates.run') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-base font-semibold text-gray-1100 dark:text-gray-dark-1100 mb-3">Or Upload Update ZIP (For Testing)</label>
                    <div class="w-full border-2 border-dashed border-neutral dark:border-dark-neutral-border hover:border-color-brands dark:hover:border-color-brands rounded-xl p-10 transition-colors cursor-pointer relative group bg-neutral-bg dark:bg-dark-neutral-bg">
                        <input type="file" name="update_zip" accept=".zip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" id="updateZipFile" onchange="document.getElementById('updateFileNameDisplay').innerText = this.files[0] ? this.files[0].name : 'Choose a file or drag it here';">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-neutral dark:bg-dark-neutral-border flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-color-brands" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="text-base font-semibold text-gray-800 dark:text-gray-dark-1100" id="updateFileNameDisplay">Choose a file or drag it here</div>
                            <div class="text-sm text-gray-500 dark:text-gray-dark-500">ZIP archive only. Leave empty to download directly from GitHub.</div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="text-sm font-semibold py-3 px-6 rounded-xl bg-color-brands text-white hover:opacity-90 transition-opacity">
                    @if($info['has_update']) Update Now (One-Click) @else Force Manual Update @endif
                </button>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection
