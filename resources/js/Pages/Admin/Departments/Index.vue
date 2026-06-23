<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Departments' }]"
            description="Manage your car departments and brands."
            class="mb-6"
        >
            <Button
                v-if="can('departments.create')"
                :href="route('admin.departments.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Department
            </Button>
        </PageHeader>

        <DataTable
            :data="departments"
            :columns="columns"
            searchRoute="/admin/departments"
            :searchQuery="filters.search"
            searchPlaceholder="Search departments..."
        >
            <template #cell(name)="{ item }">
                <div class="font-bold text-base-content">{{ item.name }}</div>
            </template>

            <template #cell(created_at)="{ item }">
                <span class="text-sm text-base-content/70">
                    {{ new Date(item.created_at).toLocaleDateString() }}
                </span>
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

            <template #cell(actions)="{ item }">
                <div @click.stop class="flex justify-end">
                    <TableActionButtons
                    :hasEdit="can('departments.edit')"
                    :hasDelete="can('departments.delete')"
                    @edit="router.visit(route('admin.departments.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
                </div>
            </template>
        </DataTable>

        <!-- Delete Confirm Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Department">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{ deletingDepartment?.name }}</strong>? This cannot be undone.
            </p>

            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteDepartment" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
            </template>
        </Modal>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Button from "@/Components/Button.vue";
import Badge from "@/Components/Badge.vue";
import DataTable from "@/Components/DataTable.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps<{
    departments: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingDepartment = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (department: any) => {
    deletingDepartment.value = department;
    deleteModalRef.value?.showModal();
};

// # Delete Department
const deleteDepartment = () => {
    if (!deletingDepartment.value) return;
    deleteForm.delete(route('admin.departments.destroy', deletingDepartment.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingDepartment.value = null, 300);
        },
    });
};

const columns = [
    { key: "id", label: "ID", class: "font-mono text-base-content/60" },
    { key: "code", label: "Code" },
    { key: "name", label: "Name" },
    { key: "status", label: "Status" },
    { key: "created_at", label: "Created" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
