<template>
    <div class="card bg-base-100 shadow-sm border border-base-200/80">
        <div class="card-body p-0">
            <!-- Toolbar -->
            <div
                class="p-4 border-b border-base-200/80 flex flex-col sm:flex-row justify-end items-start sm:items-center gap-3 bg-base-50"
            >
                <slot name="toolbar-actions"></slot>
                <TableFilterDropdown
                    :filters="filterConfig"
                    :route="searchRoute"
                    :activeFilters="activeFilters"
                    :searchQuery="searchQuery"
                    :searchPlaceholder="searchPlaceholder"
                />
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="text-base-content/60 border-b border-base-200/80">
                            <th
                                v-for="col in columns"
                                :key="col.key"
                                :class="[col.class, { 'text-left': !col.class || (!col.class.includes('text-center') && !col.class.includes('text-right')) }]"
                                class="font-medium bg-transparent align-middle whitespace-nowrap px-4 py-3 text-sm"
                            >
                                {{ col.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in data.data"
                            :key="item.id"
                            class="hover cursor-pointer border-b border-base-200/50 last:border-0 transition-colors"
                            @click="$emit('row-click', item)"
                        >
                            <td
                                v-for="col in columns"
                                :key="col.key"
                                :class="[col.class, { 'text-left': !col.class || (!col.class.includes('text-center') && !col.class.includes('text-right')) }]"
                                class="align-middle px-4 py-3"
                            >
                                <!-- Render custom slot if it exists, otherwise print the raw property -->
                                <slot :name="`cell(${col.key})`" :item="item" :index="index">
                                    <template v-if="col.key === 'no'">
                                        {{ (data.meta?.from ?? data.from ?? 1) + index }}
                                    </template>
                                    <template v-else>
                                        {{ item[col.key] }}
                                    </template>
                                </slot>
                            </td>
                        </tr>

                        <tr v-if="!data.data || data.data.length === 0">
                            <td
                                :colspan="columns.length"
                                class="text-center py-12 text-base-content/50"
                            >
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <i
                                        class="fa-solid fa-box-open text-4xl opacity-20 mb-3"
                                    ></i>
                                    <p class="text-lg font-medium">
                                        No results found
                                    </p>
                                    <p class="text-sm mt-1">
                                        Try adjusting your search criteria
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div
                class="p-4 border-t border-base-200/80 bg-base-50/50 flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <p class="text-sm text-base-content/60">
                    Showing
                    <span class="font-medium text-base-content">{{
                        data.meta?.from ?? data.from ?? 0
                    }}</span>
                    to
                    <span class="font-medium text-base-content">{{
                        data.meta?.to ?? data.to ?? 0
                    }}</span>
                    of
                    <span class="font-medium text-base-content">{{
                        data.meta?.total ?? data.total ?? 0
                    }}</span>
                    results
                </p>
                <Pagination :links="data.meta?.links ?? data.links ?? []" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Pagination from "./Pagination.vue";
import TableFilterDropdown from "./TableFilterDropdown.vue";

const emit = defineEmits(['row-click']);

const props = defineProps<{
    data: any;
    columns: Array<{ key: string; label: string; class?: string }>;
    searchRoute: string;
    searchQuery?: string;
    searchPlaceholder?: string;
    filterConfig?: {
        [key: string]: {
            label: string;
            placeholder?: string;
            options: Array<{ value: string | number; label: string }>;
        };
    };
    activeFilters?: { [key: string]: string | number | null };
}>();
</script>
