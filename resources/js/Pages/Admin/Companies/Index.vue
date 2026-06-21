<template>
    <DashboardLayout>
        <div class="space-y-6">
            <PageHeader
                title="Companies"
                description="Manage companies in the system."
            >
                <Button
                    v-if="can('companies.create')"
                    :href="route('admin.companies.create')"
                    variant="primary"
                    size="sm"
                    icon="fa-solid fa-plus"
                >
                    Add Company
                </Button>
            </PageHeader>

            <DataTable
                :data="companies"
                :columns="columns"
                searchRoute="admin.companies.index"
                :searchQuery="filters.search"
                searchPlaceholder="Search companies..."
            >
                <template #cell(is_active)="{ item }">
                    <Badge 
                        :variant="item.is_active ? 'success' : 'error'"
                        outline
                        dot
                    >
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                    </Badge>
                </template>

                <template #cell(actions)="{ item }">
                    <TableActionButtons
                        :hasEdit="can('companies.edit')"
                        :hasDelete="can('companies.delete')"
                        @edit="router.visit(route('admin.companies.edit', item.id))"
                        @delete="confirmDelete(item)"
                    />
                </template>
            </DataTable>

            <!-- Delete Confirm Modal -->
            <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Company">
                <p class="text-sm text-base-content/70">
                    Are you sure you want to delete
                    <strong class="text-base-content">{{ deletingCompany?.name }}</strong>? This cannot be undone.
                </p>

                <template #actions>
                    <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                    <Button @click="deleteCompany" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
                </template>
            </Modal>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import DataTable from "@/Components/DataTable.vue";
import Badge from "@/Components/Badge.vue";
import Button from "@/Components/Button.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps<{
    companies: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingCompany = ref<any>(null);
const deleteForm = useForm({});

const confirmDelete = (company: any) => {
    deletingCompany.value = company;
    deleteModalRef.value?.showModal();
};

const deleteCompany = () => {
    if (!deletingCompany.value) return;
    deleteForm.delete(route('admin.companies.destroy', deletingCompany.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingCompany.value = null, 300);
        },
    });
};

const columns = [
    { key: "no", label: "No." },
    { key: "name", label: "Name" },
    { key: "email", label: "Email" },
    { key: "phone", label: "Phone" },
    { key: "is_active", label: "Status" },
    { key: "actions", label: "Actions", class: "w-32" },
];
</script>
