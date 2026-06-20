<template>
    <div v-show="message" class="label pt-1 pb-0">
        <span class="label-text-alt text-error font-medium">{{ message }}</span>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>
