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
@endif
