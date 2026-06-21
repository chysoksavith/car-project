<template>
    <DashboardLayout>
        <PageHeader
            title="Car Models"
            description="Manage your car models."
            class="mb-6"
        >
            <Button
                v-if="can('car_models.create')"
                :href="route('admin.car-models.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Model
            </Button>
        </PageHeader>

        <DataTable
            :data="carModels"
            :columns="columns"
            searchRoute="/admin/car-models"
            :searchQuery="filters.search"
            searchPlaceholder="Search models..."
        >
            <template #cell(maker)="{ item }">
                <span class="font-medium text-base-content/70">{{ item.maker?.name }}</span>
            </template>

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
                <TableActionButtons
                    :hasEdit="can('car_models.edit')"
                    :hasDelete="can('car_models.delete')"
                    @edit="router.visit(route('admin.car-models.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Confirm Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Model">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{ deletingModel?.name }}</strong>? This cannot be undone.
            </p>

            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteModel" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    carModels: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingModel = ref<any>(null);
const deleteForm = useForm({});

const confirmDelete = (model: any) => {
    deletingModel.value = model;
    deleteModalRef.value?.showModal();
};

const deleteModel = () => {
    if (!deletingModel.value) return;
    deleteForm.delete(route('admin.car-models.destroy', deletingModel.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingModel.value = null, 300);
        },
    });
};

const columns = [
    { key: "id", label: "ID", class: "w-16 font-mono text-base-content/60" },
    { key: "maker", label: "Maker" },
    { key: "name", label: "Model Name" },
    { key: "status", label: "Status" },
    { key: "created_at", label: "Created" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
