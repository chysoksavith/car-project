<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Inspection Items' }]"
            description="Manage your technical inspection items and categories."
            class="mb-6"
        >
            <Button
                v-if="can('inspection_items.create')"
                :href="route('admin.inspection-items.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Item
            </Button>
        </PageHeader>

        <DataTable
            :data="inspectionItems"
            :columns="columns"
            searchRoute="/admin/inspection-items"
            :searchQuery="filters.search"
            searchPlaceholder="Search items or categories..."
        >
            <template #cell(name)="{ item }">
                <div class="flex flex-col">
                    <span class="font-medium text-base-content">{{ item.name_kh }}</span>
                    <span v-if="item.name_en" class="text-xs text-base-content/70">{{ item.name_en }}</span>
                </div>
            </template>

            <template #cell(category)="{ item }">
                <span v-if="item.parent" class="text-sm">
                    <i class="fa-solid fa-level-up fa-rotate-90 text-base-content/40 mr-1"></i>
                    {{ item.parent.name_kh }}
                </span>
                <span v-else class="text-xs font-bold text-primary uppercase tracking-wider">
                    Main Category
                </span>
            </template>

            <template #cell(price)="{ item }">
                <span class="font-medium text-success" v-if="item.default_price > 0">
                    ${{ item.default_price }}
                </span>
                <span class="text-xs text-base-content/50 italic" v-else>
                    --
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
                    :hasEdit="can('inspection_items.edit')"
                    :hasDelete="can('inspection_items.delete')"
                    @edit="router.visit(route('admin.inspection-items.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Inspection Item">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete <strong class="text-base-content">{{ deletingItem?.name_kh }}</strong>?
                <br><br>
                <span class="text-error font-medium text-xs" v-if="!deletingItem?.parent_id">
                    Note: You cannot delete a Main Category if it currently contains any sub-items.
                </span>
            </p>
            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteItem" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    inspectionItems: any;
    filters: any;
}>();

const columns = [
    { key: "no", label: "No.", class: "w-16" },
    { key: "name", label: "Item Name" },
    { key: "category", label: "Parent Category" },
    { key: "price", label: "Default Price" },
    { key: "status", label: "Status" },
    { key: "actions", label: "Actions", class: "text-right" },
];

const deleteModalRef = ref<any>(null);
const deletingItem = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (item: any) => {
    deletingItem.value = item;
    deleteModalRef.value?.showModal();
};

// # Delete Item
const deleteItem = () => {
    if (!deletingItem.value) return;
    deleteForm.delete(route("admin.inspection-items.destroy", deletingItem.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => (deletingItem.value = null), 300);
        },
    });
};
</script>
