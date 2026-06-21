<template>
    <component
        :is="href ? Link : 'button'"
        :href="href"
        class="btn font-bold tracking-wide transition-all gap-2"
        :class="[
            variant ? `btn-${variant}` : 'btn-primary',
            size ? `btn-${size}` : '',
            { 'btn-disabled opacity-70': disabled || loading },
        ]"
        :disabled="href ? undefined : (disabled || loading)"
        v-bind="$attrs"
    >
        <span v-if="loading" class="loading loading-spinner loading-sm"></span>
        <i v-if="icon && !loading" :class="icon"></i>
        <slot />
    </component>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    href?: string;
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
