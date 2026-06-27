<template>
    <div>
        <!-- Backdrop -->
        <div 
            v-if="modelValue"
            class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 transition-opacity"
            @click="$emit('update:modelValue', false)"
        ></div>

        <!-- Sidebar / Drawer -->
        <div
            class="fixed inset-y-0 right-0 z-50 w-full md:w-[480px] bg-base-100 shadow-2xl transform transition-transform duration-300 flex flex-col border-l border-base-200"
            :class="modelValue ? 'translate-x-0' : 'translate-x-full'"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-base-200 bg-base-100/80 backdrop-blur-md sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                        <i class="fa-solid fa-box text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-base-content leading-tight">Container Info</h2>
                        <p class="text-sm text-base-content/70 mt-1 flex items-center gap-2" v-if="container">
                            <span class="font-semibold">{{ container.bl_number }}</span>
                            <span class="w-1 h-1 rounded-full bg-base-content/30"></span>
                            <span class="uppercase tracking-wide text-[10px] font-bold px-2 py-0.5 rounded-full bg-base-200">{{ container.status?.replace('_', ' ') || 'Unknown' }}</span>
                        </p>
                    </div>
                </div>
                <button 
                    @click="$emit('update:modelValue', false)"
                    class="btn btn-sm btn-circle btn-ghost bg-base-200/50 hover:bg-base-200"
                >
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Content / Car List -->
            <div class="flex-1 overflow-y-auto p-6 bg-base-50">
                <div v-if="!container"></div>
                <div v-else-if="!container.cars || container.cars.length === 0" class="flex flex-col items-center justify-center py-16 text-base-content/40">
                    <div class="w-24 h-24 rounded-full bg-base-200 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-car-side text-4xl opacity-50"></i>
                    </div>
                    <p class="font-medium">No cars loaded yet.</p>
                </div>
                <div v-else class="flex flex-col gap-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-base-content/70 uppercase tracking-wider">
                            Cars Loaded
                        </h3>
                        <div class="badge badge-primary font-bold">{{ container.cars.length }}</div>
                    </div>
                    
                    <!-- Car Item Card -->
                    <CarCard
                        v-for="(car, index) in container.cars" 
                        :key="car.id"
                        :car="car"
                        :index="index"
                        :show-index="true"
                        :href="route('admin.cars.show', car.id)"
                    />
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 border-t border-base-200 bg-base-100/90 backdrop-blur-md sticky bottom-0 z-10" v-if="container">
                <div class="flex justify-between items-center px-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center text-success">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] uppercase font-bold tracking-wider text-base-content/50">Total Shipping</span>
                            <span class="font-bold text-xl text-base-content">${{ Number(container.total_shipping_cost || 0).toLocaleString() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import CarCard from '@/Components/CarCard.vue';

const props = defineProps<{
    modelValue: boolean;
    container: any;
}>();

defineEmits(['update:modelValue']);

// Helper function to determine text color (black or white) based on background hex
const getContrastYIQ = (hexcolor: string) => {
    hexcolor = hexcolor.replace("#", "");
    if (hexcolor.length === 3) {
        hexcolor = hexcolor.split('').map(h => h + h).join('');
    }
    const r = parseInt(hexcolor.substring(0, 2), 16) || 204;
    const g = parseInt(hexcolor.substring(2, 4), 16) || 204;
    const b = parseInt(hexcolor.substring(4, 6), 16) || 204;
    const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
    return (yiq >= 128) ? 'black' : 'white';
};
</script>
