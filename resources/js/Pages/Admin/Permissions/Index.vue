<template>
    <DashboardLayout>
        <div class="flex flex-col gap-6">
            <!-- Page Header -->
            <PageHeader
                :breadcrumbs="[{ label: 'Permissions' }]"
                description="Define granular permissions that can be assigned to roles."
            >
                <Button
                    v-if="can('permissions.create')"
                    @click="openCreate"
                    variant="primary"
                    size="sm"
                    icon="fa-solid fa-plus"
                >
                    New Permission
                </Button>
            </PageHeader>

            <!-- DataTable -->
            <DataTable
                :data="permissions"
                :columns="columns"
                searchRoute="/admin/permissions"
                :searchQuery="filters?.search || ''"
                searchPlaceholder="Search permissions..."
            >
                <template #cell(name)="{ item }">
                    <span
                        class="font-mono text-sm font-medium text-base-content"
                        >{{ item.name }}</span
                    >
                </template>

                <template #cell(actions)="{ item }">
                    <div @click.stop class="flex justify-end">
                    <TableActionButtons
                        :hasEdit="can('permissions.edit')"
                        :hasDelete="can('permissions.delete')"
                        @edit="openEdit(item)"
                        @delete="confirmDelete(item)"
                    />
                </div>
                </template>
            </DataTable>
        </div>

        <!-- # Create / Edit Modal -->
        <Modal
            ref="modalRef"
            maxWidth="sm"
            :title="editingPerm ? 'Edit Permission' : 'Create Permission'"
        >
            <TextInput
                label="Permission Name"
                v-model="form.name"
                placeholder="e.g. users.edit"
                inputClass="font-mono"
                :error="form.errors.name"
                hint="Use dot notation: resource.action"
                @keyup.enter="save"
                required
            />

            <template #actions>
                <Button @click="modalRef?.close()" variant="ghost" type="button"
                    >Cancel</Button
                >
                <Button
                    @click="save"
                    :loading="form.processing"
                    variant="primary"
                    type="button"
                >
                    {{ editingPerm ? "Save" : "Create" }}
                </Button>
            </template>
        </Modal>

        <!-- # Delete Confirm -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Permission">
            <p class="text-sm text-base-content/70">
                Delete
                <strong class="font-mono text-base-content">{{
                    deletingPerm?.name
                }}</strong
                >? Any role or user that has this permission will lose it
                immediately.
            </p>

            <template #actions>
                <Button
                    @click="deleteModalRef?.close()"
                    variant="ghost"
                    type="button"
                    >Cancel</Button
                >
                <Button
                    @click="deletePerm"
                    :loading="deleteForm.processing"
                    variant="error"
                    type="button"
                    >Delete</Button
                >
            </template>
        </Modal>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DataTable from "@/Components/DataTable.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import { usePermission } from "@/Composables/usePermission";

// # Props (data from the controller)
const props = defineProps<{
    permissions: any; // Paginated data object
    filters: { search?: string };
}>();

// # Columns
const columns = [
    { key: "no", label: "No.",  },
    { key: "name", label: "Permission Name" },
    { key: "actions", label: "Actions", class: "text-right" },
];

// # Composables
const {
    modalRef,
    deleteModalRef,
    editingPerm,
    deletingPerm,
    form,
    deleteForm,
    openCreate,
    openEdit,
    confirmDelete,
    save,
    deletePerm,
} = usePermission();
</script>
