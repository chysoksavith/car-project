<template>
    <div>
        <!-- Backdrop -->
        <div 
            v-if="modelValue"
            class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 transition-opacity"
            @click="$emit('update:modelValue', false)"
        ></div>

        <!-- Modal / Drawer -->
        <div
            class="fixed inset-y-0 right-0 z-50 w-full md:w-[480px] bg-base-100 shadow-2xl transform transition-transform duration-300 flex flex-col border-l border-base-200"
            :class="modelValue ? 'translate-x-0' : 'translate-x-full'"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-base-200 bg-base-100/80 backdrop-blur-md sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i class="fa-solid fa-car text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-base-content leading-tight">Select Car</h2>
                        <p class="text-sm text-base-content/70 mt-1">Choose a car to inspect</p>
                    </div>
                </div>
                <button 
                    @click="$emit('update:modelValue', false)"
                    class="btn btn-sm btn-circle btn-ghost bg-base-200/50 hover:bg-base-200"
                >
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Search -->
            <div class="p-4 border-b border-base-200 bg-base-50">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-base-content/40"></i>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search cars..."
                        class="input input-bordered w-full pl-10"
                    />
                </div>
            </div>

            <!-- Content / Car List -->
            <div class="flex-1 overflow-y-auto p-6 bg-base-50">
                <div v-if="!filteredCars || filteredCars.length === 0" class="flex flex-col items-center justify-center py-16 text-base-content/40">
                    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-car-side text-4xl opacity-50"></i>
                    </div>
                    <p class="font-medium">{{ searchQuery ? 'No cars found' : 'No cars available' }}</p>
                </div>
                <div v-else class="flex flex-col gap-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-base-content/70 uppercase tracking-wider">
                            Available Cars
                        </h3>
                        <div class="badge badge-primary font-bold">{{ filteredCars.length }}</div>
                    </div>
                    
                    <!-- Car Cards -->
                    <CarCard
                        v-for="(car, index) in filteredCars"
                        :key="car.id"
                        :car="car"
                        :index="index"
                        :show-index="false"
                        :is-selected="selectedCarId === car.id"
                        @select="handleSelectCar"
                    />
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 border-t border-base-200 bg-base-100/90 backdrop-blur-md sticky bottom-0 z-10">
                <button
                    @click="$emit('update:modelValue', false)"
                    class="btn btn-primary w-full"
                    :disabled="!selectedCarId"
                >
                    <i class="fa-solid fa-check mr-2"></i>
                    {{ selectedCarId ? 'Confirm Selection' : 'Select a Car' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import CarCard from './CarCard.vue';

const props = defineProps<{
    modelValue: boolean;
    cars: any[];
    selectedCarId?: number | null;
}>();

const emit = defineEmits(['update:modelValue', 'select']);

const searchQuery = ref('');

const filteredCars = computed(() => {
    if (!searchQuery.value) return props.cars;
    
    const query = searchQuery.value.toLowerCase();
    return props.cars.filter(car => 
        car.name?.toLowerCase().includes(query) ||
        car.maker?.name?.toLowerCase().includes(query) ||
        car.car_model?.name?.toLowerCase().includes(query) ||
        car.plate_number?.toLowerCase().includes(query) ||
        car.body_number?.toLowerCase().includes(query)
    );
});

const handleSelectCar = (car: any) => {
    emit('select', car.id);
    emit('update:modelValue', false);
};
</script>
