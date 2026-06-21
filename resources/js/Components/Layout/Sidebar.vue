<template>
    <aside
        class="bg-base-100 border-r border-base-200/80 z-20 flex flex-col transition-all duration-300 relative shadow-sm h-full"
        :class="[isCollapsed ? 'w-20' : 'w-72']"
    >
        <!-- Logo Header -->
        <div
            class="h-16 flex items-center justify-center border-b border-base-200/80 shrink-0"
        >
            <div
                class="flex items-center gap-3 w-full px-4"
                :class="[isCollapsed ? 'justify-center px-0' : '']"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-primary-content font-bold text-xl shadow-md shadow-primary/30 shrink-0"
                >
                    S
                </div>
                <span
                    v-show="!isCollapsed"
                    class="text-2xl font-bold tracking-tight truncate"
                    >SaaS App</span
                >
            </div>
        </div>

        <!-- Navigation Menu -->
        <div
            class="flex-1 overflow-y-auto py-4 overflow-x-hidden nice-scrollbar"
        >
            <ul class="menu w-full px-3 gap-1">
                <template v-for="(group, index) in menuGroups" :key="group.name">
                    <!-- Group Title -->
                    <li
                        v-show="!isCollapsed"
                        class="menu-title text-xs font-semibold uppercase tracking-wider text-base-content/50"
                        :class="index === 0 ? 'mt-2' : 'mt-6'"
                    >
                        {{ group.name }}
                    </li>

                    <!-- Group Items -->
                    <SidebarItem
                        v-for="item in group.items"
                        :key="item.name"
                        :item="item"
                        :is-collapsed="isCollapsed"
                    />
                </template>
            </ul>
        </div>

        <!-- Bottom User Profile -->
        <div class="border-t border-base-200/80 p-3 shrink-0">
            <div
                class="flex items-center gap-3 p-2 rounded-xl hover:bg-base-200 transition-colors cursor-default group"
            >
                <div class="avatar placeholder shrink-0">
                    <div
                        class="w-10 h-10 rounded-full bg-primary/20 text-primary font-bold text-sm flex items-center justify-center ring-2 ring-primary/20 shadow-sm"
                    >
                        {{ user?.initials ?? "?" }}
                    </div>
                </div>
                <div v-show="!isCollapsed" class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-base-content truncate">
                        {{ user?.name ?? "—" }}
                    </p>
                    <p class="text-xs text-base-content/60 truncate">
                        {{ user?.email ?? "—" }}
                    </p>
                </div>
            </div>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { h } from "vue";
import SidebarItem from "./SidebarItem.vue";
import { useAuth } from "@/Composables/useAuth";

const { user } = useAuth();

defineProps<{
    isCollapsed: boolean;
}>();

// Simple icon wrapper function for cleaner template
const Icon = (path: string) =>
    h(
        "svg",
        {
            xmlns: "http://www.w3.org/2000/svg",
            fill: "none",
            viewBox: "0 0 24 24",
            stroke: "currentColor",
            "stroke-width": "2",
        },
        [
            h("path", {
                "stroke-linecap": "round",
                "stroke-linejoin": "round",
                d: path,
            }),
        ],
    );

const menuGroups = [
    {
        name: "Overview",
        items: [
            {
                name: "Dashboard",
                icon: () =>
                    Icon(
                        "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
                    ),
                active: true,
                href: "/",
            },
        ],
    },
    {
        name: "Cars Management",
        items: [
            {
                name: "Makers",
                icon: () => Icon("M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"),
                href: "/admin/makers",
            },
            {
                name: "Car Models",
                icon: () => Icon("M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"),
                href: "/admin/car-models",
            },
        ],
    },
    {
        name: "Management",
        items: [
            {
                name: "Users & Teams",
                icon: () =>
                    Icon(
                        "M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z",
                    ),
                children: [
                    { name: "All Users", href: "/admin/users" },
                    { name: "Roles", href: "/admin/roles" },
                    { name: "Permissions", href: "/admin/permissions" },
                ],
            },
            {
                name: "Companies",
                icon: () =>
                    Icon(
                        "M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                    ),
                href: "/admin/companies",
            },
        ],
    },
];
</script>

<style scoped>
.nice-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.nice-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.nice-scrollbar::-webkit-scrollbar-thumb {
    background-color: oklch(var(--b3));
    border-radius: 10px;
}
</style>
