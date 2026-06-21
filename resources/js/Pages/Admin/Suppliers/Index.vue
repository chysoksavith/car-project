<template>
    <DashboardLayout>
        <PageHeader
            title="Suppliers"
            description="Manage suppliers for your vehicles and parts."
            class="mb-6"
        >
            <Button
                v-if="can('suppliers.create')"
                :href="route('admin.suppliers.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Supplier
            </Button>
        </PageHeader>

        <DataTable
            :data="suppliers"
            :columns="columns"
            searchRoute="/admin/suppliers"
            :searchQuery="filters.search"
            searchPlaceholder="Search suppliers..."
        >
            <template #cell(name)="{ item }">
                <div class="font-medium text-base-content">{{ item.name }}</div>
            </template>

            <template #cell(contact)="{ item }">
                <div class="flex flex-col">
                    <span v-if="item.contact_person" class="text-sm">{{ item.contact_person }}</span>
                    <span v-if="item.phone" class="text-xs text-base-content/70">{{ item.phone }}</span>
                    <span v-if="!item.contact_person && !item.phone" class="text-xs text-base-content/50 italic">N/A</span>
                </div>
            </template>

            <template #cell(email)="{ item }">
                <span v-if="item.email" class="text-sm text-primary">{{ item.email }}</span>
                <span v-else class="text-xs text-base-content/50 italic">None</span>
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
                    :hasEdit="can('suppliers.edit')"
                    :hasDelete="can('suppliers.delete')"
                    @edit="router.visit(route('admin.suppliers.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Supplier">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete <strong class="text-base-content">{{ deletingSupplier?.name }}</strong>?
            </p>
            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteSupplier" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
            </template>
        </Modal>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "@/Components/Button.vue";
import DataTable from "@/Components/DataTable.vue";
import Badge from "@/Components/Badge.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps<{
    suppliers: any;
    filters: any;
}>();

const columns = [
    { key: "no", label: "No.", class: "w-16" },
    { key: "name", label: "Supplier Name" },
    { key: "contact", label: "Contact Info" },
    { key: "email", label: "Email" },
    { key: "status", label: "Status" },
    { key: "actions", label: "Actions", class: "text-right" },
];

const deleteModalRef = ref<any>(null);
const deletingSupplier = ref<any>(null);
const deleteForm = useForm({});

const confirmDelete = (supplier: any) => {
    deletingSupplier.value = supplier;
    deleteModalRef.value?.showModal();
};

const deleteSupplier = () => {
    if (!deletingSupplier.value) return;
    deleteForm.delete(route("admin.suppliers.destroy", deletingSupplier.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => (deletingSupplier.value = null), 300);
        },
    });
};
</script>
