<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Users Management' }]"
            description="Manage system administrators and frontend users."
            class="mb-6"
        >
            <Button
                v-if="can('users.create')"
                :href="route('admin.users.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
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
                <Badge
                    :variant="item.user_type === 'backend' ? 'primary' : 'ghost'"
                    :outline="item.user_type === 'backend'"
                >
                    {{ item.user_type }}
                </Badge>
            </template>

            <template #cell(status)="{ item }">
                <Badge
                    :variant="item.is_active ? 'success' : 'error'"
                    outline
                    dot
                >
                    {{ item.is_active ? "Active" : "Inactive" }}
                </Badge>
            </template>

            <template #cell(audit)="{ item }">
                <div class="text-[11px] text-base-content/70 flex flex-col gap-0.5">
                    <div v-if="item.creator_name">
                        <span class="font-semibold">Created:</span> {{ item.creator_name }}
                    </div>
                    <div v-if="item.updater_name && item.updater_name !== item.creator_name">
                        <span class="font-semibold">Updated:</span> {{ item.updater_name }}
                    </div>
                    <div v-if="!item.creator_name && !item.updater_name" class="opacity-50 italic">
                        System created
                    </div>
                </div>
            </template>

            <template #cell(actions)="{ item }">
                <TableActionButtons
                    :hasEdit="can('users.edit')"
                    :hasDelete="can('users.delete')"
                    @edit="router.visit(route('admin.users.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- # Delete Confirm Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete User">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{ deletingUser?.first_name }} {{ deletingUser?.last_name }}</strong>? This cannot be undone.
            </p>

            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteUser" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
            </template>
        </Modal>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Button from "@/Components/Button.vue";
import Badge from "@/Components/Badge.vue";
import DataTable from "@/Components/DataTable.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps<{
    users: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingUser = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (user: any) => {
    deletingUser.value = user;
    deleteModalRef.value?.showModal();
};

// # Delete User
const deleteUser = () => {
    if (!deletingUser.value) return;
    deleteForm.delete(route('admin.users.destroy', deletingUser.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingUser.value = null, 300);
        },
    });
};

const columns = [
    { key: "no", label: "No.", class: "w-16" },
    { key: "name", label: "Name" },
    { key: "contact", label: "Contact Info" },
    { key: "role", label: "Role" },
    { key: "status", label: "Status" },
    { key: "audit", label: "Audit" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
