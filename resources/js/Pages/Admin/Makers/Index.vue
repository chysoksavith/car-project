<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Makers' }]"
            description="Manage your car makers and brands."
            class="mb-6"
        >
            <Button
                v-if="can('makers.create')"
                :href="route('admin.makers.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Maker
            </Button>
        </PageHeader>

        <DataTable
            :data="makers"
            :columns="columns"
            searchRoute="/admin/makers"
            :searchQuery="filters.search"
            searchPlaceholder="Search makers..."
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
                <TableActionButtons
                    :hasEdit="can('makers.edit')"
                    :hasDelete="can('makers.delete')"
                    @edit="router.visit(route('admin.makers.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Confirm Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Maker">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{ deletingMaker?.name }}</strong>? This cannot be undone.
            </p>

            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteMaker" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    makers: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingMaker = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (maker: any) => {
    deletingMaker.value = maker;
    deleteModalRef.value?.showModal();
};

// # Delete Maker
const deleteMaker = () => {
    if (!deletingMaker.value) return;
    deleteForm.delete(route('admin.makers.destroy', deletingMaker.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingMaker.value = null, 300);
        },
    });
};

const columns = [
    { key: "id", label: "ID", class: "w-16 font-mono text-base-content/60" },
    { key: "name", label: "Name" },
    { key: "status", label: "Status" },
    { key: "created_at", label: "Created" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
