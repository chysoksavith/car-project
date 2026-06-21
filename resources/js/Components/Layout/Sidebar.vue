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
import SidebarItem from "./SidebarItem.vue";
import { useAuth } from "@/Composables/useAuth";

const { user } = useAuth();

defineProps<{
    isCollapsed: boolean;
}>();

const menuGroups = [
    {
        name: "Overview",
        items: [
            {
                name: "Dashboard",
                icon: "fa-solid fa-gauge",
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
                icon: "fa-solid fa-industry",
                href: "/admin/makers",
            },
            {
                name: "Car Models",
                icon: "fa-solid fa-car",
                href: "/admin/car-models",
            },
            {
                name: "Colors",
                icon: "fa-solid fa-palette",
                href: "/admin/colors",
            },
            {
                name: "Inspection Items",
                icon: "fa-solid fa-clipboard-list",
                href: "/admin/inspection-items",
            },
        ],
    },
    {
        name: "Management",
        items: [
            {
                name: "Users & Teams",
                icon: "fa-solid fa-users",
                children: [
                    { name: "All Users", href: "/admin/users" },
                    { name: "Roles", href: "/admin/roles" },
                    { name: "Permissions", href: "/admin/permissions" },
                ],
            },
            {
                name: "Companies",
                icon: "fa-solid fa-building",
                href: "/admin/companies",
            },
            {
                name: "Suppliers",
                icon: "fa-solid fa-truck-field",
                href: "/admin/suppliers",
            },
            {
                name: "Shipments",
                icon: "fa-solid fa-ship",
                href: "/admin/shipments",
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
