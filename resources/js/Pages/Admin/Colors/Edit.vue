<template>
    <DashboardLayout>
        <PageHeader :breadcrumbs="[{ label: 'Colors', url: route('admin.colors.index') }, { label: 'Edit Color' }]" description="Update color details." />
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <ColorForm :color="color.data || color" isEdit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ColorForm from "./ColorForm.vue";

defineProps<{
    color: any;
}>();
</script>
