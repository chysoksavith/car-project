<template>
    <div class="relative w-full sm:w-72">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-base-content/40">
            <i class="fa-solid fa-search"></i>
        </div>
        <input
            type="text"
            v-model="model"
            :placeholder="placeholder"
            class="input input-sm input-bordered w-full pl-9 bg-base-200/50 focus:bg-base-100 transition-all rounded-lg"
        />
    </div>
</template>

<script setup lang="ts">
const model = defineModel<string>({ default: '' });

withDefaults(defineProps<{
    placeholder?: string;
}>(), {
    placeholder: 'Search...'
});
</script>
