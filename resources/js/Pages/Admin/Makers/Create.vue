<template>
    <DashboardLayout>
        <PageHeader
            title="Create Maker"
            description="Add a new maker to the system."
            class="mb-6"
        >
            <Link
                :href="route('admin.makers.index')"
                class="btn btn-ghost btn-sm"
            >
                <i class="fa-solid fa-arrow-left mr-2"></i> Back
            </Link>
        </PageHeader>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <MakerForm />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import MakerForm from "./MakerForm.vue";
import { Link } from "@inertiajs/vue3";
</script>
