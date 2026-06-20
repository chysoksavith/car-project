<template>
    <button 
        class="btn rounded-xl font-bold tracking-wide transition-all"
        :class="[
            variant ? `btn-${variant}` : 'btn-primary',
            { 'btn-disabled opacity-70': disabled || loading }
        ]"
        :disabled="disabled || loading"
        v-bind="$attrs"
    >
        <span v-if="loading" class="loading loading-spinner loading-sm"></span>
        <slot />
    </button>
</template>

<script setup lang="ts">
defineProps<{
    disabled?: boolean;
    loading?: boolean;
    variant?: 'primary' | 'secondary' | 'accent' | 'info' | 'success' | 'warning' | 'error' | 'ghost' | 'link' | 'outline' | 'active';
}>();
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
