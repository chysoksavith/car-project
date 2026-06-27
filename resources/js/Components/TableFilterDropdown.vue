<template>
    <div class="flex items-center gap-2">
        <div class="relative w-full sm:w-72">
            <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-base-content/40"
            >
                <i class="fa-solid fa-search"></i>
            </div>
            <input
                type="text"
                v-model="localSearch"
                :placeholder="searchPlaceholder || 'Search...'"
                class="input input-sm input-bordered w-full pl-9 bg-base-200/50 focus:bg-base-100 transition-all rounded-lg"
            />
        </div>

        <div class="relative" v-if="filters">
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
                class="absolute right-0 top-full mt-2 z-50 w-72 bg-base-100 rounded-lg shadow-lg border border-base-300 p-4"
                @click.stop
            >
                <div class="mb-3">
                    <h3 class="text-sm font-semibold text-base-content">Filters</h3>
                </div>

                <div v-for="(filter, key) in filters" :key="key" class="mb-3">
                    <SelectInput
                        v-model="localFilters[key]"
                        :options="filter.options"
                        :placeholder="filter.placeholder || 'All'"
                        :label="filter.label"
                        selectClass="select-sm"
                        @click.stop
                    />
                </div>

                <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-base-300">
                    <Button
                        @click="clearFilters"
                        variant="ghost"
                        size="xs"
                    >
                        Clear
                    </Button>
                    <Button
                        @click="applyAndClose"
                        variant="primary"
                        size="xs"
                    >
                        Apply
                    </Button>
                </div>
            </div>

            <!-- Click outside to close -->
            <div
                v-if="isOpen"
                class="fixed inset-0 z-40"
                @click="isOpen = false"
            ></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import SelectInput from "./Form/SelectInput.vue";
import Button from "./Button.vue";

const props = defineProps<{
    filters?: {
        [key: string]: {
            label: string;
            placeholder?: string;
            options: Array<{ value: string | number; label: string }>;
        };
    };
    route: string;
    activeFilters?: { [key: string]: string | number | null | undefined };
    searchQuery?: string;
    searchPlaceholder?: string;
}>();

const localFilters = ref<Record<string, string | number | null>>({});
const localSearch = ref(props.searchQuery || "");
const isOpen = ref(false);

// # Initialize local filters from active filters
if (props.filters) {
    Object.keys(props.filters).forEach((key) => {
        localFilters.value[key] = props.activeFilters?.[key] || "";
    });
}

// # Toggle dropdown
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const hasActiveFilters = computed(() => {
    const hasFilterValues = Object.values(localFilters.value).some((value) => value !== "" && value !== null);
    const hasSearchValue = localSearch.value !== "";
    return hasFilterValues || hasSearchValue;
});

// # Apply filter with debounce
let filterTimeout: any;
const applyFilter = () => {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        const params: Record<string, any> = {};

        // Add search if present
        if (localSearch.value !== "") {
            params.search = localSearch.value;
        }

        // Add filters if present
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
    localSearch.value = "";
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

// # Watch for search query from props
watch(
    () => props.searchQuery,
    (newSearch) => {
        if (newSearch !== undefined) {
            localSearch.value = newSearch;
        }
    }
);

// # Watch for local search changes to apply filter
watch(localSearch, () => {
    applyFilter();
});
</script>
