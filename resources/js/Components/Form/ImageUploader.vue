<template>
    <div class="w-full">
        <label v-if="label" class="label pb-1">
            <span class="label-text font-medium text-base-content">{{ label }}</span>
        </label>
        
        <!-- Existing Images Display -->
        <div v-if="visibleExistingImages.length > 0" class="mb-5 p-4 rounded-xl border border-base-300 bg-base-100/50">
            <h4 class="text-xs font-semibold text-base-content/60 uppercase tracking-wider mb-3">Previously Uploaded Images</h4>
            <div class="flex flex-wrap gap-4">
                <div
                    v-for="img in visibleExistingImages"
                    :key="img.id"
                    class="relative w-28 h-28 rounded-lg overflow-hidden border border-base-300 bg-base-200 group shadow-sm transition-all duration-200 hover:shadow-md hover:scale-[1.02]"
                >
                    <img
                        :src="img.url"
                        class="object-cover w-full h-full"
                    />
                    <div class="absolute inset-0 bg-base-300/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-200 flex items-center justify-center">
                        <button
                            type="button"
                            @click.stop="removeExistingImage(img.id)"
                            class="btn btn-circle btn-error btn-sm shadow-lg scale-75 group-hover:scale-100 transition-transform duration-200"
                            title="Remove image"
                        >
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FilePond Uploader for New Images -->
        <div class="filepond-wrapper relative" :class="{ 'has-error': error }">
            <file-pond
                name="filepond"
                ref="pond"
                label-idle="<div class='flex flex-col items-center justify-center gap-2'><i class='fa-solid fa-cloud-arrow-up text-4xl text-base-content/30'></i><p class='text-[15px] font-medium text-base-content/80'>Drag & Drop your images or <span class='text-primary font-semibold cursor-pointer hover:underline'>Browse</span></p><p class='text-xs text-base-content/50 mt-1'>Supports JPG, PNG, GIF, WEBP</p></div>"
                :allow-multiple="true"
                accepted-file-types="image/jpeg, image/png, image/webp, image/gif, image/svg+xml"
                @updatefiles="handleUpdateFiles"
                class="file-pond-custom"
            />
        </div>

        <div v-if="error" class="text-error text-sm mt-2 flex items-center gap-1.5 font-medium">
            <i class="fa-solid fa-circle-exclamation text-sm"></i>
            {{ error }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';

// Import Vue FilePond
import vueFilePond from 'vue-filepond';

// Import FilePond styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

// Import FilePond plugins
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview
);

const props = withDefaults(defineProps<{
    modelValue?: File[];
    existingImages?: any[];
    deletedImages?: any[];
    error?: string;
    label?: string;
}>(), {
    modelValue: () => [],
    existingImages: () => [],
    deletedImages: () => [],
    label: 'Images'
});

const emit = defineEmits(['update:modelValue', 'update:deletedImages']);

const pond = ref(null);

const validExistingImages = computed(() => {
    return Array.isArray(props.existingImages) ? props.existingImages.filter(i => i && i.url) : [];
});

const visibleExistingImages = computed(() => {
    const deleted = new Set(props.deletedImages);
    return validExistingImages.value.filter(img => !deleted.has(img.id));
});

const removeExistingImage = (id: any) => {
    emit('update:deletedImages', [...props.deletedImages, id]);
};

// Handle files from FilePond
const handleUpdateFiles = (fileItems: any[]) => {
    // Extract native JS File objects
    const files = fileItems.map(fileItem => fileItem.file);
    emit('update:modelValue', files);
};

// Clear FilePond if modelValue is cleared externally (e.g. form submit success)
watch(() => props.modelValue, (newVal) => {
    if ((!newVal || newVal.length === 0) && pond.value) {
        // Only clear if pond has files, to avoid infinite loops
        if ((pond.value as any).getFiles().length > 0) {
             (pond.value as any).removeFiles();
        }
    }
}, { deep: true });
</script>

<style>
/* Modern FilePond Styling for Tailwind / DaisyUI */

/* Hide FilePond's internal background panels to prevent duplicate borders */
.filepond-wrapper .filepond--panel-root {
    border: none !important;
    background-color: transparent !important;
}

/* Apply our custom borders and backgrounds directly to the main root container */
.filepond-wrapper .filepond--root {
    margin-bottom: 0 !important;
    font-family: inherit !important;
    background-color: transparent !important;
    border: 2px dashed #9ca3af !important; /* tailwind gray-400 */
    border-radius: 0.75rem !important;
    transition: all 0.2s ease !important;
}

/* Dark mode compatibility */
@media (prefers-color-scheme: dark) {
    .filepond-wrapper .filepond--root {
        border-color: #4b5563 !important; /* gray-600 */
    }
}

.filepond-wrapper:hover .filepond--root {
    background-color: rgba(156, 163, 175, 0.1) !important;
    border-color: #6b7280 !important; /* gray-500 */
}

.filepond-wrapper .filepond--drop-label {
    color: inherit !important;
    min-height: 100px !important;
    cursor: pointer !important;
}

.filepond-wrapper .filepond--label-action {
    text-decoration: none !important;
}

/* Image preview styling */
.filepond-wrapper .filepond--item-panel {
    background-color: #374151 !important; /* gray-700 */
    border-radius: 0.5rem !important;
}

.filepond-wrapper .filepond--image-preview-overlay-success {
    color: #10b981 !important; /* emerald-500 */
}

/* Error state styling */
.filepond-wrapper.has-error .filepond--root {
    border-color: #ef4444 !important; /* red-500 */
    background-color: rgba(239, 68, 68, 0.05) !important;
}
.filepond-wrapper.has-error:hover .filepond--root {
    border-color: #dc2626 !important; /* red-600 */
}
</style>
