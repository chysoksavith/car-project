<template>
    <DashboardLayout>
        <div class="flex flex-col gap-6">
            <!-- Page Header -->
            <PageHeader
                title="Permissions"
                description="Define granular permissions that can be assigned to roles."
            >
                <Button
                    @click="openCreate"
                    variant="primary"
                    size="sm"
                    icon="fa-solid fa-plus"
                >
                    New Permission
                </Button>
            </PageHeader>

            <!-- DataTable -->
            <DataTable
                :data="permissions"
                :columns="columns"
                searchRoute="/admin/permissions"
                :searchQuery="filters?.search || ''"
                searchPlaceholder="Search permissions..."
            >
                <template #cell(name)="{ item }">
                    <span
                        class="font-mono text-sm font-medium text-base-content"
                        >{{ item.name }}</span
                    >
                </template>

                <template #cell(actions)="{ item }">
                    <TableActionButtons
                        @edit="openEdit(item)"
                        @delete="confirmDelete(item)"
                    />
                </template>
            </DataTable>
        </div>

        <!-- ── Create / Edit Modal ─────────────────────────────────────── -->
        <Modal
            ref="modalRef"
            maxWidth="sm"
            :title="editingPerm ? 'Edit Permission' : 'Create Permission'"
        >
            <TextInput
                label="Permission Name"
                v-model="form.name"
                placeholder="e.g. users.edit"
                inputClass="font-mono"
                :error="form.errors.name"
                hint="Use dot notation: resource.action"
                @keyup.enter="save"
            />

            <template #actions>
                <Button @click="modalRef?.close()" variant="ghost" type="button"
                    >Cancel</Button
                >
                <Button
                    @click="save"
                    :loading="form.processing"
                    variant="primary"
                    type="button"
                >
                    {{ editingPerm ? "Save" : "Create" }}
                </Button>
            </template>
        </Modal>

        <!-- ── Delete Confirm ──────────────────────────────────────────── -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Permission">
            <p class="text-sm text-base-content/70">
                Delete
                <strong class="font-mono text-base-content">{{
                    deletingPerm?.name
                }}</strong
                >? Any role or user that has this permission will lose it
                immediately.
            </p>

            <template #actions>
                <Button
                    @click="deleteModalRef?.close()"
                    variant="ghost"
                    type="button"
                    >Cancel</Button
                >
                <Button
                    @click="deletePerm"
                    :loading="deleteForm.processing"
                    variant="error"
                    type="button"
                    >Delete</Button
                >
            </template>
        </Modal>
    </DashboardLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DataTable from "@/Components/DataTable.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/Form/TextInput.vue";

interface Permission {
    id: number;
    name: string;
}

// ── Props (data from the controller) ─────────────────────────────────────
const props = defineProps<{
    permissions: any; // Paginated data object
    filters: { search?: string };
}>();

// ── Columns ───────────────────────────────────────────────────────────────
const columns = [
    { key: "name", label: "Permission Name" },
    { key: "actions", label: "Actions", class: "w-24 text-right" },
];

// ── Local state ───────────────────────────────────────────────────────────
const modalRef = ref<HTMLDialogElement | null>(null);
const deleteModalRef = ref<HTMLDialogElement | null>(null);
const editingPerm = ref<Permission | null>(null);
const deletingPerm = ref<Permission | null>(null);

// ── Forms ─────────────────────────────────────────────────────────────────
const form = useForm({ name: "" });
const deleteForm = useForm({});

// ── Modal helpers ─────────────────────────────────────────────────────────
function openCreate() {
    editingPerm.value = null;
    form.reset();
    form.clearErrors();
    modalRef.value?.showModal();
}

function openEdit(perm: Permission) {
    editingPerm.value = perm;
    form.name = perm.name;
    form.clearErrors();
    modalRef.value?.showModal();
}

function confirmDelete(perm: Permission) {
    deletingPerm.value = perm;
    deleteModalRef.value?.showModal();
}

// ── CRUD ──────────────────────────────────────────────────────────────────
function save() {
    if (editingPerm.value) {
        form.put(route("admin.permissions.update", editingPerm.value.id), {
            onSuccess: () => modalRef.value?.close(),
        });
    } else {
        form.post(route("admin.permissions.store"), {
            onSuccess: () => {
                form.reset();
                modalRef.value?.close();
            },
        });
    }
}

function deletePerm() {
    if (!deletingPerm.value) return;
    deleteForm.delete(
        route("admin.permissions.destroy", deletingPerm.value.id),
        {
            onSuccess: () => deleteModalRef.value?.close(),
        },
    );
}
</script>
