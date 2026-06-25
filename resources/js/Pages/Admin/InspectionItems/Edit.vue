<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Inspection Items', url: route('admin.inspection-items.index') }, { label: 'Edit Item' }]"
            description="Update an existing inspection item."
            class="mb-6"
            :backUrl="route('admin.inspection-items.index')"
        />

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <InspectionItemForm :inspectionItem="inspectionItem" :categories="categories" />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InspectionItemForm from "./InspectionItemForm.vue";
import type { InspectionItem, InspectionItemCategory } from "@/Types/inspection-item";

defineProps<{
    inspectionItem: InspectionItem;
    categories: InspectionItemCategory[];
}>();
</script>
