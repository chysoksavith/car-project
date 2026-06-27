<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Car Inspections' }]"
            description="Manage technical inspection checklists for cars."
            class="mb-6"
        />



        <!-- Car Selection Sidebar -->
        <CarSelectSidebar
            v-model="showCarSidebar"
            :cars="cars"
            :selected-car-id="selectedCarId"
            @select="handleCarSelect"
        />

        <!-- Car Details -->
        <div v-if="selectedCar" class="card bg-base-100 shadow-sm mb-6">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="card-title text-lg mb-0">Car Details</h3>
                    <button
                        @click="showCarSidebar = true"
                        class="btn btn-sm btn-primary"
                    >
                        <i class="fa-solid fa-car"></i>
                        {{ selectedCar.name }}
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-base-content/70">Name</span>
                        <div class="font-medium">{{ selectedCar.name }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-base-content/70">Maker / Model</span>
                        <div class="font-medium">{{ selectedCar.maker?.name }} / {{ selectedCar.car_model?.name }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-base-content/70">Plate Number</span>
                        <div class="font-medium">{{ selectedCar.plate_number || '-' }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-base-content/70">Year</span>
                        <div class="font-medium">{{ selectedCar.year }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-base-content/70">Color</span>
                        <div class="font-medium">{{ selectedCar.color?.name || '-' }}</div>
                    </div>
                    <div>
                        <span class="text-sm text-base-content/70">Fuel</span>
                        <div class="font-medium">{{ selectedCar.fuel?.name || '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspection List -->
        <div v-if="selectedCar && Object.keys(groupedInspections).length > 0" class="card bg-base-100 shadow-sm">
            <div class="card-body p-0">
                <!-- Tabs -->
                <div class="tabs tabs-boxed bg-base-200 p-2">
                    <a
                        v-for="(subcategories, mainCategory) in groupedInspections"
                        :key="mainCategory"
                        class="tab tab-sm"
                        :class="{ 'tab-active': activeTab === mainCategory }"
                        @click="activeTab = mainCategory"
                    >
                        {{ mainCategory }}
                    </a>
                </div>

                <!-- Tab Content -->
                <div
                    v-for="(items, mainCategory) in groupedInspections"
                    :key="'content-' + mainCategory"
                    class="p-4 w-full"
                    :class="{ 'hidden': activeTab !== mainCategory }"
                >
                    <!-- Inspection Items as Compact Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3">
                        <div
                            v-for="(item, index) in items"
                            :key="item.id"
                            class="p-3 bg-base-200 rounded-lg hover:bg-base-300 transition-colors"
                        >
                            <!-- Item Name -->
                            <div class="mb-3">
                                <span class="text-sm font-bold block">
                                    {{ item.inspection_item.name_kh || item.inspection_item.name_en }}
                                </span>
                            </div>

                            <!-- Price Section -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-base-content/60">Default:</span>
                                <span class="text-sm font-semibold">${{ item.inspection_item.default_price || 0 }}</span>
                            </div>

                            <!-- Actual Cost -->
                            <div class="relative">
                                <input
                                    type="number"
                                    v-model.number="item.cost"
                                    @blur="updateCost(item)"
                                    @keyup.enter="updateCost(item)"
                                    step="0.01"
                                    min="0"
                                    class="input input-bordered input-sm w-full pr-8"
                                    placeholder="0.00"
                                />
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-base-content/50 font-medium">$</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Notes Card -->
        <div v-if="selectedCar && Object.keys(groupedInspections).length > 0" class="card bg-base-100 shadow-sm mt-6">
            <div class="card-body">
                <h3 class="card-title text-lg mb-4">General Notes</h3>
                <textarea
                    v-model="generalNotes"
                    @blur="saveGeneralNotes"
                    class="textarea textarea-bordered w-full"
                    rows="4"
                    placeholder="Add general notes about this car's inspection..."
                ></textarea>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="selectedCar" class="card bg-base-100 shadow-sm">
            <div class="card-body text-center py-12">
                <i class="fa-solid fa-clipboard-check text-6xl text-base-content/20 mb-4"></i>
                <h3 class="text-lg font-medium text-base-content mb-2">No Inspections Found</h3>
                <p class="text-sm text-base-content/70">
                    Inspections will be automatically initialized for this car.
                </p>
            </div>
        </div>

        <!-- No Car Selected -->
        <div v-else class="flex flex-col items-center justify-center min-h-[400px] bg-base-100/50 rounded-box border-2 border-dashed border-base-300 p-12 text-center">
            <div class="bg-base-200 w-24 h-24 rounded-full flex items-center justify-center mb-6">
                <i class="fa-solid fa-car-side text-4xl text-base-content/40"></i>
            </div>
            <h3 class="text-xl font-bold text-base-content mb-2">No Car Selected</h3>
            <p class="text-base-content/60 max-w-sm mb-8">
                Please select a car from the list to view and manage its detailed inspection checklist.
            </p>
            <button
                @click="showCarSidebar = true"
                class="btn btn-primary btn-lg gap-2"
            >
                <i class="fa-solid fa-car"></i>
                Select a Car Now
            </button>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "@/Components/Button.vue";
import CarSelectSidebar from "@/Components/CarSelectSidebar.vue";
import type { CarInspection } from "@/Types/inspection-item";

const props = defineProps<{
    cars: any[];
    selectedCar: any;
    inspections: CarInspection[];
    groupedInspections: Record<string, CarInspection[]>;
    selectedCarId: number | null;
}>();

const selectedCarId = ref<number | null>(props.selectedCarId);
const inspections = ref<CarInspection[]>(props.inspections);
const activeTab = ref<string>('');
const generalNotes = ref<string>('');
const showCarSidebar = ref<boolean>(false);

// Ensure active tab is set correctly, especially when switching cars
watch(() => props.groupedInspections, (newGrouped) => {
    const categories = Object.keys(newGrouped);
    if (categories.length > 0 && !categories.includes(activeTab.value)) {
        activeTab.value = categories[0];
    }
}, { immediate: true });

// Select car and navigate
const handleCarSelect = (carId: number) => {
    selectedCarId.value = carId;
    router.get(route('admin.car-inspections.index'), { car_id: carId }, {
        preserveState: true,
    });
};

// Update inspection cost
const updateCost = (inspection: CarInspection) => {
    router.put(
        route('admin.car-inspections.update-cost', inspection.id),
        { cost: inspection.cost },
        {
            preserveState: true,
            onError: () => {
                // Revert on error
                const original = props.inspections.find(i => i.id === inspection.id);
                if (original) inspection.cost = original.cost;
            },
        }
    );
};

// Update inspection details (note only)
const updateDetails = (inspection: CarInspection) => {
    router.put(
        route('admin.car-inspections.update-details', inspection.id),
        {
            note: inspection.note,
        },
        {
            preserveState: true,
        }
    );
};

// Save general notes
const saveGeneralNotes = () => {
    // TODO: Implement saving general notes to car or separate table
    console.log('Saving general notes:', generalNotes.value);
};
</script>
