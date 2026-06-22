<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[
                { label: 'Cars', href: route('admin.cars.index') },
                { label: 'Add Car' }
            ]"
            title="Add New Car"
            description="Create a new car in the system."
            class="mb-6"
        />

        <div class="bg-base-100 rounded-xl p-6 shadow-sm border border-base-200">
            <CarForm 
                :makers="makers"
                :carModels="carModels"
                :fuels="fuels"
                :colors="colors"
                :containers="containers"
            />
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import CarForm from "./CarForm.vue";

defineProps<{
    makers: any[];
    carModels: any[];
    fuels: any[];
    colors: any[];
    containers: any[];
}>();
</script>
