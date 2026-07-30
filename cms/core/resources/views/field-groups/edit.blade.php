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
                $ruleParam = $fieldGroup->location_rules[0]['param'] ?? 'post_type';
                $ruleValue = $fieldGroup->location_rules[0]['value'] ?? 'post';
            @endphp

            <!-- Location Rules -->
            <div class="mb-12 border border-neutral dark:border-dark-neutral-border rounded-xl p-[20px] bg-neutral-bg dark:bg-dark-neutral-bg" x-data="{ param: '{{ $ruleParam }}' }">
                <h3 class="text-gray-1100 text-lg font-bold mb-4 dark:text-gray-dark-1100">Location Rules</h3>
                <p class="text-sm text-gray-800 mb-4 dark:text-gray-dark-500">Show this field group if</p>
                
                <div class="flex items-center gap-4">
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-[200px]">
                        <select name="location_rules[0][param]" x-model="param" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="post_type" class="bg-white dark:bg-dark-neutral-bg">Post Type</option>
                            <option value="page" class="bg-white dark:bg-dark-neutral-bg">Page</option>
                            <option value="page_template" class="bg-white dark:bg-dark-neutral-bg">Page Template</option>
                            <option value="taxonomy" class="bg-white dark:bg-dark-neutral-bg">Taxonomy</option>
                        </select>
                    </div>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-[200px]">
                        <select name="location_rules[0][operator]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="==" class="bg-white dark:bg-dark-neutral-bg">is equal to</option>
                            <option value="!=" class="bg-white dark:bg-dark-neutral-bg">is not equal to</option>
                        </select>
                    </div>
                    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] w-full max-w-[200px]">
                        <select x-show="param === 'post_type'" :name="param === 'post_type' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['post_types'] as $pt)
                                <option value="{{ $pt->name }}" {{ ($ruleParam === 'post_type' && $ruleValue == $pt->name) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $pt->singular_label }}</option>
                            @endforeach
                        </select>
                        <select x-cloak x-show="param === 'page'" :name="param === 'page' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['pages'] as $page)
                                <option value="{{ $page->id }}" {{ ($ruleParam === 'page' && $ruleValue == $page->id) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $page->title }}</option>
                            @endforeach
                        </select>
                        <select x-cloak x-show="param === 'page_template'" :name="param === 'page_template' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['templates'] as $file => $name)
                                <option value="{{ $file }}" {{ ($ruleParam === 'page_template' && $ruleValue == $file) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $name }}</option>
                            @endforeach
                        </select>
                        <select x-cloak x-show="param === 'taxonomy'" :name="param === 'taxonomy' ? 'location_rules[0][value]' : ''" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            @foreach($locationOptions['taxonomies'] as $tax)
                                <option value="{{ $tax->name }}" {{ ($ruleParam === 'taxonomy' && $ruleValue == $tax->name) ? 'selected' : '' }} class="bg-white dark:bg-dark-neutral-bg">{{ $tax->singular_label }}</option>
                            @endforeach
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

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const container = document.getElementById('fields-container');
    const addBtn = document.getElementById('add-field-btn');
    let fieldCount = 0;

    const existingFields = @json($fieldGroup->fields);

    function addFieldRow(data = {}) {
        const index = fieldCount++;
        const row = document.createElement('div');
        row.className = 'field-row border border-neutral dark:border-dark-neutral-border rounded-xl p-[20px] bg-neutral-bg dark:bg-dark-neutral-bg shadow-sm relative';
        
        row.innerHTML = `
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#E8EDF2] dark:border-[#313442]">
                <div class="flex items-center gap-3">
                    <div class="cursor-move drag-handle text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Field Configuration</span>
                </div>
                <button type="button" class="remove-field-btn flex items-center gap-1 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:hover:text-red-300 rounded px-3 py-1.5 transition-colors text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Remove Field
                </button>
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
                        <select name="fields[${index}][type]" class="field-type-select select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                            <option value="text" ${data.type === 'text' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Text</option>
                            <option value="textarea" ${data.type === 'textarea' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Textarea</option>
                            <option value="wysiwyg" ${data.type === 'wysiwyg' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">WYSIWYG Editor</option>
                            <option value="image" ${data.type === 'image' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Image</option>
                            <option value="number" ${data.type === 'number' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Number</option>
                            <option value="group" ${data.type === 'group' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Group</option>
                            <option value="repeater" ${data.type === 'repeater' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">Repeater</option>
                            <option value="url" ${data.type === 'url' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">URL</option>
                            <option value="file" ${data.type === 'file' ? 'selected' : ''} class="bg-white dark:bg-dark-neutral-bg">File</option>
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
            
            <div class="sub-fields-container mt-4 p-4 bg-gray-50 dark:bg-[#252734] border border-[#E8EDF2] dark:border-[#313442] rounded-lg ${(data.type === 'repeater' || data.type === 'group') ? 'block' : 'hidden'}">
                <p class="sub-fields-title text-gray-1100 text-sm font-bold capitalize mb-[15px] dark:text-gray-dark-1100">${data.type === 'repeater' ? 'Repeater Sub-Fields' : 'Group Sub-Fields'}</p>
                <div class="sub-fields-list space-y-4 mb-4"></div>
                <button type="button" class="add-sub-field-btn btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[6px] px-[12px] rounded flex items-center gap-2 text-xs whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Sub-Field
                </button>
            </div>
        `;

        const labelInput = row.querySelector('.field-label');
        const nameInput = row.querySelector('.field-name');
        const typeSelect = row.querySelector('.field-type-select');
        const subFieldsContainer = row.querySelector('.sub-fields-container');
        const subFieldsList = row.querySelector('.sub-fields-list');
        const addSubFieldBtn = row.querySelector('.add-sub-field-btn');
        let subIndex = 0;
        
        labelInput.addEventListener('input', function() {
            if (!nameInput.dataset.modified) {
                let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/(^_|_$)/g, '');
                nameInput.value = slug;
            }
        });
        
        nameInput.addEventListener('input', function() {
            this.dataset.modified = true;
        });

        typeSelect.addEventListener('change', function() {
            if (this.value === 'repeater' || this.value === 'group') {
                subFieldsContainer.classList.remove('hidden');
                subFieldsContainer.classList.add('block');
                const titleEl = subFieldsContainer.querySelector('.sub-fields-title');
                if (titleEl) titleEl.innerText = this.value === 'repeater' ? 'Repeater Sub-Fields' : 'Group Sub-Fields';
                if (subFieldsList.children.length === 0) {
                    addSubField(index, subIndex++, subFieldsList);
                }
                
                // Initialize sortable for sub-fields if not already
                if (!subFieldsList.sortableInstance && typeof Sortable !== 'undefined') {
                    subFieldsList.sortableInstance = new Sortable(subFieldsList, {
                        handle: '.sub-drag-handle',
                        animation: 150
                    });
                }
            } else {
                subFieldsContainer.classList.add('hidden');
                subFieldsContainer.classList.remove('block');
            }
        });

        row.querySelector('.remove-field-btn').addEventListener('click', function() {
            row.remove();
        });

        addSubFieldBtn.addEventListener('click', function() {
            addSubField(index, subIndex++, subFieldsList);
        });

        // Load existing sub-fields if any
        if ((data.type === 'repeater' || data.type === 'group') && data.settings && data.settings.sub_fields) {
            data.settings.sub_fields.forEach(subData => {
                addSubField(index, subIndex++, subFieldsList, subData);
            });
            // Init sortable if loaded with data
            if (!subFieldsList.sortableInstance && typeof Sortable !== 'undefined') {
                subFieldsList.sortableInstance = new Sortable(subFieldsList, {
                    handle: '.sub-drag-handle',
                    animation: 150
                });
            }
        }

        container.appendChild(row);
    }

    function addSubField(parentIndex, sIndex, containerList, subData = {}) {
        const subRow = document.createElement('div');
        subRow.className = 'sub-field-row flex flex-col md:flex-row gap-4 items-center bg-white dark:bg-dark-neutral-bg p-4 border border-[#E8EDF2] dark:border-[#313442] rounded-lg relative';
        
        subRow.innerHTML = `
                <div class="flex-shrink-0 cursor-move sub-drag-handle text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded transition-colors self-end md:self-auto mb-[2px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
                <div class="flex items-end gap-4 relative w-full">
                <div class="flex-1">
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Label</p>
                    <input type="text" name="fields[${parentIndex}][settings][sub_fields][${sIndex}][label]" class="sub-label input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] border rounded border-[#E8EDF2] dark:border-[#313442]" value="${subData.label || ''}" required placeholder="Sub Field Label">
                </div>
                <div class="flex-1">
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Name</p>
                    <input type="text" name="fields[${parentIndex}][settings][sub_fields][${sIndex}][name]" class="sub-name input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none pl-[13px] border rounded border-[#E8EDF2] dark:border-[#313442]" value="${subData.name || ''}" required placeholder="sub_field_name" data-modified="${subData.name ? 'true' : ''}">
                </div>
                <div class="flex-1">
                    <p class="text-gray-1100 text-xs font-bold capitalize mb-[5px] dark:text-gray-dark-1100">Type</p>
                    <select name="fields[${parentIndex}][settings][sub_fields][${sIndex}][type]" class="select w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-2 focus:outline-none px-[13px] border rounded border-[#E8EDF2] dark:border-[#313442]">
                        <option value="text" ${subData.type === 'text' ? 'selected' : ''}>Text</option>
                        <option value="textarea" ${subData.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                        <option value="wysiwyg" ${subData.type === 'wysiwyg' ? 'selected' : ''}>WYSIWYG Editor</option>
                        <option value="image" ${subData.type === 'image' ? 'selected' : ''}>Image</option>
                        <option value="number" ${subData.type === 'number' ? 'selected' : ''}>Number</option>
                        <option value="url" ${subData.type === 'url' ? 'selected' : ''}>URL</option>
                        <option value="file" ${subData.type === 'file' ? 'selected' : ''}>File</option>
                    </select>
                    </div>
                    <button type="button" class="remove-sub-field-btn flex-shrink-0 text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 p-2 rounded transition-colors" title="Remove Sub-field">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
        `;

        const subLabelInput = subRow.querySelector('.sub-label');
        const subNameInput = subRow.querySelector('.sub-name');
        
        subLabelInput.addEventListener('input', function() {
            if (!subNameInput.dataset.modified) {
                let slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/(^_|_$)/g, '');
                subNameInput.value = slug;
            }
        });
        
        subNameInput.addEventListener('input', function() {
            this.dataset.modified = true;
        });

        subRow.querySelector('.remove-sub-field-btn').addEventListener('click', function() {
            subRow.remove();
        });

        containerList.appendChild(subRow);
    }

    addBtn.addEventListener('click', (e) => {
        e.preventDefault();
        addFieldRow();
    });
    
    // Load existing fields
    if (existingFields.length > 0) {
        existingFields.forEach(field => addFieldRow(field));
    } else {
        addFieldRow();
    }
    // Initialize top-level sortable
    if (typeof Sortable !== 'undefined') {
        new Sortable(container, {
            handle: '.drag-handle',
            animation: 150
        });
    }

    // On form submit, update field indexes so they match DOM order
    form.addEventListener('submit', function(e) {
        const fieldRows = container.querySelectorAll('.field-row');
        fieldRows.forEach((row, i) => {
            // Update main inputs
            const inputs = row.querySelectorAll('[name^="fields["]');
            inputs.forEach(input => {
                input.name = input.name.replace(/^fields\[\d+\]/, `fields[${i}]`);
            });

            // Update sub-fields inside this row
            const subFieldRows = row.querySelectorAll('.sub-field-row');
            subFieldRows.forEach((subRow, sIndex) => {
                const subInputs = subRow.querySelectorAll(`[name^="fields[${i}][settings][sub_fields]["]`);
                subInputs.forEach(subInput => {
                    subInput.name = subInput.name.replace(/\[settings\]\[sub_fields\]\[\d+\]/, `[settings][sub_fields][${sIndex}]`);
                });
            });
        });
    });
});
</script>
@endsection
