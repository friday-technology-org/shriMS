@extends('cms-core::layouts.admin')

@section('title', 'Edit Field Group - LaraCMS')

@section('content')
<form action="{{ route('cms.field-groups.update', $fieldGroup->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div>
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Edit Field Group</h2>
        <div class="flex justify-between flex-col gap-y-2 sm:flex-row mb-[54px]">
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Custom Fields</span><img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon"><span class="capitalize text-color-brands">Edit</span>
        </div>
        </div>

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] pt-[25px] pb-[68px]">
        
            <div class="mb-8">
                <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">Field Group Title</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] sm:min-w-[252px]">
                <input name="title" class="input w-full bg-transparent text-sm leading-4 text-gray-800 h-fit min-h-fit py-4 focus:outline-none pl-[13px] dark:text-white placeholder:text-inherit" type="text" placeholder="e.g., Homepage Settings" value="{{ old('title', $fieldGroup->title) }}" required>
                </div>
            </div>

            @php
                $ruleValue = $fieldGroup->location_rules[0]['value'] ?? 'post';
            @endphp

            <!-- Location Rules -->
            <div class="mb-12 border border-neutral dark:border-dark-neutral-border rounded-xl p-[20px] bg-neutral-bg dark:bg-dark-neutral-bg">
                <h3 class="text-gray-1100 text-lg font-bold mb-4 dark:text-gray-dark-1100">Location Rules</h3>
                <p class="text-sm text-gray-800 mb-4 dark:text-gray-dark-500">Show this field group if</p>
                
                <div class="flex items-center gap-4">
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-[200px]">
                        <select name="location_rules[0][param]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="post_type" class="bg-white dark:bg-dark-neutral-bg">Post Type</option>
                        </select>
                    </div>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-[200px]">
                        <select name="location_rules[0][operator]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="==" class="bg-white dark:bg-dark-neutral-bg">is equal to</option>
                        </select>
                    </div>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-[200px]">
                        <select name="location_rules[0][value]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="post" {{ $ruleValue == 'post' ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">Post</option>
                            <option value="page" {{ $ruleValue == 'page' ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">Page</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Fields Builder -->
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-gray-1100 text-lg font-bold dark:text-gray-dark-1100">Fields</h3>
                    <button type="button" id="add-field-btn" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[6px] px-[16px]">
                        + Add Field
                    </button>
                </div>

                <div id="fields-container" class="flex flex-col gap-6">
                    <!-- Fields will be dynamically added here -->
                </div>
            </div>

            <div class="w-full bg-neutral h-[1px] dark:bg-dark-neutral-border mb-6"></div>
            
            <div class="flex items-center gap-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $fieldGroup->is_active ? 'checked' : '' }} class="checkbox checkbox-primary rounded border border-neutral dark:border-dark-neutral-border w-[18px] h-[18px]">
                <label for="is_active" class="text-sm font-medium text-gray-800 dark:text-gray-dark-500">Active (Show this field group)</label>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg font-medium dark:border-dark-neutral-bg py-[10px] px-[24px] text-sm text-white">Update Field Group</button>
                <a href="{{ route('cms.field-groups.index') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 border-neutral-bg bg-gray-200 font-medium text-gray-500 dark:border-dark-neutral-bg py-[10px] px-[24px] dark:bg-gray-dark-200 text-sm dark:text-gray-dark-500 hover:bg-gray-200 dark:hover:bg-gray-dark-200 hover:border-gray-300 dark:hover:border-gray-dark-300">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('fields-container');
    const addBtn = document.getElementById('add-field-btn');
    let fieldCount = 0;

    const existingFields = @json($fieldGroup->fields);

    function addFieldRow(data = {}) {
        const index = fieldCount++;
        const row = document.createElement('div');
        row.className = 'field-row border border-neutral dark:border-dark-neutral-border rounded-xl p-[20px] bg-neutral-bg dark:bg-dark-neutral-bg shadow-sm relative';
        
        row.innerHTML = `
            <div class="absolute top-4 right-4">
                <button type="button" class="remove-field-btn text-red-500 hover:text-red-700 text-sm font-bold">Remove</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div>
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[10px] dark:text-gray-dark-1100">Field Label</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                        <input type="text" name="fields[${index}][label]" class="field-label input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" value="${data.label || ''}" required placeholder="e.g. Hero Image">
                    </div>
                </div>
                <div>
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[10px] dark:text-gray-dark-1100">Field Name (slug)</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                        <input type="text" name="fields[${index}][name]" class="field-name input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" value="${data.name || ''}" required placeholder="e.g. hero_image" data-modified="${data.name ? 'true' : ''}">
                    </div>
                </div>
                <div>
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[10px] dark:text-gray-dark-1100">Field Type</p>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                        <select name="fields[${index}][type]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="text" ${data.type === 'text' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Text</option>
                            <option value="textarea" ${data.type === 'textarea' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Textarea</option>
                            <option value="wysiwyg" ${data.type === 'wysiwyg' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">WYSIWYG Editor</option>
                            <option value="image" ${data.type === 'image' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Image (URL)</option>
                            <option value="number" ${data.type === 'number' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Number</option>
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-1100 text-xs font-bold capitalize mb-[10px] dark:text-gray-dark-1100">Instructions (optional)</p>
                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full">
                    <input type="text" name="fields[${index}][instructions]" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none pl-[13px] placeholder:text-inherit" value="${data.instructions || ''}" placeholder="Instructions for authors">
                </div>
            </div>
        `;

        const labelInput = row.querySelector('.field-label');
        const nameInput = row.querySelector('.field-name');
        
        labelInput.addEventListener('input', function() {
            if (!nameInput.dataset.modified) {
                let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/(^_|_$)/g, '');
                nameInput.value = slug;
            }
        });
        
        nameInput.addEventListener('input', function() {
            this.dataset.modified = true;
        });

        row.querySelector('.remove-field-btn').addEventListener('click', function() {
            row.remove();
        });

        container.appendChild(row);
    }

    addBtn.addEventListener('click', () => addFieldRow());
    
    // Load existing fields
    if (existingFields.length > 0) {
        existingFields.forEach(field => addFieldRow(field));
    } else {
        addFieldRow();
    }
});
</script>
@endsection
