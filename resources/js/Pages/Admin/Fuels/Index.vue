<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Fuels' }]"
            description="Manage your car fuels and brands."
            class="mb-6"
        >
            <Button
                v-if="can('fuels.create')"
                :href="route('admin.fuels.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Fuel
            </Button>
        </PageHeader>

        <DataTable
            :data="fuels"
            :columns="columns"
            searchRoute="/admin/fuels"
            :searchQuery="filters.search"
            searchPlaceholder="Search fuels..."
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
                    :hasEdit="can('fuels.edit')"
                    :hasDelete="can('fuels.delete')"
                    @edit="router.visit(route('admin.fuels.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
                </div>
            </template>
        </DataTable>

        <!-- Delete Confirm Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Fuel">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{ deletingFuel?.name }}</strong>? This cannot be undone.
            </p>

            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteFuel" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    fuels: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingFuel = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (fuel: any) => {
    deletingFuel.value = fuel;
    deleteModalRef.value?.showModal();
};

// # Delete Fuel
const deleteFuel = () => {
    if (!deletingFuel.value) return;
    deleteForm.delete(route('admin.fuels.destroy', deletingFuel.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingFuel.value = null, 300);
        },
    });
};

const columns = [
    { key: "id", label: "ID", class: "font-mono text-base-content/60" },
    { key: "code", label: "Code" },
    { key: "name", label: "Name" },
    { key: "sort_order", label: "Sort" },
    { key: "status", label: "Status" },
    { key: "created_at", label: "Created" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
