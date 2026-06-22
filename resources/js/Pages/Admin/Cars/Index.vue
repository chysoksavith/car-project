<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Cars' }]"
            description="Manage your inventory of cars."
            class="mb-6"
        >
            <Button
                v-if="can('cars.create')"
                :href="route('admin.cars.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Car
            </Button>
        </PageHeader>

        <DataTable
            :data="cars"
            :columns="columns"
            searchRoute="/admin/cars"
            :searchQuery="filters.search"
            searchPlaceholder="Search cars..."
        >
            <template #cell(name)="{ item }">
                <div class="font-bold text-base-content">{{ item.name }}</div>
                <div class="text-sm text-base-content/70" v-if="item.plate_number">
                    Plate: {{ item.plate_number }}
                </div>
            </template>

            <template #cell(maker)="{ item }">
                {{ item.maker?.name || '-' }} / {{ item.car_model?.name || '-' }}
            </template>

            <template #cell(inventory_status)="{ item }">
                <Badge
                    :variant="getInventoryStatusVariant(item.inventory_status)"
                    outline
                >
                    {{ item.inventory_status }}
                </Badge>
            </template>

            <template #cell(sales_status)="{ item }">
                <Badge
                    :variant="getSalesStatusVariant(item.sales_status)"
                    outline
                >
                    {{ item.sales_status }}
                </Badge>
            </template>

            <template #cell(actions)="{ item }">
                <TableActionButtons
                    :hasEdit="can('cars.edit')"
                    :hasDelete="can('cars.delete')"
                    @edit="router.visit(route('admin.cars.edit', item.id))"
                    @delete="confirmDelete(item)"
                />
            </template>
        </DataTable>

        <!-- Delete Confirm Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Car">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{ deletingCar?.name }}</strong>? This cannot be undone.
            </p>

            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteCar" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
    cars: any;
    filters: any;
}>();

const deleteModalRef = ref<any>(null);
const deletingCar = ref<any>(null);
const deleteForm = useForm({});

// # Confirm Delete
const confirmDelete = (car: any) => {
    deletingCar.value = car;
    deleteModalRef.value?.showModal();
};

// # Delete Car
const deleteCar = () => {
    if (!deletingCar.value) return;
    deleteForm.delete(route('admin.cars.destroy', deletingCar.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => deletingCar.value = null, 300);
        },
    });
};

const getInventoryStatusVariant = (status: string) => {
    const map: Record<string, string> = {
        'IN_TRANSIT': 'warning',
        'IN_SHOWROOM': 'info',
        'DELIVERED': 'success',
    };
    return map[status] || 'neutral';
};

const getSalesStatusVariant = (status: string) => {
    const map: Record<string, string> = {
        'AVAILABLE': 'success',
        'BOOKED': 'warning',
        'RESERVED': 'warning',
        'SOLD': 'info',
    };
    return map[status] || 'neutral';
};

const columns = [
    { key: "id", label: "ID", class: "w-16 font-mono text-base-content/60" },
    { key: "name", label: "Name & Plate" },
    { key: "maker", label: "Make / Model" },
    { key: "inventory_status", label: "Inventory" },
    { key: "sales_status", label: "Sales" },
    { key: "actions", label: "Actions", class: "text-right" },
];
</script>
