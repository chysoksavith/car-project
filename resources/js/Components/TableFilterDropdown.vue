<template>
    <div class="relative">
        <button
            @click="toggleDropdown"
            class="btn btn-sm btn-ghost gap-2"
            :class="{ 'btn-active': isOpen }"
        >
            <i class="fa-solid fa-filter"></i>
            <span v-if="hasActiveFilters" class="badge badge-primary badge-xs"></span>
        </button>

        <div
            v-if="isOpen"
            class="absolute right-0 top-full mt-2 z-50 w-72 bg-white rounded-lg shadow-lg border border-gray-200 p-4"
            @click.stop
        >
            <div class="mb-3">
                <h3 class="text-sm font-semibold text-gray-700">Filters</h3>
            </div>

            <div v-for="(filter, key) in filters" :key="key" class="mb-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">
                    {{ filter.label }}
                </label>

                <select
                    v-model="localFilters[key]"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                    @click.stop
                    @change.stop
                >
                    <option value="">{{ filter.placeholder || 'All' }}</option>
                    <option
                        v-for="option in filter.options"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-200">
                <button
                    @click="clearFilters"
                    class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-colors"
                >
                    Clear
                </button>
                <button
                    @click="applyAndClose"
                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors"
                >
                    Apply
                </button>
            </div>
        </div>

        <!-- Click outside to close -->
        <div
            v-if="isOpen"
            class="fixed inset-0 z-40"
            @click="isOpen = false"
        ></div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    filters: {
        [key: string]: {
            label: string;
            placeholder?: string;
            options: Array<{ value: string | number; label: string }>;
        };
    };
    route: string;
    activeFilters?: { [key: string]: string | number | null | undefined };
}>();

const localFilters = ref<Record<string, string | number | null>>({});
const isOpen = ref(false);

// # Initialize local filters from active filters
Object.keys(props.filters).forEach((key) => {
    localFilters.value[key] = props.activeFilters?.[key] || "";
});

// # Toggle dropdown
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const hasActiveFilters = computed(() => {
    return Object.values(localFilters.value).some((value) => value !== "" && value !== null);
});

// # Apply filter with debounce
let filterTimeout: any;
const applyFilter = () => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        const params: Record<string, any> = {};
        Object.keys(localFilters.value).forEach((key) => {
            if (localFilters.value[key] !== "" && localFilters.value[key] !== null) {
                params[key] = localFilters.value[key];
            }
        });

        router.get(props.route, params, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }, 300);
};

// # Apply filter and close dropdown
const applyAndClose = () => {
    applyFilter();
    isOpen.value = false;
};

// # Clear all filters
const clearFilters = () => {
    Object.keys(localFilters.value).forEach((key) => {
        localFilters.value[key] = "";
    });

    router.get(props.route, {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
    isOpen.value = false;
};

// # Watch for changes in active filters from props
watch(
    () => props.activeFilters,
    (newFilters) => {
        if (newFilters) {
            Object.keys(newFilters).forEach((key) => {
                localFilters.value[key] = newFilters[key] || "";
            });
        }
    },
    { deep: true }
);
</script>
