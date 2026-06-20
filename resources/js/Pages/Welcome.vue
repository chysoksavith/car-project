<template>
    <DashboardLayout>
        <div class="flex flex-col gap-6">
            
            <!-- Page Header -->
            <!-- Page Header -->
            <PageHeader 
                title="Dashboard Overview" 
                description="Welcome back! Here's what's happening with your app today."
            >
                <div class="flex items-center gap-3">
                    <Button variant="ghost" class="btn-circle" icon="fa-solid fa-search text-xl" />
                    
                    <Button variant="ghost" class="btn-circle">
                        <div class="indicator">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span class="badge badge-xs badge-error indicator-item"></span>
                        </div>
                    </Button>
                    
                    <Button variant="primary" class="shadow-lg shadow-primary/30 ml-2" icon="fa-solid fa-plus mr-1 -ml-1">
                        New Project
                    </Button>
                </div>
            </PageHeader>

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
                            <div class="p-3 bg-success/10 rounded-xl text-success w-12 h-12 flex items-center justify-center">
                                <i class="fa-solid fa-dollar-sign text-xl"></i>
                            </div>
                        </div>
                        <p class="text-success text-sm mt-4 flex items-center font-medium">
                            <i class="fa-solid fa-arrow-trend-up mr-1"></i>
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
                            <div class="p-3 bg-primary/10 rounded-xl text-primary w-12 h-12 flex items-center justify-center">
                                <i class="fa-solid fa-users text-xl"></i>
                            </div>
                        </div>
                        <p class="text-success text-sm mt-4 flex items-center font-medium">
                            <i class="fa-solid fa-arrow-trend-up mr-1"></i>
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
                            <div class="p-3 bg-error/10 rounded-xl text-error w-12 h-12 flex items-center justify-center">
                                <i class="fa-regular fa-circle-check text-xl"></i>
                            </div>
                        </div>
                        <p class="text-error text-sm mt-4 flex items-center font-medium">
                            <i class="fa-solid fa-arrow-trend-down mr-1"></i>
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
import PageHeader from '@/Components/PageHeader.vue';
import Button from '@/Components/Button.vue';
</script>
