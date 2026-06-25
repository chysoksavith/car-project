<template>
    <div class="w-full">
        <label v-if="label" class="label pb-1">
            <span class="label-text font-medium text-base-content">{{ label }}</span>
        </label>
        
        <!-- FilePond Uploader for All Images (Existing + New) -->
        <div class="filepond-wrapper relative" :class="{ 'has-error': error }">
            <file-pond
                name="filepond"
                ref="pond"
                :files="initialFiles"
                label-idle="<div class='flex flex-col items-center justify-center gap-2'><i class='fa-solid fa-cloud-arrow-up text-4xl text-base-content/30'></i><p class='text-[15px] font-medium text-base-content/80'>Drag & Drop your images or <span class='text-primary font-semibold cursor-pointer hover:underline'>Browse</span></p><p class='text-xs text-base-content/50 mt-1'>Supports JPG, PNG, GIF, WEBP</p></div>"
                :allow-multiple="allowMultiple"
                :allow-reorder="true"
                accepted-file-types="image/jpeg, image/png, image/webp, image/gif, image/svg+xml"
                :image-preview-max-height="256"
                :file-poster-max-height="256"
                @updatefiles="handleUpdateFiles"
                @reorderfiles="handleUpdateFiles"
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
import { ref, watch } from 'vue';

// Import Vue FilePond
import vueFilePond from 'vue-filepond';

// Import FilePond styles
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
// Note: We need the poster plugin to properly display local files without fetching them
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.min.css';

// Import FilePond plugins
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
    FilePondPluginFilePoster
);

const props = withDefaults(defineProps<{
    modelValue?: File[];
    existingImages?: any[];
    deletedImages?: any[];
    existingImageOrder?: any[];
    error?: string;
    label?: string;
    allowMultiple?: boolean;
}>(), {
    modelValue: () => [],
    existingImages: () => [],
    deletedImages: () => [],
    existingImageOrder: () => [],
    label: 'Images',
    allowMultiple: true
});

const emit = defineEmits(['update:modelValue', 'update:deletedImages', 'update:existingImageOrder']);

const pond = ref(null);

const initialFiles = ref(
    (props.existingImages || []).filter(i => i && i.url).map(img => ({
        source: img.id.toString(),
        options: {
            type: 'local',
            file: {
                name: img.file_name || 'image',
                size: img.size || 0,
                type: img.mime_type || 'image/jpeg'
            },
            metadata: {
                poster: img.url
            }
        }
    }))
);

// Handle files from FilePond
const handleUpdateFiles = (fileItems: any[]) => {
    const existingIds: number[] = [];
    const newFiles: File[] = [];

    fileItems.forEach(fileItem => {
        if (fileItem.origin === 2 || fileItem.origin === 3) {
            existingIds.push(parseInt(fileItem.source));
        } else if (fileItem.origin === 1) {
            newFiles.push(fileItem.file);
        }
    });

    emit('update:existingImageOrder', existingIds);
    emit('update:modelValue', newFiles);

    const originalIds = (props.existingImages || []).map(img => img.id);
    const deleted = originalIds.filter(id => !existingIds.includes(id));
    
    emit('update:deletedImages', deleted);
};

// Clear FilePond if modelValue is cleared externally (e.g. form submit success)
watch(() => props.modelValue, (newVal) => {
    if ((!newVal || newVal.length === 0) && pond.value) {
        if ((pond.value as any).getFiles().filter((f: any) => f.origin === 1).length > 0) {
            const pondFiles = (pond.value as any).getFiles();
            pondFiles.forEach((file: any) => {
                if (file.origin === 1) {
                    (pond.value as any).removeFile(file.id);
                }
            });
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
