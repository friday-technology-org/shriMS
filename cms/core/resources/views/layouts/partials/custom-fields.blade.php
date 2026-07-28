@if($fieldGroups && $fieldGroups->count() > 0)
    <div class="mt-8">
        @foreach($fieldGroups as $group)
            <div class="border border-neutral rounded-lg bg-neutral-bg dark:border-dark-neutral-border pb-6 dark:bg-dark-neutral-bg mb-6">
                <div class="bg-neutral rounded-t-lg py-[15px] pl-[18px] mb-[27px] dark:bg-dark-neutral-border">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px]">{{ $group->title }}</p>
                </div>
                
                <div class="px-5 flex flex-col gap-6">
                    @foreach($group->fields as $field)
                        @php
                            $metaValue = isset($post) ? $post->getMeta($field->name, $field->default_value) : old('meta.'.$field->name, $field->default_value);
                        @endphp
                        <div>
                            <p class="text-gray-1100 text-base leading-4 font-medium capitalize mb-[10px] dark:text-gray-dark-1100">
                                {{ $field->label }}
                                @if($field->required) <span class="text-red-500">*</span> @endif
                            </p>

                            @if($field->instructions)
                                <p class="text-xs text-gray-500 dark:text-gray-dark-500 mb-2">{{ $field->instructions }}</p>
                            @endif

                            @if($field->type === 'textarea')
                                <textarea name="meta[{{ $field->name }}]" id="meta_{{ $field->name }}" class="textarea w-full text-gray-800 dark:text-white resize-y rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-4 min-h-[100px] focus:outline-none placeholder:text-inherit" {{ $field->required ? 'required' : '' }}>{{ $metaValue }}</textarea>
                            
                            @elseif($field->type === 'wysiwyg')
                                @include('cms-core::layouts.partials.wysiwyg-editor', ['name' => 'meta['.$field->name.']', 'fieldId' => 'meta_'.$field->name, 'value' => $metaValue, 'height' => '250px'])
                            
                            @elseif($field->type === 'number')
                                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                                    <input name="meta[{{ $field->name }}]" id="meta_{{ $field->name }}" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" type="number" value="{{ $metaValue }}" {{ $field->required ? 'required' : '' }}>
                                </div>
                            
                            @elseif($field->type === 'image')
                                @php
                                    $existingMedia = null;
                                    if ($metaValue && is_numeric($metaValue)) {
                                        $existingMedia = \Cms\Core\Models\Media::find((int) $metaValue);
                                    }
                                    $existingMediaId   = $existingMedia ? $existingMedia->id : 'null';
                                    $existingMediaUrl  = $existingMedia ? "'" . addslashes($existingMedia->thumbnailUrl('medium')) . "'" : 'null';
                                @endphp

                                <div
                                    x-data="{
                                        imageId:   {{ $existingMediaId }},
                                        imageUrl:  {{ $existingMediaUrl }},
                                        pickImage() {
                                            window.openMediaPicker((media) => {
                                                this.imageId   = media.id;
                                                this.imageUrl  = media.medium_url || media.url;
                                            });
                                        },
                                        clearImage() {
                                            this.imageId   = null;
                                            this.imageUrl  = null;
                                        }
                                    }"
                                    class="flex items-center gap-4"
                                >
                                    {{-- Hidden input stores the media ID --}}
                                    <input type="hidden" name="meta[{{ $field->name }}]" id="meta_{{ $field->name }}" x-model="imageId">

                                    {{-- Compact thumbnail preview (only when an image is set) --}}
                                    <div x-show="imageUrl" class="relative flex-shrink-0">
                                        <div class="w-20 h-20 rounded-xl overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-gray-100 dark:bg-[#1f2130]">
                                            <img :src="imageUrl" alt="Selected" class="w-full h-full object-cover">
                                        </div>
                                        {{-- Small ✕ remove button --}}
                                        <button
                                            type="button"
                                            @click="clearImage()"
                                            class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition-colors"
                                            title="Remove image"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Label + button column --}}
                                    <div class="flex flex-col gap-1.5 min-w-0">
                                        <p x-show="!imageId" class="text-xs text-gray-400 dark:text-gray-dark-500">No image selected</p>

                                        <button
                                            type="button"
                                            @click="pickImage()"
                                            class="self-start btn normal-case h-fit min-h-fit transition-all duration-200 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]"
                                        >
                                            <span class="flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span x-text="imageId ? 'Change Image' : 'Select Image'"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            
                            @elseif($field->type === 'repeater')
                                @php
                                    $subFields = $field->settings['sub_fields'] ?? [];
                                    $rows = is_array($metaValue) ? $metaValue : [];
                                    // Ensure every row is an object, not a flat string
                                    foreach($rows as $k => $r) { if(!is_array($r)) { $rows[$k] = []; } }
                                @endphp
                                <div class="border border-neutral rounded-lg p-4 bg-gray-50 dark:bg-[#1f2130] dark:border-dark-neutral-border"
                                     x-data="repeaterField({{ json_encode($rows) }}, {{ json_encode($subFields) }}, '{{ $field->name }}')">
                                    <div class="space-y-4">
                                        <template x-for="(row, index) in rows" :key="row._id">
                                            <div class="relative bg-white dark:bg-dark-neutral-bg p-5 border border-[#E8EDF2] dark:border-[#313442] rounded-lg shadow-sm group">
                                                <div class="flex justify-between items-center mb-4 pb-2 border-b border-[#E8EDF2] dark:border-[#313442]">
                                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Row <span x-text="index + 1"></span></span>
                                                    <button type="button" @click="removeRow(index)" class="flex items-center gap-1 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:hover:text-red-300 rounded px-3 py-1.5 transition-colors text-xs font-bold whitespace-nowrap">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Remove Row
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                                    <template x-for="subField in subFields" :key="subField.name">
                                                        <div class="flex flex-col">
                                                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5" x-text="subField.label"></label>
                                                            
                                                            <!-- Text / Number -->
                                                            <template x-if="subField.type === 'text' || subField.type === 'number'">
                                                                <input :type="subField.type" :name="`meta[${fieldName}][${index}][${subField.name}]`" x-model="row[subField.name]" class="input w-full bg-transparent text-sm rounded border border-[#E8EDF2] dark:border-[#313442] p-2 focus:outline-none dark:text-white">
                                                            </template>
                                                            
                                                            <!-- Textarea / WYSIWYG fallback -->
                                                            <template x-if="subField.type === 'textarea' || subField.type === 'wysiwyg'">
                                                                <textarea :name="`meta[${fieldName}][${index}][${subField.name}]`" x-model="row[subField.name]" class="textarea w-full bg-transparent text-sm rounded border border-[#E8EDF2] dark:border-[#313442] p-2 focus:outline-none dark:text-white min-h-[80px]"></textarea>
                                                            </template>
                                                            
                                                            <!-- Image -->
                                                            <template x-if="subField.type === 'image'">
                                                                <div class="flex flex-col gap-2">
                                                                    <div class="flex items-center gap-2">
                                                                        <input type="text" :name="`meta[${fieldName}][${index}][${subField.name}]`" x-model="row[subField.name]" class="input flex-1 bg-transparent text-sm rounded border border-[#E8EDF2] dark:border-[#313442] p-2 focus:outline-none dark:text-white" placeholder="Media ID">
                                                                        <button type="button" @click="pickImage(row, subField.name)" class="btn normal-case px-3 py-1.5 bg-color-brands text-white rounded text-xs hover:bg-[#9785FF]">Pick</button>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        <!-- Empty State -->
                                        <template x-if="rows.length === 0">
                                            <div class="text-center py-6 border-2 border-dashed border-[#E8EDF2] dark:border-[#313442] rounded-lg">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No rows added yet.</p>
                                            </div>
                                        </template>
                                    </div>
                                    <button type="button" @click="addRow()" class="mt-4 btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] text-white py-[8px] px-[16px] rounded flex items-center gap-2 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        Add Row
                                    </button>
                                </div>

                            @else
                                <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                                    <input name="meta[{{ $field->name }}]" id="meta_{{ $field->name }}" class="input w-full bg-transparent text-sm leading-4 text-gray-800 dark:text-white h-fit min-h-fit py-4 focus:outline-none pl-[13px] placeholder:text-inherit" type="text" value="{{ $metaValue }}" {{ $field->required ? 'required' : '' }}>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script>
    function repeaterField(initialRows, subFields, fieldName) {
        return {
            rows: (initialRows || []).map(row => ({ ...row, _id: Math.random().toString(36).substr(2, 9) })),
            subFields: subFields || [],
            fieldName: fieldName,
            
            addRow() {
                let newRow = { _id: Math.random().toString(36).substr(2, 9) };
                this.subFields.forEach(sf => {
                    newRow[sf.name] = '';
                });
                this.rows.push(newRow);
            },
            
            removeRow(index) {
                this.rows.splice(index, 1);
            },

            pickImage(row, key) {
                if (typeof window.openMediaPicker === 'function') {
                    window.openMediaPicker((media) => {
                        row[key] = media.id;
                    });
                }
            }
        };
    }
    </script>
    @endpush
@endif
