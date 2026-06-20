<template>
    <div class="card bg-base-100 shadow-sm border border-base-200/80">
        <div class="card-body p-0">
            <!-- Toolbar -->
            <div class="p-4 border-b border-base-200/80 flex justify-between items-center bg-base-50">
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-base-content/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input 
                        type="text" 
                        v-model="internalSearch" 
                        :placeholder="searchPlaceholder || 'Search...'" 
                        class="input input-sm input-bordered w-full pl-9 bg-base-200/50 focus:bg-base-100 transition-all rounded-lg"
                    />
                </div>
                <slot name="toolbar-actions"></slot>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/40 text-base-content/70 border-b border-base-200/80 uppercase text-xs tracking-wider">
                            <th v-for="col in columns" :key="col.key" :class="col.class || ''" class="font-semibold py-4">
                                {{ col.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data.data" :key="item.id" class="hover:bg-base-200/30 transition-colors border-b border-base-200/50">
                            <td v-for="col in columns" :key="col.key" :class="col.class || ''">
                                <!-- Render custom slot if it exists, otherwise print the raw property -->
                                <slot :name="`cell(${col.key})`" :item="item">
                                    {{ item[col.key] }}
                                </slot>
                            </td>
                        </tr>
                        
                        <tr v-if="!data.data || data.data.length === 0">
                            <td :colspan="columns.length" class="text-center py-12 text-base-content/50">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <p class="text-lg font-medium">No results found</p>
                                    <p class="text-sm mt-1">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="p-4 border-t border-base-200/80 bg-base-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-base-content/60">
                    Showing <span class="font-medium text-base-content">{{ data.from || 0 }}</span> to <span class="font-medium text-base-content">{{ data.to || 0 }}</span> of <span class="font-medium text-base-content">{{ data.total || 0 }}</span> results
                </p>
                <Pagination v-if="data.links" :links="data.links" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Pagination from './Pagination.vue';

const props = defineProps<{
    data: any;
    columns: Array<{ key: string, label: string, class?: string }>;
    searchRoute: string;
    searchQuery?: string;
    searchPlaceholder?: string;
}>();

const internalSearch = ref(props.searchQuery || '');

let searchTimeout: any;
watch(internalSearch, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(props.searchRoute, { search: value }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 300);
});
</script>
