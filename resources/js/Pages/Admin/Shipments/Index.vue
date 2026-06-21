<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Shipments' }]"
            description="Manage your shipments and containers."
            class="mb-6"
        >
            <Button
                v-if="can('shipments.create')"
                :href="route('admin.shipments.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Shipment
            </Button>
        </PageHeader>

        <DataTable
            :data="shipments"
            :columns="columns"
            searchRoute="/admin/shipments"
            :searchQuery="filters.search"
            searchPlaceholder="Search B/L or Container..."
        >
            <template #cell(bl_number)="{ item }">
                <div class="font-medium text-base-content">{{ item.bl_number }}</div>
            </template>

            <template #cell(container)="{ item }">
                <div class="flex flex-col">
                    <span class="text-sm">{{ item.container_number }}</span>
                    <span v-if="item.container_type" class="text-xs text-base-content/70">{{ item.container_type }}</span>
                </div>
            </template>

            <template #cell(status)="{ item }">
                <Badge
                    :variant="item.status === 'delivered' ? 'success' : (item.status === 'in_stock' ? 'info' : 'warning')"
                    outline
                    dot
                >
                    {{ item.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                </Badge>
            </template>

            <template #cell(dates)="{ item }">
                <div class="flex flex-col text-xs">
                    <span v-if="item.departure_date"><span class="text-base-content/60">Dep:</span> {{ item.departure_date }}</span>
                    <span v-if="item.expected_date"><span class="text-base-content/60">Exp:</span> {{ item.expected_date }}</span>
                </div>
            </template>

            <template #cell(actions)="{ item }">
                <TableActionButtons
                    :hasEdit="can('shipments.edit')"
                    :hasDelete="can('shipments.delete')"
                    @edit="router.visit(route('admin.shipments.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Shipment">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete shipment <strong class="text-base-content">{{ deletingShipment?.bl_number }}</strong>?
            </p>
            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteShipment" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    shipments: any;
    filters: any;
}>();

const columns = [
    { key: "no", label: "No.", class: "w-16" },
    { key: "bl_number", label: "B/L Number" },
    { key: "container", label: "Container Info" },
    { key: "status", label: "Status" },
    { key: "dates", label: "Dates" },
    { key: "actions", label: "Actions", class: "text-right" },
];

const deleteModalRef = ref<any>(null);
const deletingShipment = ref<any>(null);
const deleteForm = useForm({});

const confirmDelete = (shipment: any) => {
    deletingShipment.value = shipment;
    deleteModalRef.value?.showModal();
};

const deleteShipment = () => {
    if (!deletingShipment.value) return;
    deleteForm.delete(route("admin.shipments.destroy", deletingShipment.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => (deletingShipment.value = null), 300);
        },
    });
};
</script>
