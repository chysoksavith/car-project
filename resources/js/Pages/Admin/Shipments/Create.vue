<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Shipments', url: route('admin.shipments.index') }, { label: 'Add Shipment' }]"
            description="Create a new shipment."
            class="mb-6"
            :backUrl="route('admin.shipments.index')"
        />

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <ShipmentForm :suppliers="suppliers" />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ShipmentForm from "./ShipmentForm.vue";

defineProps<{
    suppliers: any[];
}>();
</script>
