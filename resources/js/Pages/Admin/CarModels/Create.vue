<template>
    <DashboardLayout>
        <PageHeader :breadcrumbs="[{ label: 'Car Models', url: route('admin.car-models.index') }, { label: 'Create Car Model' }]" description="Add a new car model to the system." />
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <CarModelForm :makers="makers" />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import CarModelForm from "./CarModelForm.vue";

const props = defineProps<{
    makers: any[];
}>();
</script>
