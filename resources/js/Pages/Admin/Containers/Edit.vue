<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Containers', url: route('admin.containers.index') }, { label: 'Edit Container' }]"
            description="Update an existing container."
            class="mb-6"
            :backUrl="route('admin.containers.index')"
        />

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <ContainerForm :container="container.data" :suppliers="suppliers" isEdit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ContainerForm from "./ContainerForm.vue";

defineProps<{
    container: any;
    suppliers: any[];
}>();
</script>
