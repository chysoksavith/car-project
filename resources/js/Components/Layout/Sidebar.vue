<template>
    <aside
        class="bg-base-100 border-r border-base-200/80 z-20 flex flex-col transition-all duration-300 relative shadow-sm h-full"
        :class="[isCollapsed ? 'w-20' : 'w-72']"
    >
        <!-- Logo Header -->
        <div class="h-16 flex items-center justify-center border-b border-base-200/80 shrink-0">
            <div class="flex items-center gap-3 w-full px-4" :class="[isCollapsed ? 'justify-center px-0' : '']">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-primary-content font-bold text-xl shadow-md shadow-primary/30 shrink-0">
                    S
                </div>
                <span v-show="!isCollapsed" class="text-2xl font-bold tracking-tight truncate">SaaS App</span>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto py-4 overflow-x-hidden nice-scrollbar">
            <ul class="menu w-full px-3 gap-1">
                <!-- Overview Section -->
                <li v-show="!isCollapsed" class="menu-title text-xs font-semibold uppercase tracking-wider text-base-content/50 mt-2">
                    Overview
                </li>
                
                <SidebarItem 
                    v-for="item in overviewItems" 
                    :key="item.name" 
                    :item="item" 
                    :is-collapsed="isCollapsed" 
                />

                <!-- Management Section (With Dropdown) -->
                <li v-show="!isCollapsed" class="menu-title text-xs font-semibold uppercase tracking-wider text-base-content/50 mt-6">
                    Management
                </li>
                <SidebarItem 
                    v-for="item in managementItems" 
                    :key="item.name" 
                    :item="item" 
                    :is-collapsed="isCollapsed" 
                />
            </ul>
        </div>

        <!-- Bottom User Profile -->
        <div class="border-t border-base-200/80 p-3 shrink-0">
            <a class="flex items-center gap-3 p-2 rounded-xl hover:bg-base-200 transition-colors cursor-pointer group">
                <div class="avatar placeholder shrink-0">
                    <div class="w-10 h-10 rounded-full bg-base-300 text-base-content flex items-center justify-center font-bold shadow-sm">
                        JD
                    </div>
                </div>
                <div v-show="!isCollapsed" class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-base-content truncate">John Doe</p>
                    <p class="text-xs text-base-content/60 truncate">admin@saas.com</p>
                </div>
            </a>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { h } from 'vue';
import SidebarItem from './SidebarItem.vue';

defineProps<{
    isCollapsed: boolean;
}>();

// Simple icon wrapper function for cleaner template
const Icon = (path: string) => h('svg', { 
    xmlns: 'http://www.w3.org/2000/svg', 
    fill: 'none', 
    viewBox: '0 0 24 24', 
    stroke: 'currentColor', 
    'stroke-width': '2' 
}, [
    h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', d: path })
]);

const overviewItems = [
    { 
        name: 'Dashboard', 
        icon: () => Icon('M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'), 
        active: true,
        href: '/'
    },
    { 
        name: 'Analytics', 
        icon: () => Icon('M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'), 
    },
    {
        name: 'Messages',
        icon: () => Icon('M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'),
        badge: '3'
    }
];

const managementItems = [
    {
        name: 'Users & Teams',
        icon: () => Icon('M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'),
        children: [
            { name: 'All Users', href: '#' },
            { name: 'Roles & Permissions', href: '#' },
            { name: 'Invites', href: '#' }
        ]
    },
    {
        name: 'Projects',
        icon: () => Icon('M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'),
        children: [
            { name: 'Active Projects', href: '#' },
            { name: 'Archived', href: '#' },
        ]
    },
    {
        name: 'Settings',
        icon: () => Icon('M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'),
    }
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
