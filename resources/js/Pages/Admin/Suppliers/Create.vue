<template>
    <DashboardLayout>
        <PageHeader
            title="Add Supplier"
            description="Create a new supplier."
            class="mb-6"
            :backUrl="route('admin.suppliers.index')"
        />

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <SupplierForm />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import SupplierForm from "./SupplierForm.vue";
</script>
