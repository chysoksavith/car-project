<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Users', url: route('admin.users.index') }, { label: 'Edit User' }]"
            description="Update an existing user's information."
            class="mb-6"
        >
            <Link :href="route('admin.users.index')" class="btn btn-ghost btn-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back
            </Link>
        </PageHeader>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body">
                <UserForm :user="user" :roles="roles" :companies="companies" :departments="departments" isEdit />
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import UserForm from "./UserForm.vue";

const props = defineProps<{
    user: any;
    roles: any[];
    companies: any[];
    departments: any[];
}>();
</script>
