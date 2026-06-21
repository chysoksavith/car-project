<template>
    <span :class="badgeClasses">
        <slot name="icon">
            <i v-if="icon" :class="icon"></i>
            <div v-else-if="dot" class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></div>
        </slot>
        <slot></slot>
    </span>
</template>

<script setup lang="ts">
import { computed, useSlots } from 'vue';

const slots = useSlots();

const props = withDefaults(defineProps<{
    variant?: 'primary' | 'secondary' | 'accent' | 'ghost' | 'info' | 'success' | 'warning' | 'error' | 'neutral' | '';
    size?: 'lg' | 'md' | 'sm' | 'xs' | '';
    outline?: boolean;
    icon?: string;
    dot?: boolean;
}>(), {
    variant: '',
    size: 'sm',
    outline: false,
    icon: '',
    dot: false,
});

const badgeClasses = computed(() => {
    return [
        'badge',
        'font-medium', 
        (props.icon || props.dot || !!slots.icon) ? 'gap-1.5' : '',
        props.variant ? `badge-${props.variant}` : '',
        props.size ? `badge-${props.size}` : '',
        props.outline ? 'badge-outline' : '',
    ].filter(Boolean).join(' ');
});
</script>
