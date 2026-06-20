<template>
    <div class="card bg-base-100 shadow-sm border border-base-200/80">
        <div class="card-body p-0">
            <!-- Toolbar -->
            <div class="p-4 border-b border-base-200/80 flex justify-between items-center bg-base-50">
                <div class="relative w-full sm:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-base-content/40">
                        <i class="fa-solid fa-search"></i>
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
                                    <i class="fa-solid fa-box-open text-4xl opacity-20 mb-3"></i>
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
