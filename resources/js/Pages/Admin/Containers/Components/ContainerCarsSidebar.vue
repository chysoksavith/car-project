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
            class="fixed inset-y-0 right-0 z-50 w-full md:w-[480px] bg-base-100 shadow-2xl transform transition-transform duration-300 flex flex-col"
            :class="modelValue ? 'translate-x-0' : 'translate-x-full'"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-base-200">
                <div>
                    <h2 class="text-xl font-bold text-base-content">Container Details</h2>
                    <p class="text-sm text-base-content/70 mt-1" v-if="container">
                        B/L: <span class="font-medium text-base-content">{{ container.bl_number }}</span>
                        <span class="mx-2">•</span>
                        <span class="uppercase tracking-wide text-xs font-semibold">{{ container.status?.replace('_', ' ') || 'Unknown' }}</span>
                    </p>
                </div>
                <button 
                    @click="$emit('update:modelValue', false)"
                    class="btn btn-sm btn-circle btn-ghost"
                >
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Content / Car List -->
            <div class="flex-1 overflow-y-auto p-6 bg-base-200/30">
                <div v-if="!container"></div>
                <div v-else-if="!container.cars || container.cars.length === 0" class="flex flex-col items-center justify-center py-12 text-base-content/50">
                    <i class="fa-solid fa-car-side text-4xl mb-3 opacity-30"></i>
                    <p>No cars found in this container.</p>
                </div>
                <div v-else class="flex flex-col gap-4">
                    <div class="text-sm font-semibold text-base-content/60 uppercase tracking-wider mb-2">
                        Cars Loaded ({{ container.cars.length }})
                    </div>
                    
                    <!-- Car Item Card -->
                    <div 
                        v-for="car in container.cars" 
                        :key="car.id"
                        class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden transition-all hover:shadow-md"
                    >
                        <div class="flex items-stretch h-32">
                            <!-- Thumbnail -->
                            <div class="w-32 bg-base-200 flex-shrink-0 relative">
                                <img 
                                    v-if="car.images && car.images.length > 0" 
                                    :src="car.images[0].url" 
                                    class="w-full h-full object-cover"
                                    alt="Car image"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-base-content/20">
                                    <i class="fa-solid fa-image text-3xl"></i>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="p-3 flex-1 flex flex-col justify-between overflow-hidden">
                                <div>
                                    <h3 class="font-bold text-base-content leading-tight truncate" :title="car.name">
                                        {{ car.name }}
                                    </h3>
                                    <p class="text-xs text-base-content/70 mt-1 truncate">
                                        {{ car.maker?.name || 'Unknown' }} - {{ car.car_model?.name || 'Unknown' }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <div class="badge badge-sm badge-outline" v-if="car.year">{{ car.year }}</div>
                                    <div class="badge badge-sm border-0" :style="{ backgroundColor: car.color?.hex_code || '#ccc', color: getContrastYIQ(car.color?.hex_code || '#ccc') }" v-if="car.color">
                                        {{ car.color.name }}
                                    </div>
                                    <div class="badge badge-sm badge-ghost font-mono text-[10px] truncate max-w-[100px]">{{ car.body_number || 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 border-t border-base-200 bg-base-100" v-if="container">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-base-content/70 font-medium">Total Shipping Cost:</span>
                    <span class="font-bold text-lg text-primary">${{ Number(container.total_shipping_cost || 0).toLocaleString() }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
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
