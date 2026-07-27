@extends('cms-core::layouts.admin')

@section('title', 'My Profile - LaraCMS')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">My Profile</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Profile</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('cms.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="w-full h-fit border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Profile Information</p>
                
                <div class="flex flex-col gap-4 max-w-2xl">
                    <div class="w-full">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Profile Image</p>
                        <div id="dropzone" class="border-2 border-dashed border-[#E8EDF2] dark:border-[#313442] rounded-xl p-6 flex flex-col items-center justify-center text-center hover:bg-gray-50 dark:hover:bg-gray-dark-100 transition-colors cursor-pointer relative" onclick="document.getElementById('avatar-input').click()">
                            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewImage(event)">
                            
                            <div id="preview-container" class="mb-4 {{ $user->avatar ? '' : 'hidden' }}">
                                <img id="preview-image" src="{{ $user->avatar ? asset($user->avatar) : '' }}" alt="Avatar Preview" class="w-24 h-24 rounded-full object-cover border border-neutral dark:border-dark-neutral-border shadow-sm mx-auto">
                            </div>
                            
                            <div id="upload-placeholder" class="{{ $user->avatar ? 'hidden' : '' }}">
                                <div class="w-12 h-12 rounded-full bg-color-brands/10 flex items-center justify-center text-color-brands mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-800 dark:text-white font-semibold mb-1"><span class="text-color-brands">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-dark-500">SVG, PNG, JPG or GIF (max. 2MB)</p>
                        </div>
                    </div>

                    <hr class="border-[#E8EDF2] dark:border-[#313442] my-2">

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Name</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Email</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Nickname (Optional)</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <input type="text" name="nickname" value="{{ old('nickname', $user->nickname) }}" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Bio (Optional)</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <textarea name="bio" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full h-fit border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Change Password (Leave blank to keep current)</p>
                
                <div class="flex flex-col gap-4 max-w-2xl">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Current Password</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <input type="password" name="current_password" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">New Password</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <input type="password" name="password" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Confirm New Password</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <input type="password" name="password_confirmation" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-gray-400">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex lg:col-span-2">
                <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[20px]">Save Profile</button>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

const dropzone = document.getElementById('dropzone');
const avatarInput = document.getElementById('avatar-input');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropzone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropzone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropzone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropzone.classList.add('border-color-brands', 'bg-color-brands/5');
}

function unhighlight(e) {
    dropzone.classList.remove('border-color-brands', 'bg-color-brands/5');
}

dropzone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length) {
        avatarInput.files = files;
        // manually trigger change event for preview
        const event = new Event('change');
        avatarInput.dispatchEvent(event);
    }
}
</script>
@endsection
