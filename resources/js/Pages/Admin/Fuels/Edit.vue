<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Fuels', url: route('admin.fuels.index') }, { label: 'Edit Fuel' }]"
            description="Update fuel details."
            class="mb-6"
        >
            <Link
                :href="route('admin.fuels.index')"
                class="btn btn-ghost btn-sm"
            >
                <i class="fa-solid fa-arrow-left mr-2"></i> Back
            </Link>
        </PageHeader>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <FuelForm :fuel="fuel.data || fuel" isEdit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import FuelForm from "./FuelForm.vue";
import { Link } from "@inertiajs/vue3";

defineProps<{
    fuel: any;
}>();
</script>
