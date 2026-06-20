<template>
    <header class="h-16 bg-base-100 border-b border-base-200/80 flex items-center justify-between px-4 sticky top-0 z-30 shadow-sm">
        
        <div class="flex items-center gap-3">
            <!-- Mobile Menu Toggle -->
            <label for="mobile-drawer" class="btn btn-square btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </label>
            
            <!-- Desktop Collapse Toggle -->
            <button @click="$emit('toggle-collapse')" class="btn btn-square btn-ghost hidden lg:flex text-base-content/70 hover:text-base-content">
                <svg v-if="!isCollapsed" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex relative w-64 ml-2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" placeholder="Search..." class="input input-sm input-bordered w-full pl-10 bg-base-200/50 focus:bg-base-100 rounded-full" />
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Notifications -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span class="badge badge-xs badge-error indicator-item"></span>
                    </div>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-box w-64 border border-base-200 mt-4">
                    <li class="menu-title">Notifications</li>
                    <li><a>New message from Sarah</a></li>
                    <li><a>Server backup completed</a></li>
                </ul>
            </div>
            
            <!-- User Menu -->
            <div class="dropdown dropdown-end md:hidden">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-8 rounded-full bg-base-300 text-base-content flex items-center justify-center font-bold">
                        JD
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-lg bg-base-100 rounded-box w-52 border border-base-200">
                    <li><a>Profile</a></li>
                    <li><a>Settings</a></li>
                    <li><a @click="router.post('/logout')" class="text-error cursor-pointer">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';

defineProps<{
    isCollapsed: boolean;
}>();

defineEmits(['toggle-collapse']);
</script>
