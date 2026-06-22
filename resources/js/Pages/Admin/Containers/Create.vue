<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Containers', url: route('admin.containers.index') }, { label: 'Add Container' }]"
            description="Create a new container."
            class="mb-6"
            :backUrl="route('admin.containers.index')"
        />

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <ContainerForm 
                    :suppliers="suppliers"
                    :makers="makers"
                    :carModels="carModels"
                    :fuels="fuels"
                    :colors="colors"
                />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ContainerForm from "./ContainerForm.vue";

defineProps<{
    suppliers: any[];
    makers: any[];
    carModels: any[];
    fuels: any[];
    colors: any[];
}>();
</script>
