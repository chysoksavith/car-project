<template>
    <li>
        <!-- If no submenu -->
        <a 
            v-if="!item.children" 
            :href="item.href || '#'"
            class="group font-medium transition-colors"
            :class="{ 'active bg-primary/10 text-primary': item.active }"
        >
            <component 
                :is="item.icon" 
                class="w-5 h-5 transition-transform group-hover:scale-110" 
                :class="{ 'opacity-70': !item.active, 'text-primary': item.active }"
            />
            <span v-show="!isCollapsed" class="whitespace-nowrap">{{ item.name }}</span>
            
            <div v-if="item.badge && !isCollapsed" class="badge badge-sm badge-primary ml-auto">
                {{ item.badge }}
            </div>
        </a>

        <!-- If has submenu -->
        <details v-else :open="isOpen" @toggle="onToggle" class="group">
            <summary class="font-medium">
                <component 
                    :is="item.icon" 
                    class="w-5 h-5 opacity-70 transition-transform group-hover:scale-110" 
                />
                <span v-show="!isCollapsed" class="whitespace-nowrap">{{ item.name }}</span>
            </summary>
            <ul v-show="!isCollapsed" class="before:bg-base-300/50 mt-1">
                <li v-for="child in item.children" :key="child.name">
                    <a 
                        :href="child.href || '#'" 
                        class="text-sm text-base-content/70 hover:text-base-content"
                        :class="{ 'active bg-primary/10 text-primary': child.active }"
                    >
                        {{ child.name }}
                    </a>
                </li>
            </ul>
        </details>
    </li>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    item: {
        name: string;
        icon?: any;
        href?: string;
        active?: boolean;
        badge?: string | number;
        children?: any[];
    };
    isCollapsed?: boolean;
}>();

const isOpen = ref(props.item.children?.some(c => c.active) || false);

// Watch collapse state to close details if collapsed
watch(() => props.isCollapsed, (collapsed) => {
    if (collapsed) {
        isOpen.value = false;
    }
});

const onToggle = (e: Event) => {
    const details = e.target as HTMLDetailsElement;
    isOpen.value = details.open;
};
</script>
