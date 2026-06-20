<template>
    <label v-if="value || $slots.default" class="label pb-1">
        <span class="label-text font-medium text-base-content/80">
            <slot>{{ value }}</slot>
            <span v-if="required" class="text-error ml-1" aria-hidden="true">*</span>
        </span>
    </label>
</template>

<script setup lang="ts">
defineProps<{
    value?: string;
    required?: boolean;
}>();
</script>
