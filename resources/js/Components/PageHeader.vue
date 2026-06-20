<template>
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
    >
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-base-content">
                {{ title }}
            </h1>
            <p v-if="description" class="text-base-content/60 text-sm mt-0.5">
                {{ description }}
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    title: string;
    description?: string;
}>();
</script>
