<template>
    <button
        class="btn rounded-xl font-bold tracking-wide transition-all gap-2"
        :class="[
            variant ? `btn-${variant}` : 'btn-primary',
            size ? `btn-${size}` : '',
            { 'btn-disabled opacity-70': disabled || loading },
        ]"
        :disabled="disabled || loading"
        v-bind="$attrs"
    >
        <span v-if="loading" class="loading loading-spinner loading-sm"></span>
        <i v-if="icon && !loading" :class="icon"></i>
        <slot />
    </button>
</template>

<script setup lang="ts">
defineProps<{
    disabled?: boolean;
    loading?: boolean;
    variant?:
        | "primary"
        | "secondary"
        | "accent"
        | "info"
        | "success"
        | "warning"
        | "error"
        | "ghost"
        | "link"
        | "outline"
        | "active";
    size?: "xs" | "sm" | "md" | "lg";
    icon?: string;
}>();
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
