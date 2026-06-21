<template>
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
    >
        <div>
            <div v-if="breadcrumbs && breadcrumbs.length > 0" class="breadcrumbs text-sm">
                <ul>
                    <li v-for="(item, index) in breadcrumbs" :key="index">
                        <Link v-if="item.url" :href="item.url">
                            {{ item.label }}
                        </Link>
                        <span v-else class="font-medium">
                            {{ item.label }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface BreadcrumbItem {
    label: string;
    url?: string;
}

defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>
