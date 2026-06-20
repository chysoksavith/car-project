<template>
    <DashboardLayout>
        <div class="flex flex-col gap-6">
            <!-- Page Header -->
            <PageHeader
                title="Roles"
                description="Manage roles and their permissions."
            />

            <!-- DataTable -->
            <DataTable
                :data="roles"
                :columns="columns"
                searchRoute="/admin/roles"
                :searchQuery="filters.search"
                searchPlaceholder="Search roles..."
            >
                <template #toolbar-actions>
                    <Button
                        @click="openCreate"
                        variant="primary"
                        size="sm"
                        icon="fa-solid fa-plus"
                    >
                        New Role
                    </Button>
                </template>

                <template #cell(name)="{ item }">
                    <span class="font-semibold text-sm text-base-content">{{
                        item.name
                    }}</span>
                </template>

                <template #cell(permissions)="{ item }">
                    <div class="flex flex-wrap gap-1">
                        <span
                            v-for="perm in item.permissions"
                            :key="perm.id"
                            class="badge badge-ghost badge-sm font-mono"
                            >{{ perm.name }}</span
                        >
                        <span
                            v-if="!item.permissions.length"
                            class="text-base-content/40 text-xs italic"
                            >No permissions</span
                        >
                    </div>
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
            maxWidth="2xl"
            :title="editingRole ? 'Edit Role' : 'Create Role'"
        >
            <div class="flex flex-col gap-6">
                <!-- Name -->
                <TextInput
                    label="Role Name"
                    v-model="form.name"
                    placeholder="e.g. editor"
                    :error="form.errors.name"
                    @keyup.enter="save"
                />

                <!-- Permissions (Grouped) -->
                <div class="form-control">
                    <div class="label">
                        <span class="label-text font-medium">Permissions</span>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2"
                    >
                        <div
                            v-for="(perms, groupName) in groupedPermissions"
                            :key="groupName"
                            class="card bg-base-200/50 border border-base-300"
                        >
                            <div class="card-body p-4">
                                <h4
                                    class="font-bold text-sm uppercase text-base-content/70 mb-3 border-b border-base-300 pb-2"
                                >
                                    {{ groupName }}
                                </h4>
                                <div class="flex flex-col gap-2">
                                    <label
                                        v-for="perm in perms"
                                        :key="perm.id"
                                        class="flex items-center gap-3 cursor-pointer group"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="perm.name"
                                            v-model="form.permissions"
                                            class="checkbox checkbox-primary checkbox-sm"
                                        />
                                        <span
                                            class="text-sm font-mono group-hover:text-primary transition-colors"
                                            >{{
                                                perm.name.replace(
                                                    groupName + ".",
                                                    "",
                                                )
                                            }}</span
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p
                        v-if="!permissions.length"
                        class="text-xs text-base-content/50 italic py-2"
                    >
                        No permissions defined yet.
                    </p>
                </div>
            </div>

            <template #actions>
                <Button @click="closeModal" variant="ghost" type="button"
                    >Cancel</Button
                >
                <Button
                    @click="save"
                    :loading="form.processing"
                    variant="primary"
                    type="button"
                >
                    {{ editingRole ? "Save Changes" : "Create Role" }}
                </Button>
            </template>
        </Modal>

        <!-- ── Delete Confirm Modal ────────────────────────────────────── -->
        <Modal ref="deleteModalRef" maxWidth="sm" title="Delete Role">
            <p class="text-sm text-base-content/70">
                Are you sure you want to delete
                <strong class="text-base-content">{{
                    deletingRole?.name
                }}</strong
                >? This cannot be undone.
            </p>

            <template #actions>
                <Button
                    @click="deleteModalRef?.close()"
                    variant="ghost"
                    type="button"
                    >Cancel</Button
                >
                <Button
                    @click="deleteRole"
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
import { ref, onMounted, computed } from "vue";
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
interface Role {
    id: number;
    name: string;
    permissions: Permission[];
    permissions_count: number;
}

// ── Props ────────────────────────────────────────────────────────────────
const props = defineProps<{
    roles: any; // Paginated response
    permissions: Permission[];
    filters: { search?: string };
}>();

// ── DataTable Columns ────────────────────────────────────────────────────
const columns = [
    { key: "name", label: "Name" },
    { key: "permissions", label: "Permissions" },
    { key: "actions", label: "Actions", class: "text-right w-32" },
];

// ── Group Permissions by Prefix ──────────────────────────────────────────
const groupedPermissions = computed(() => {
    const groups: Record<string, Permission[]> = {};
    props.permissions.forEach((p) => {
        const parts = p.name.split(".");
        const group = parts.length > 1 ? parts[0] : "general";
        if (!groups[group]) groups[group] = [];
        groups[group].push(p);
    });
    return groups;
});

// ── Modal refs ────────────────────────────────────────────────────────────
const modalRef = ref<HTMLDialogElement | null>(null);
const deleteModalRef = ref<HTMLDialogElement | null>(null);
const editingRole = ref<Role | null>(null);
const deletingRole = ref<Role | null>(null);

// ── Forms ─────────────────────────────────────────────────────────────────
const form = useForm({ name: "", permissions: [] as string[] });
const deleteForm = useForm({});

// ── Modal helpers ─────────────────────────────────────────────────────────
function openCreate() {
    editingRole.value = null;
    form.reset();
    form.clearErrors();
    modalRef.value?.showModal();
}

function openEdit(role: Role) {
    editingRole.value = role;
    form.name = role.name;
    form.permissions = role.permissions.map((p) => p.name);
    form.clearErrors();
    modalRef.value?.showModal();
}

function closeModal() {
    modalRef.value?.close();
}

function confirmDelete(role: Role) {
    deletingRole.value = role;
    deleteModalRef.value?.showModal();
}

// ── CRUD via Inertia router ───────────────────────────────────────────────
function save() {
    if (editingRole.value) {
        form.put(route("admin.roles.update", editingRole.value.id), {
            onSuccess: closeModal,
        });
    } else {
        form.post(route("admin.roles.store"), {
            onSuccess: () => {
                form.reset();
                closeModal();
            },
        });
    }
}

function deleteRole() {
    if (!deletingRole.value) return;
    deleteForm.delete(route("admin.roles.destroy", deletingRole.value.id), {
        onSuccess: () => deleteModalRef.value?.close(),
    });
}
</script>
