<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Suppliers', url: route('admin.suppliers.index') }, { label: 'Edit Supplier' }]"
            description="Update the supplier details."
            class="mb-6"
            :backUrl="route('admin.suppliers.index')"
        />

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <SupplierForm :supplier="supplier.data" is-edit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import SupplierForm from "./SupplierForm.vue";

defineProps<{
    supplier: any;
}>();
</script>
