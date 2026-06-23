<template>
    <DashboardLayout>
        <PageHeader
            :breadcrumbs="[{ label: 'Containers' }]"
            description="Manage your containers and containers."
            class="mb-6"
        >
            <Button
                v-if="can('containers.create')"
                :href="route('admin.containers.create')"
                variant="primary"
                size="sm"
                icon="fa-solid fa-plus"
            >
                Add Container
            </Button>
        </PageHeader>

        <DataTable
            :data="containers"
            :columns="columns"
            searchRoute="/admin/containers"
            :searchQuery="filters.search"
            searchPlaceholder="Search B/L or Container..."
            @row-click="handleRowClick"
        >
            <template #cell(bl_number)="{ item }">
                <div class="font-medium text-base-content">{{ item.bl_number }}</div>
            </template>

            <template #cell(container)="{ item }">
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-base-content">{{ item.container_number }}</span>
                </div>
            </template>

            <template #cell(cars)="{ item }">
                <div class="flex items-center justify-center">
                    <div class="badge badge-primary badge-outline gap-1 font-semibold">
                        <i class="fa-solid fa-car-side text-[10px]"></i>
                        {{ item.cars?.length || 0 }}
                    </div>
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
                <div @click.stop class="flex justify-end">
                    <TableActionButtons
                        :hasEdit="can('containers.edit')"
                        :hasDelete="can('containers.delete')"
                        @edit="router.visit(route('admin.containers.edit', item.id))"
                        @delete="confirmDelete(item)"
                    />
                </div>
            </template>
        </DataTable>

        <!-- Container Cars Sidebar -->
        <ContainerCarsSidebar 
            v-model="isSidebarOpen"
            :container="selectedContainer"
        />

        <!-- Delete Modal -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Container">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete container <strong class="text-base-content">{{ deletingContainer?.bl_number }}</strong>?
            </p>
            <template #actions>
                <Button @click="deleteModalRef?.close()" variant="ghost" type="button">Cancel</Button>
                <Button @click="deleteContainer" :loading="deleteForm.processing" variant="error" type="button">Delete</Button>
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
import ContainerCarsSidebar from "./Components/ContainerCarsSidebar.vue";

const props = defineProps<{
    containers: any;
    filters: any;
}>();

const columns = [
    { key: "no", label: "No.",  },
    { key: "bl_number", label: "B/L Number" },
    { key: "container", label: "Container Info" },
    { key: "cars", label: "Cars", class: "text-center" },
    { key: "status", label: "Status" },
    { key: "dates", label: "Dates" },
    { key: "actions", label: "Actions", class: "text-right" },
];

const deleteModalRef = ref<any>(null);
const deletingContainer = ref<any>(null);
const deleteForm = useForm({});

const isSidebarOpen = ref(false);
const selectedContainer = ref<any>(null);

// # Handle Row Click
const handleRowClick = (item: any) => {
    selectedContainer.value = item;
    isSidebarOpen.value = true;
};

// # Confirm Delete
const confirmDelete = (container: any) => {
    deletingContainer.value = container;
    deleteModalRef.value?.showModal();
};

// # Delete Container
const deleteContainer = () => {
    if (!deletingContainer.value) return;
    deleteForm.delete(route("admin.containers.destroy", deletingContainer.value.id), {
        onSuccess: () => {
            deleteModalRef.value?.close();
            setTimeout(() => (deletingContainer.value = null), 300);
        },
    });
};
</script>
