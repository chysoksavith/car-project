<template>
    <header class="h-16 bg-base-100 border-b border-base-200/80 flex items-center justify-between px-4 sticky top-0 z-30 shadow-sm">

        <!-- Left: Mobile toggle + Collapse toggle + Search -->
        <div class="flex items-center gap-3">
            <!-- Mobile Menu Toggle -->
            <label for="mobile-drawer" class="btn btn-square btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <!-- Desktop Collapse Toggle -->
            <button
                @click="$emit('toggle-collapse')"
                class="btn btn-square btn-ghost hidden lg:flex text-base-content/70 hover:text-base-content"
                title="Toggle sidebar"
            >
                <svg v-if="!isCollapsed" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex relative w-64 ml-2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search..." class="input input-sm input-bordered w-full pl-10 bg-base-200/50 focus:bg-base-100 rounded-full" />
            </div>
        </div>

        <!-- Right: Notifications + User dropdown -->
        <div class="flex items-center gap-1">

            <!-- Notifications -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle" title="Notifications">
                    <div class="indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="badge badge-xs badge-error indicator-item"></span>
                    </div>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-lg bg-base-100 rounded-box w-64 border border-base-200 mt-4">
                    <li class="menu-title">Notifications</li>
                    <li><a>New message from Sarah</a></li>
                    <li><a>Server backup completed</a></li>
                </ul>
            </div>

            <!-- ─── User Dropdown ─────────────────────────────────── -->
            <div class="dropdown dropdown-end" ref="userDropdownRef">
                <!-- Trigger -->
                <div
                    tabindex="0"
                    role="button"
                    class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl hover:bg-base-200 transition-colors cursor-pointer select-none"
                    title="Account menu"
                >
                    <!-- Avatar -->
                    <div class="avatar placeholder">
                        <div class="w-8 h-8 rounded-full bg-primary/20 text-primary font-bold text-sm flex items-center justify-center ring-2 ring-primary/20">
                            <span>{{ user?.initials ?? '?' }}</span>
                        </div>
                    </div>
                    <!-- Name (hidden on small screens) -->
                    <span class="hidden sm:block text-sm font-semibold text-base-content max-w-[120px] truncate">
                        {{ user?.name ?? 'Account' }}
                    </span>
                    <!-- Chevron -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/50 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Dropdown Panel -->
                <div tabindex="0" class="dropdown-content z-[1] mt-3 w-64 bg-base-100 rounded-2xl shadow-xl border border-base-200 overflow-hidden">

                    <!-- User Info Header -->
                    <div class="px-4 py-4 border-b border-base-200/70 bg-base-200/30">
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="w-10 h-10 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center ring-2 ring-primary/30">
                                    <span>{{ user?.initials ?? '?' }}</span>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-sm text-base-content truncate">{{ user?.name ?? '—' }}</p>
                                <p class="text-xs text-base-content/60 truncate">{{ user?.email ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <ul class="menu menu-sm p-2 gap-0.5">
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-lg py-2 px-3 hover:bg-base-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>View Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-lg py-2 px-3 hover:bg-base-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-base-content/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Settings</span>
                            </a>
                        </li>

                        <div class="divider my-1 h-px bg-base-200"></div>

                        <!-- Logout -->
                        <li>
                            <button
                                @click="logout"
                                class="flex items-center gap-3 rounded-lg py-2 px-3 w-full text-left text-error hover:bg-error/10 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="font-medium">Sign Out</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- ─── End User Dropdown ─────────────────────────────── -->
        </div>
    </header>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useAuth } from '@/Composables/useAuth';

defineProps<{
    isCollapsed: boolean;
}>();

defineEmits(['toggle-collapse']);

const { user } = useAuth();

function logout() {
    router.post('/logout');
}
</script>
