<template>
    <DashboardLayout>
        <PageHeader title="Create Color" description="Add a new color to the system." />
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <ColorForm />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ColorForm from "./ColorForm.vue";
</script>
