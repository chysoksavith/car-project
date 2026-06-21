<template>
    <DashboardLayout>
        <PageHeader :breadcrumbs="[{ label: 'Car Models', url: route('admin.car-models.index') }, { label: 'Edit Car Model' }]" description="Update the car model details." />
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <CarModelForm :carModel="carModel.data || carModel" :makers="makers" isEdit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import CarModelForm from "./CarModelForm.vue";

const props = defineProps<{
    carModel: any;
    makers: any[];
}>();
</script>
