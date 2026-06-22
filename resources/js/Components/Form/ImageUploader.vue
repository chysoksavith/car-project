<template>
    <div class="w-full">
        <label v-if="label" class="label"><span class="label-text">{{ label }}</span></label>
        <input 
            type="file" 
            multiple 
            @change="handleImageUpload" 
            class="file-input file-input-bordered w-full file-input-primary" 
            accept="image/*"
        />
        <div v-if="error" class="text-error text-sm mt-1">
            {{ error }}
        </div>
        
        <div class="flex flex-wrap gap-4 mt-4" v-if="hasImages">
            <!-- Existing Images -->
            <div
                v-for="img in visibleExistingImages"
                :key="img.id"
                class="relative w-24 h-24 rounded overflow-hidden border bg-base-200 group"
            >
                <img
                    :src="img.url"
                    class="object-cover w-full h-full"
                />
                <button
                    type="button"
                    @click.stop="removeExistingImage(img.id)"
                    class="absolute top-1 right-1 btn btn-circle btn-xs btn-error opacity-0 group-hover:opacity-100 transition-opacity z-10"
                >
                    ✕
                </button>
            </div>

            <!-- New Image Previews -->
            <div
                v-for="(url, i) in imagePreviews"
                :key="'new-' + i"
                class="relative w-24 h-24 rounded overflow-hidden border border-primary bg-base-200 group"
            >
                <img
                    :src="url"
                    class="object-cover w-full h-full opacity-80"
                />
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/10 text-white text-xs font-bold drop-shadow pointer-events-none"
                >
                    New
                </div>
                <button
                    type="button"
                    @click.stop="removeNewImage(i)"
                    class="absolute top-1 right-1 btn btn-circle btn-xs btn-error opacity-0 group-hover:opacity-100 transition-opacity z-10"
                >
                    ✕
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onBeforeUnmount } from 'vue';

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

const imagePreviews = ref<string[]>([]);

const validExistingImages = computed(() => {
    return Array.isArray(props.existingImages) ? props.existingImages.filter(i => i && i.url) : [];
});

const visibleExistingImages = computed(() => {
    const deleted = new Set(props.deletedImages);
    return validExistingImages.value.filter(img => !deleted.has(img.id));
});

const hasImages = computed(() => {
    return visibleExistingImages.value.length > 0 || imagePreviews.value.length > 0;
});

const removeExistingImage = (id: any) => {
    emit('update:deletedImages', [...props.deletedImages, id]);
};

const removeNewImage = (index: number) => {
    const newFiles = [...props.modelValue];
    newFiles.splice(index, 1);
    
    URL.revokeObjectURL(imagePreviews.value[index]);
    
    const newPreviews = [...imagePreviews.value];
    newPreviews.splice(index, 1);
    imagePreviews.value = newPreviews;
    
    emit('update:modelValue', newFiles);
};

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const newFiles = Array.from(target.files);
        
        // Append instead of replace
        const allFiles = [...props.modelValue, ...newFiles];
        emit('update:modelValue', allFiles);
        
        const newPreviews = newFiles.map((file) => URL.createObjectURL(file));
        imagePreviews.value = [...imagePreviews.value, ...newPreviews];
        
        // Reset file input
        target.value = '';
    }
};

onBeforeUnmount(() => {
    imagePreviews.value.forEach((url) => URL.revokeObjectURL(url));
});

// Watch if modelValue gets cleared externally (e.g. form reset)
watch(() => props.modelValue, (newVal) => {
    if (!newVal || newVal.length === 0) {
        imagePreviews.value.forEach((url) => URL.revokeObjectURL(url));
        imagePreviews.value = [];
    }
}, { deep: true });
</script>
