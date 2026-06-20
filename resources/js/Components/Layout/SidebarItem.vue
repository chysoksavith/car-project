<template>
    <li>
        <!-- If no submenu -->
        <Link
            v-if="!item.children"
            :href="item.href || '#'"
            class="sidebar-link group font-medium transition-colors"
            :class="{ 'sidebar-active bg-primary/10 text-primary': isItemActive }"
        >
            <component
                :is="item.icon"
                class="w-5 h-5 transition-transform group-hover:scale-110"
                :class="{
                    'opacity-70': !isItemActive,
                    'text-primary': isItemActive,
                }"
            />
            <span v-show="!isCollapsed" class="whitespace-nowrap">{{
                item.name
            }}</span>

            <div
                v-if="item.badge && !isCollapsed"
                class="badge badge-sm badge-primary ml-auto"
            >
                {{ item.badge }}
            </div>
        </Link>

        <!-- If has submenu -->
        <details v-else :open="isOpen" @toggle="onToggle" class="group">
            <summary
                class="sidebar-summary font-medium"
                :class="{ 'bg-primary/5 text-primary': isAnyChildActive }"
            >
                <component
                    :is="item.icon"
                    class="w-5 h-5 transition-transform group-hover:scale-110"
                    :class="{
                        'opacity-70': !isAnyChildActive,
                        'text-primary': isAnyChildActive,
                    }"
                />
                <span v-show="!isCollapsed" class="whitespace-nowrap">{{
                    item.name
                }}</span>
            </summary>
            <ul v-show="!isCollapsed" class="before:bg-base-300/50 mt-1">
                <li v-for="child in item.children" :key="child.name">
                    <Link
                        :href="child.href || '#'"
                        class="sidebar-link text-sm transition-colors"
                        :class="
                            isActivePath(child.href)
                                ? 'sidebar-active bg-primary/10 text-primary font-semibold'
                                : 'text-base-content/70 hover:text-base-content'
                        "
                    >
                        {{ child.name }}
                    </Link>
                </li>
            </ul>
        </details>
    </li>
</template>

<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps<{
    item: {
        name: string;
        icon?: any;
        href?: string;
        badge?: string | number;
        children?: any[];
    };
    isCollapsed?: boolean;
}>();

// Check if a given href is active based on the current page URL
const isActivePath = (href?: string) => {
    if (!href || href === "#") return false;

    // For root path
    if (href === "/") {
        return page.url === "/";
    }

    // Check if current URL starts with this href
    return page.url.startsWith(href);
};

const isItemActive = computed(() => isActivePath(props.item.href));

const isAnyChildActive = computed(() => {
    if (!props.item.children) return false;
    return props.item.children.some((child) => isActivePath(child.href));
});

const isOpen = ref(isAnyChildActive.value);

// Watch collapse state to close details if collapsed
watch(
    () => props.isCollapsed,
    (collapsed) => {
        if (collapsed) {
            isOpen.value = false;
        }
    },
);

const onToggle = (e: Event) => {
    const details = e.target as HTMLDetailsElement;
    isOpen.value = details.open;
};
</script>

<style scoped>
/*
 * DaisyUI v5 applies a dark/black background to:
 *   - summary:focus  (browser default focus ring + DaisyUI's oklch(--b3))
 *   - .menu li > a.active  (DaisyUI default uses oklch(--n) = neutral ≈ dark)
 *
 * We override both to transparent and rely solely on our own Tailwind
 * utility classes (bg-primary/10, hover:bg-base-200) for visual feedback.
 */

/* Remove DaisyUI's dark "active" background from links */
.sidebar-link:focus,
.sidebar-link:active,
.sidebar-link.active {
    background-color: transparent !important;
    color: inherit;
}

/* Our custom active state keeps the primary tint */
.sidebar-active {
    /* intentionally left to Tailwind classes: bg-primary/10 text-primary */
}

/* Remove the black flash on summary click */
.sidebar-summary:focus,
.sidebar-summary:focus-visible,
.sidebar-summary:active {
    background-color: transparent !important;
    outline: none;
}

/* Remove browser default summary marker arrow (DaisyUI uses its own) */
.sidebar-summary::-webkit-details-marker {
    display: none;
}
</style>
