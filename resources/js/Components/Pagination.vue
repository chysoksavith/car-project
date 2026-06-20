<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-1 mt-6">
        <template v-for="(link, key) in links" :key="key">
            <div 
                v-if="link.url === null" 
                class="btn btn-sm btn-disabled opacity-50" 
                v-html="link.label" 
            />
            <Link 
                v-else 
                class="btn btn-sm" 
                :class="link.active ? 'btn-primary' : 'bg-base-200 hover:bg-base-300 border-transparent'" 
                :href="link.url" 
                v-html="link.label" 
            />
        </template>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    links: any[];
}>();
</script>
