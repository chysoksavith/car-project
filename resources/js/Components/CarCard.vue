<template>
    <component
        :is="href ? Link : 'div'"
        :href="href"
        class="group relative bg-base-100 rounded-2xl p-3 flex gap-4 border border-base-200/60 shadow-sm hover:shadow-md transition-all duration-200 hover:border-primary/30 text-left"
        :class="[
            { 'border-primary ring-2 ring-primary/20': isSelected },
            href ? 'block' : 'cursor-pointer'
        ]"
        @click="!href && $emit('select', car)"
    >
        <!-- Index Number -->
        <div class="absolute -top-2 -left-2 w-6 h-6 rounded-full bg-base-200 flex items-center justify-center text-[10px] font-bold text-base-content/60 border border-base-100 shadow-sm z-10 group-hover:bg-primary group-hover:text-primary-content transition-colors" v-if="showIndex && index !== undefined">
            {{ index + 1 }}
        </div>

        <!-- Thumbnail -->
        <div class="w-28 h-28 rounded-xl bg-base-200 shrink-0 relative overflow-hidden group-hover:shadow-inner">
            <img
                v-if="car.images && car.images.length > 0"
                :src="car.images[0].url"
                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                alt="Car image"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-base-content/20 bg-linear-to-br from-base-200 to-base-300">
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

        <!-- Selected Indicator -->
        <div v-if="isSelected" class="absolute top-2 right-2 w-6 h-6 rounded-full bg-primary flex items-center justify-center text-primary-content shadow-sm">
            <i class="fa-solid fa-check text-xs"></i>
        </div>
    </component>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    car: any;
    index?: number;
    showIndex?: boolean;
    isSelected?: boolean;
    href?: string;
}>();

defineEmits(['select']);
</script>
