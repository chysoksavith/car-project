<template>
    <DashboardLayout>
        <div class="flex flex-col gap-6">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-base-content">Dashboard Overview</h1>
                    <p class="text-base-content/60 mt-1">Welcome back! Here's what's happening with your app today.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="btn btn-ghost btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                    <button class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <span class="badge badge-xs badge-error indicator-item"></span>
                        </div>
                    </button>
                    <button class="btn btn-primary shadow-lg shadow-primary/30 ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New Project
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/30 transition-colors">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-base-content/60 text-sm font-semibold uppercase tracking-wider">Total Revenue</h2>
                                <p class="text-3xl font-bold mt-2 text-base-content">$45,231.89</p>
                            </div>
                            <div class="p-3 bg-success/10 rounded-xl text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        <p class="text-success text-sm mt-4 flex items-center font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            +20.1% <span class="text-base-content/50 ml-1 font-normal">from last month</span>
                        </p>
                    </div>
                </div>
                
                <!-- Stat Card 2 -->
                <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/30 transition-colors">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-base-content/60 text-sm font-semibold uppercase tracking-wider">Active Users</h2>
                                <p class="text-3xl font-bold mt-2 text-base-content">+2,350</p>
                            </div>
                            <div class="p-3 bg-primary/10 rounded-xl text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                        </div>
                        <p class="text-success text-sm mt-4 flex items-center font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            +180 <span class="text-base-content/50 ml-1 font-normal">since last week</span>
                        </p>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/30 transition-colors">
                    <div class="card-body p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-base-content/60 text-sm font-semibold uppercase tracking-wider">Active Subscriptions</h2>
                                <p class="text-3xl font-bold mt-2 text-base-content">1,204</p>
                            </div>
                            <div class="p-3 bg-error/10 rounded-xl text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        <p class="text-error text-sm mt-4 flex items-center font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" /></svg>
                            -4.5% <span class="text-base-content/50 ml-1 font-normal">since yesterday</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Area / Tables -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">
                
                <!-- Recent Projects Table -->
                <div class="card bg-base-100 shadow-sm border border-base-200 lg:col-span-2">
                    <div class="card-body p-0">
                        <div class="flex items-center justify-between p-6 border-b border-base-200/50">
                            <h2 class="card-title text-lg">Recent Projects</h2>
                            <button class="btn btn-sm btn-ghost text-primary">View All</button>
                        </div>
                        <div class="overflow-x-auto p-2">
                            <table class="table table-zebra w-full">
                                <thead>
                                    <tr class="text-base-content/60">
                                        <th class="font-medium bg-transparent">Project Name</th>
                                        <th class="font-medium bg-transparent">Status</th>
                                        <th class="font-medium bg-transparent">Date</th>
                                        <th class="font-medium bg-transparent text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover">
                                        <td class="font-semibold">Acme Corp Redesign</td>
                                        <td><div class="badge badge-success badge-sm badge-outline">Completed</div></td>
                                        <td class="text-base-content/70">Oct 24, 2026</td>
                                        <td class="font-semibold text-right">$4,500.00</td>
                                    </tr>
                                    <tr class="hover">
                                        <td class="font-semibold">Globex Landing Page</td>
                                        <td><div class="badge badge-warning badge-sm badge-outline">In Progress</div></td>
                                        <td class="text-base-content/70">Oct 22, 2026</td>
                                        <td class="font-semibold text-right">$2,100.00</td>
                                    </tr>
                                    <tr class="hover">
                                        <td class="font-semibold">Soylent Mobile App</td>
                                        <td><div class="badge badge-ghost badge-sm border-base-300">Pending</div></td>
                                        <td class="text-base-content/70">Oct 18, 2026</td>
                                        <td class="font-semibold text-right">$8,900.00</td>
                                    </tr>
                                    <tr class="hover">
                                        <td class="font-semibold">Initech Dashboard</td>
                                        <td><div class="badge badge-success badge-sm badge-outline">Completed</div></td>
                                        <td class="text-base-content/70">Oct 15, 2026</td>
                                        <td class="font-semibold text-right">$3,200.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Activity Feed -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body p-6">
                        <h2 class="card-title text-lg mb-6">Recent Activity</h2>
                        
                        <ul class="steps steps-vertical step-primary -ml-4">
                            <li class="step step-primary">
                                <div class="text-left ml-2">
                                    <p class="font-semibold text-sm">Invoice Paid</p>
                                    <p class="text-xs text-base-content/60">Acme Corp paid $4,500.00</p>
                                    <p class="text-xs text-base-content/40 mt-1">2 hours ago</p>
                                </div>
                            </li>
                            <li class="step step-primary">
                                <div class="text-left ml-2">
                                    <p class="font-semibold text-sm">New User Registered</p>
                                    <p class="text-xs text-base-content/60">Sarah Jenkins joined the team</p>
                                    <p class="text-xs text-base-content/40 mt-1">5 hours ago</p>
                                </div>
                            </li>
                            <li class="step">
                                <div class="text-left ml-2">
                                    <p class="font-semibold text-sm">Project Created</p>
                                    <p class="text-xs text-base-content/60">Globex Landing Page initiated</p>
                                    <p class="text-xs text-base-content/40 mt-1">1 day ago</p>
                                </div>
                            </li>
                            <li class="step">
                                <div class="text-left ml-2">
                                    <p class="font-semibold text-sm">Server Update</p>
                                    <p class="text-xs text-base-content/60">System maintenance completed</p>
                                    <p class="text-xs text-base-content/40 mt-1">2 days ago</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
</script>
