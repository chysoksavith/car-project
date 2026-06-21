<template>
    <DashboardLayout>
        <PageHeader
            title="Edit Company"
            description="Update company details."
            class="mb-6"
        >
            <Link
                :href="route('admin.companies.index')"
                class="btn btn-ghost btn-sm"
            >
                <i class="fa-solid fa-arrow-left mr-2"></i> Back
            </Link>
        </PageHeader>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <CompanyForm :company="company" isEdit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import CompanyForm from "./CompanyForm.vue";
import { Link } from "@inertiajs/vue3";

defineProps<{
    company: any;
}>();
</script>
