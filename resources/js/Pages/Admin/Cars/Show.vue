<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[
                { label: 'Cars', href: route('admin.cars.index') },
                { label: car.name }
            ]"
            :description="'Viewing details for ' + car.name"
            class="mb-6"
        >
            <div class="flex gap-2">
                <Button :href="route('admin.cars.edit', car.id)" variant="primary" size="sm" icon="fa-solid fa-pen">
                    Edit Car
                </Button>
            </div>
        </PageHeader>

        <!-- Main Detail Component (Frontend Ready) -->
        <div class="bg-base-100 rounded-3xl shadow-xl border border-base-200 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 lg:gap-8">
                
                <!-- Left Column: Gallery -->
                <div class="lg:col-span-7 bg-base-200/30 p-6 lg:p-8 border-b lg:border-b-0 lg:border-r border-base-200">
                    <!-- Main Image -->
                    <div class="aspect-video bg-base-200 rounded-2xl overflow-hidden shadow-sm relative group mb-4 border border-base-300">
                        <img 
                            v-if="car.images && car.images.length > 0" 
                            :src="activeImage" 
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
                            :alt="car.name"
                        />
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-base-content/20 bg-gradient-to-br from-base-200 to-base-300">
                            <i class="fa-solid fa-car text-6xl mb-4 opacity-50"></i>
                            <span class="font-medium tracking-wider">NO IMAGE</span>
                        </div>
                        
                        <!-- Badges Overlay -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span v-if="car.condition === 'NEW'" class="badge badge-success border-none shadow-sm font-bold tracking-wider px-3 py-3">NEW</span>
                            <span v-else class="badge badge-neutral border-none shadow-sm font-bold tracking-wider px-3 py-3">USED</span>
                            <span v-if="car.sales_status === 'AVAILABLE'" class="badge badge-info text-white border-none shadow-sm font-bold tracking-wider px-3 py-3">AVAILABLE</span>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="flex gap-3 overflow-x-auto pb-2 custom-scrollbar" v-if="car.images && car.images.length > 1">
                        <button 
                            v-for="(img, idx) in car.images" 
                            :key="img.id"
                            @click="activeImage = img.url"
                            class="relative w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 rounded-xl overflow-hidden border-2 transition-all duration-200 focus:outline-none"
                            :class="activeImage === img.url ? 'border-primary shadow-md scale-[1.03]' : 'border-base-300 hover:border-primary/50 opacity-70 hover:opacity-100'"
                        >
                            <img :src="img.url" class="w-full h-full object-cover" />
                        </button>
                    </div>
                </div>

                <!-- Right Column: Details -->
                <div class="lg:col-span-5 p-6 lg:p-8 lg:pl-0 flex flex-col">
                    <div class="mb-6 pb-6 border-b border-base-200">
                        <div class="flex items-center gap-2 text-primary font-bold text-sm tracking-widest uppercase mb-2">
                            <span>{{ car.maker?.name || 'Make' }}</span>
                            <i class="fa-solid fa-circle text-[5px] opacity-40"></i>
                            <span>{{ car.car_model?.name || 'Model' }}</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-base-content leading-tight mb-2">
                            {{ car.name }}
                        </h1>
                        <p class="text-base-content/60 font-medium text-lg flex items-center gap-2">
                            <i class="fa-regular fa-calendar-days text-primary/70"></i>
                            {{ car.year }} Model
                        </p>
                    </div>

                    <!-- Key Specs Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 gap-4 mb-8">
                        <div class="bg-base-200/50 rounded-2xl p-4 border border-base-200 flex items-start gap-3 transition-colors hover:bg-base-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-base-100 shadow-sm flex items-center justify-center text-primary flex-shrink-0">
                                <i class="fa-solid fa-gas-pump"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mb-0.5">Fuel</p>
                                <p class="font-semibold text-base-content truncate">{{ car.fuel?.name || 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="bg-base-200/50 rounded-2xl p-4 border border-base-200 flex items-start gap-3 transition-colors hover:bg-base-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-base-100 shadow-sm flex items-center justify-center text-primary flex-shrink-0">
                                <i class="fa-solid fa-gears"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mb-0.5">Trans</p>
                                <p class="font-semibold text-base-content capitalize truncate">{{ car.transmission?.toLowerCase() || 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="bg-base-200/50 rounded-2xl p-4 border border-base-200 flex items-start gap-3 transition-colors hover:bg-base-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-base-100 shadow-sm flex items-center justify-center text-primary flex-shrink-0">
                                <i class="fa-solid fa-gauge-high"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mb-0.5">Engine</p>
                                <p class="font-semibold text-base-content truncate">{{ car.engine_capacity_cc ? car.engine_capacity_cc.toLocaleString() + ' cc' : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="bg-base-200/50 rounded-2xl p-4 border border-base-200 flex items-start gap-3 transition-colors hover:bg-base-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-base-100 shadow-sm flex items-center justify-center text-primary flex-shrink-0">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs text-base-content/50 font-bold uppercase tracking-wider mb-0.5">Color</p>
                                <div class="flex items-center gap-1.5">
                                    <span v-if="car.color" class="w-3 h-3 rounded-full shadow-sm border border-black/10 flex-shrink-0" :style="{ backgroundColor: car.color.hex_code }"></span>
                                    <p class="font-semibold text-base-content truncate" :title="car.color?.name">{{ car.color?.name || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Info (Admin only for now, can be extracted out) -->
                    <div class="bg-gradient-to-br from-primary/10 to-base-200 rounded-2xl p-6 border border-primary/20 mt-auto shadow-sm relative overflow-hidden">
                        <!-- Decorative element -->
                        <div class="absolute -right-6 -bottom-6 text-primary/5 opacity-50 transform rotate-12 pointer-events-none">
                            <i class="fa-solid fa-tag text-9xl"></i>
                        </div>
                        
                        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1 relative z-10">Selling Price</p>
                        <div class="flex items-baseline gap-2 mb-4 relative z-10">
                            <span class="text-4xl font-black text-base-content">${{ Number(car.selling_price || 0).toLocaleString() }}</span>
                            <span v-if="car.discounted_price > 0" class="text-lg text-base-content/40 line-through font-semibold">${{ Number(car.discounted_price).toLocaleString() }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-primary/10 pt-4 relative z-10">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-base-content/50 mb-0.5 tracking-wider">Purchase</p>
                                <p class="font-semibold text-sm">${{ Number(car.purchase_price || 0).toLocaleString() }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-base-content/50 mb-0.5 tracking-wider">Exp. Profit</p>
                                <p class="font-semibold text-sm text-success">${{ Number(car.expected_profit || 0).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bottom Tabs/Details Section -->
            <div class="border-t border-base-200 bg-base-100">
                <div class="p-6 lg:p-8">
                    <h3 class="text-xl font-bold mb-6 text-base-content flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i>
                        Vehicle Specifications
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <!-- Spec item -->
                        <div class="flex items-center justify-between border-b border-base-200 pb-3">
                            <span class="text-base-content/60 font-medium text-sm">Body Number (VIN)</span>
                            <span class="font-mono font-semibold text-base-content">{{ car.body_number || '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-base-200 pb-3">
                            <span class="text-base-content/60 font-medium text-sm">Engine Number</span>
                            <span class="font-mono font-semibold text-base-content">{{ car.engine_number || '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-base-200 pb-3">
                            <span class="text-base-content/60 font-medium text-sm">Registration Type</span>
                            <span class="font-semibold text-base-content capitalize">{{ car.registration_type?.replace('_', ' ').toLowerCase() || '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-base-200 pb-3">
                            <span class="text-base-content/60 font-medium text-sm">Plate Number</span>
                            <span class="font-semibold text-base-content">{{ car.plate_number || '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-base-200 pb-3">
                            <span class="text-base-content/60 font-medium text-sm">Certificate Number</span>
                            <span class="font-semibold text-base-content">{{ car.certificate_number || '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-base-200 pb-3">
                            <span class="text-base-content/60 font-medium text-sm">Inventory Location</span>
                            <span class="font-semibold text-base-content">{{ car.container ? car.container.container_number : 'Local / Unknown' }}</span>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-bold text-base-content/80 uppercase tracking-wider text-xs mb-3">Description</h4>
                            <div class="prose prose-sm max-w-none text-base-content/80 bg-base-200/30 p-5 rounded-2xl border border-base-200 leading-relaxed min-h-[100px] shadow-inner">
                                {{ car.description || 'No description provided.' }}
                            </div>
                        </div>
                        <div>
                            <h4 class="font-bold text-base-content/80 uppercase tracking-wider text-xs mb-3">Additional Options</h4>
                            <div class="prose prose-sm max-w-none text-base-content/80 bg-base-200/30 p-5 rounded-2xl border border-base-200 leading-relaxed min-h-[100px] shadow-inner">
                                {{ car.options || 'No options listed.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "@/Components/Button.vue";

const props = defineProps<{
    car: any;
}>();

const activeImage = ref<string>('');

onMounted(() => {
    if (props.car.images && props.car.images.length > 0) {
        activeImage.value = props.car.images[0].url;
    }
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: oklch(var(--bc) / 0.2);
    border-radius: 20px;
}
</style>
