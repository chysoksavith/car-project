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
                    <Link 
                        v-for="(car, index) in container.cars" 
                        :key="car.id"
                        :href="route('admin.cars.show', car.id)"
                        class="group relative bg-base-100 rounded-2xl p-3 flex gap-4 border border-base-200/60 shadow-sm hover:shadow-md transition-all duration-200 hover:border-primary/30 block text-left"
                    >
                        <!-- Index Number -->
                        <div class="absolute -top-2 -left-2 w-6 h-6 rounded-full bg-base-200 flex items-center justify-center text-[10px] font-bold text-base-content/60 border border-base-100 shadow-sm z-10 group-hover:bg-primary group-hover:text-primary-content transition-colors">
                            {{ index + 1 }}
                        </div>

                        <!-- Thumbnail -->
                        <div class="w-28 h-28 rounded-xl bg-base-200 flex-shrink-0 relative overflow-hidden group-hover:shadow-inner">
                            <img 
                                v-if="car.images && car.images.length > 0" 
                                :src="car.images[0].url" 
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                                alt="Car image"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-base-content/20 bg-gradient-to-br from-base-200 to-base-300">
                                <i class="fa-solid fa-car text-3xl opacity-50"></i>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 flex flex-col justify-between py-1 overflow-hidden">
                            <div>
                                <h3 class="font-bold text-base text-base-content leading-tight truncate group-hover:text-primary transition-colors" :title="car.name">
                                    {{ car.name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-medium text-base-content/60">{{ car.maker?.name || 'Unknown' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-base-300"></span>
                                    <span class="text-xs font-medium text-base-content/60 truncate">{{ car.car_model?.name || 'Unknown' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-base-200 text-xs font-medium text-base-content/80">
                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                    {{ car.year || 'N/A' }}
                                </div>
                                <div v-if="car.color" class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-base-200 text-xs font-medium" :style="{ backgroundColor: car.color.hex_code + '20', color: car.color.hex_code !== '#ffffff' ? car.color.hex_code : '#333' }">
                                    <span class="w-2.5 h-2.5 rounded-full border border-black/10" :style="{ backgroundColor: car.color.hex_code }"></span>
                                    {{ car.color.name }}
                                </div>
                                <div class="flex items-center gap-1.5 px-2 py-1 rounded-md bg-base-200 text-xs font-mono text-base-content/70 truncate max-w-[120px]" :title="car.body_number">
                                    <i class="fa-solid fa-hashtag text-[10px]"></i>
                                    {{ car.body_number || 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </Link>
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
