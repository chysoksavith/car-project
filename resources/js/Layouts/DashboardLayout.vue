<template>
    <div class="drawer lg:drawer-open min-h-screen bg-base-200">
        <input id="mobile-drawer" type="checkbox" class="drawer-toggle" />
        
        <!-- Main Application Layout -->
        <div class="drawer-content flex min-h-screen min-w-0">
            <!-- Desktop Sidebar -->
            <div class="hidden lg:flex z-20">
                <Sidebar :is-collapsed="isSidebarCollapsed" />
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar -->
                <Topbar 
                    :is-collapsed="isSidebarCollapsed" 
                    @toggle-collapse="isSidebarCollapsed = !isSidebarCollapsed" 
                />
                
                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
                    <div class="mx-auto max-w-7xl w-full">
                        <slot />
                    </div>
                </main>
            </div>
        </div> 
        
        <!-- Mobile Sidebar Drawer -->
        <div class="drawer-side z-50 lg:hidden">
            <label for="mobile-drawer" aria-label="close sidebar" class="drawer-overlay"></label> 
            <!-- Reuse Sidebar component but force expanded mode for mobile -->
            <Sidebar :is-collapsed="false" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Sidebar from '@/Components/Layout/Sidebar.vue';
import Topbar from '@/Components/Layout/Topbar.vue';

// Desktop sidebar state
const isSidebarCollapsed = ref(false);
</script>
