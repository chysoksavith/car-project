<template>
    <DashboardLayout>
        <PageHeader
            title="Users Management"
            description="Manage system administrators and frontend users."
            class="mb-6"
        >
            <Button variant="primary" class="shrink-0">
                <i class="fa-solid fa-plus mr-2"></i>
                Add User
            </Button>
        </PageHeader>

        <DataTable
            :data="users"
            :columns="columns"
            searchRoute="/admin/users"
            :searchQuery="filters.search"
            searchPlaceholder="Search users by name, email..."
        >
            <template #cell(name)="{ item }">
                <div class="flex items-center gap-3">
                    <div class="avatar placeholder">
                        <div
                            class="bg-primary/10 text-primary rounded-full w-10 h-10 font-bold shadow-sm border border-primary/20"
                        >
                            <span>{{
                                item.first_name
                                    ? item.first_name.charAt(0)
                                    : "U"
                            }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="font-bold text-base-content">
                            {{ item.first_name }} {{ item.last_name }}
                        </div>
                        <div class="text-xs text-base-content/50">
                            Joined
                            {{ new Date(item.created_at).toLocaleDateString() }}
                        </div>
                    </div>
                </div>
            </template>

            <template #cell(contact)="{ item }">
                <div class="text-sm font-medium">{{ item.email }}</div>
                <div class="text-xs text-base-content/60">
                    {{ item.phone_number || "No phone" }}
                </div>
            </template>

            <template #cell(role)="{ item }">
                <span
                    class="badge badge-sm font-medium"
                    :class="
                        item.user_type === 'backend'
                            ? 'badge-primary badge-outline'
                            : 'badge-ghost'
                    "
                >
                    {{ item.user_type }}
                </span>
            </template>

            <template #cell(status)="{ item }">
                <div class="flex items-center gap-2">
                    <div
                        class="w-2 h-2 rounded-full"
                        :class="item.is_active ? 'bg-success' : 'bg-error'"
                    ></div>
                    <span
                        class="text-sm font-medium"
                        :class="item.is_active ? 'text-success' : 'text-error'"
                    >
                        {{ item.is_active ? "Active" : "Inactive" }}
                    </span>
                </div>
            </template>

            <template #cell(actions)="{ item }">
                <TableActionButtons
                    @edit="console.log('Edit', item.id)"
                    @delete="console.log('Delete', item.id)"
                />
            </template>
        </DataTable>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Button from "@/Components/Button.vue";
import DataTable from "@/Components/DataTable.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import PageHeader from "@/Components/PageHeader.vue";

defineProps<{
    users: any;
    filters: any;
}>();

const columns = [
    { key: "name", label: "Name" },
    { key: "contact", label: "Contact Info" },
    { key: "role", label: "Role" },
    { key: "status", label: "Status" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
