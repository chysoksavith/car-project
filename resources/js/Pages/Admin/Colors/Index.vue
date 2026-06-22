<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Colors' }]"
            description="Manage colors available for vehicles."
            class="mb-6"
        >
            <Button
                v-if="can('colors.create')"
                :href="route('admin.colors.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Color
            </Button>
        </PageHeader>

        <DataTable
            :data="colors"
            :columns="columns"
            searchRoute="/admin/colors"
            :searchQuery="filters.search"
            searchPlaceholder="Search colors..."
        >
            <template #cell(name)="{ item }">
                <div class="flex items-center gap-3">
                    <div
                        v-if="item.hex_code"
                        class="w-8 h-8 rounded-full border border-base-300 shadow-sm shrink-0"
                        :style="{ backgroundColor: item.hex_code }"
                    ></div>
                    <div class="font-medium text-base-content">{{ item.name }}</div>
                </div>
            </template>

            <template #cell(hex_code)="{ item }">
                <span v-if="item.hex_code" class="font-mono text-sm bg-base-200 px-2 py-1 rounded">
                    {{ item.hex_code }}
                </span>
                <span v-else class="text-base-content/50 italic text-sm">None</span>
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
                    :hasEdit="can('colors.edit')"
                    :hasDelete="can('colors.delete')"
                    @edit="router.visit(route('admin.colors.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Color">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete <strong class="text-base-content">{{ deletingColor?.name }}</strong>?
            </p>
            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteColor" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    colors: any;
    filters: any;
}>();

const columns = [
    { key: "no", label: "No.", class: "w-16" },
    { key: "name", label: "Color Name" },
    { key: "hex_code", label: "Hex Code" },
    { key: "status", label: "Status" },
    { key: "actions", label: "Actions", class: "text-right" },
];

const deleteModalRef = ref<any>(null);
const deletingColor = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (color: any) => {
    deletingColor.value = color;
    deleteModalRef.value?.showModal();
};

// # Delete Color
const deleteColor = () => {
    if (!deletingColor.value) return;
    deleteForm.delete(route("admin.colors.destroy", deletingColor.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => (deletingColor.value = null), 300);
        },
    });
};
</script>
