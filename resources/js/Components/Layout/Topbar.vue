<template>
    <header
        class="h-16 bg-base-100 border-b border-base-200/80 flex items-center justify-between px-4 sticky top-0 z-30 shadow-sm"
    >
        <!-- Left: Mobile toggle + Collapse toggle + Search -->
        <div class="flex items-center gap-3">
            <!-- Mobile Menu Toggle -->
            <label
                for="mobile-drawer"
                class="btn btn-square btn-ghost lg:hidden"
            >
                <i class="fa-solid fa-bars text-xl"></i>
            </label>

            <!-- Desktop Collapse Toggle -->
            <button
                @click="$emit('toggle-collapse')"
                class="btn btn-square btn-ghost hidden lg:flex text-base-content/70 hover:text-base-content"
                title="Toggle sidebar"
            >
                <i
                    v-if="!isCollapsed"
                    class="fa-solid fa-bars-staggered text-xl"
                ></i>
                <i v-else class="fa-solid fa-bars text-xl"></i>
            </button>

            <!-- Search Bar -->
            <div class="hidden md:flex relative w-64 ml-2">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-base-content/50"
                >
                    <i class="fa-solid fa-search"></i>
                </div>
                <input
                    type="text"
                    placeholder="Search..."
                    class="input input-sm input-bordered w-full pl-10 bg-base-200/50 focus:bg-base-100 rounded-full"
                />
            </div>
        </div>

        <!-- Right: User dropdown -->
        <div class="flex items-center gap-1">

            <!-- # User Dropdown -->
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
                        <div
                            class="w-8 h-8 rounded-full bg-primary/20 text-primary font-bold text-sm flex items-center justify-center ring-2 ring-primary/20"
                        >
                            <span>{{ user?.initials ?? "?" }}</span>
                        </div>
                    </div>
                    <!-- Name (hidden on small screens) -->
                    <span
                        class="hidden sm:block text-sm font-semibold text-base-content max-w-[120px] truncate"
                    >
                        {{ user?.name ?? "Account" }}
                    </span>
                    <!-- Chevron -->
                    <i
                        class="fa-solid fa-chevron-down text-xs text-base-content/50 hidden sm:block"
                    ></i>
                </div>

                <!-- Dropdown Panel -->
                <div
                    tabindex="0"
                    class="dropdown-content z-[1] mt-3 w-72 min-w-full bg-base-100 rounded-2xl shadow-xl border border-base-200 overflow-hidden"
                >
                    <!-- User Info Header -->
                    <div
                        class="px-4 py-4 border-b border-base-200/70 bg-base-200/30"
                    >
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center ring-2 ring-primary/30"
                                >
                                    <span>{{ user?.initials ?? "?" }}</span>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="font-bold text-sm text-base-content truncate"
                                >
                                    {{ user?.name ?? "—" }}
                                </p>
                                <p
                                    class="text-xs text-base-content/60 truncate"
                                >
                                    {{ user?.email ?? "—" }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <ul class="menu menu-sm p-2 gap-0.5 w-full">
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3 rounded-lg py-2 px-3 w-full hover:bg-base-200 transition-colors"
                            >
                                <i
                                    class="fa-regular fa-user text-base-content/60 w-4 text-center"
                                ></i>
                                <span>View Profile</span>
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="flex items-center gap-3 rounded-lg py-2 px-3 w-full hover:bg-base-200 transition-colors"
                            >
                                <i
                                    class="fa-solid fa-gear text-base-content/60 w-4 text-center"
                                ></i>
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
                                <i
                                    class="fa-solid fa-arrow-right-from-bracket w-4 text-center"
                                ></i>
                                <span class="font-medium">Sign Out</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- # End User Dropdown -->
        </div>
    </header>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { useAuth } from "@/Composables/useAuth";

defineProps<{
    isCollapsed: boolean;
}>();

defineEmits(["toggle-collapse"]);

const { user } = useAuth();

function logout() {
    router.post("/logout");
}
</script>
