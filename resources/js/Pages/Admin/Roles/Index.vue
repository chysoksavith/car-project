<template>
    <DashboardLayout>
        <div class="flex flex-col gap-6">
            <!-- Page Header -->
            <PageHeader
                title="Roles"
                description="Manage roles and their permissions."
            >
                <Button
                    v-if="can('roles.create')"
                    @click="openCreate"
                    variant="primary"
                    size="sm"
                    icon="fa-solid fa-plus"
                >
                    New Role
                </Button>
            </PageHeader>

            <!-- DataTable -->
            <DataTable
                :data="roles"
                :columns="columns"
                searchRoute="/admin/roles"
                :searchQuery="filters.search"
                searchPlaceholder="Search roles..."
            >
                <template #cell(name)="{ item }">
                    <span class="font-semibold text-sm text-base-content">{{
                        item.name
                    }}</span>
                </template>

                <template #cell(permissions)="{ item }">
                    <div class="flex flex-wrap gap-1">
                        <Badge
                            v-for="perm in item.permissions"
                            :key="perm.id"
                            variant="ghost"
                            class="font-mono"
                            >{{ perm.name }}</Badge
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
                        :hasEdit="can('roles.edit')"
                        :hasDelete="can('roles.delete')"
                        @edit="openEdit(item)"
                        @delete="confirmDelete(item)"
                    />
                </template>
            </DataTable>
        </div>

        <!-- # Create / Edit Modal -->
        <Modal
            ref="modalRef"
            maxWidth="4xl"
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
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-base-content">Permissions</span>
                        <label class="flex items-center gap-2 cursor-pointer hover:opacity-80">
                            <input
                                type="checkbox"
                                v-model="isAllSelected"
                                class="checkbox checkbox-primary checkbox-sm"
                            />
                            <span class="text-sm font-medium">Select All Permissions</span>
                        </label>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[60vh] overflow-y-auto pr-2 mt-2"
                    >
                        <div
                            v-for="(perms, groupName) in groupedPermissions"
                            :key="groupName"
                            class="card bg-base-100 shadow-sm border border-base-200 transition-all hover:border-primary/30"
                        >
                            <div class="card-body p-4 gap-0">
                                <div class="flex items-center justify-between mb-3 border-b border-base-200 pb-2">
                                    <h4 class="font-bold text-sm uppercase text-base-content/80">
                                        {{ groupName }}
                                    </h4>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <span class="text-xs text-base-content/60 group-hover:text-base-content transition-colors">All</span>
                                        <input
                                            type="checkbox"
                                            :checked="isGroupSelected(groupName as string)"
                                            @change="toggleGroup(groupName as string, $event)"
                                            class="checkbox checkbox-sm checkbox-primary rounded"
                                        />
                                    </label>
                                </div>
                                <div class="flex flex-col gap-2.5 mt-1">
                                    <label
                                        v-for="perm in perms"
                                        :key="perm.id"
                                        class="flex items-center gap-3 cursor-pointer group"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="perm.name"
                                            v-model="form.permissions"
                                            class="checkbox checkbox-primary checkbox-sm rounded-md"
                                        />
                                        <span
                                            class="text-sm font-mono text-base-content/80 group-hover:text-primary transition-colors"
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

        <!-- # Delete Confirm Modal -->
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
import { computed } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DataTable from "@/Components/DataTable.vue";
import TableActionButtons from "@/Components/TableActionButtons.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Button from "@/Components/Button.vue";
import Badge from "@/Components/Badge.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import type { Permission } from "@/Types/permission";
import { useRole } from "@/Composables/useRole";

// # Props
const props = defineProps<{
    roles: any; // Paginated response
    permissions: Permission[];
    filters: { search?: string };
}>();

// # DataTable Columns
const columns = [
    { key: "no", label: "No.", class: "w-16" },
    { key: "name", label: "Name" },
    { key: "permissions", label: "Permissions" },
    { key: "actions", label: "Actions", class: "text-right w-32" },
];

// # Group Permissions by Prefix
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

// # Composables
const {
    modalRef,
    deleteModalRef,
    editingRole,
    deletingRole,
    form,
    deleteForm,
    openCreate,
    openEdit,
    closeModal,
    confirmDelete,
    save,
    deleteRole,
} = useRole();

// # Select All Logic
const allPermissionNames = computed(() => props.permissions.map(p => p.name));

const isAllSelected = computed({
    get: () => form.permissions.length === allPermissionNames.value.length && allPermissionNames.value.length > 0,
    set: (val: boolean) => {
        if (val) {
            form.permissions = [...allPermissionNames.value];
        } else {
            form.permissions = [];
        }
    }
});

function isGroupSelected(groupName: string) {
    const groupPerms = groupedPermissions.value[groupName] || [];
    if (!groupPerms.length) return false;
    return groupPerms.every(p => form.permissions.includes(p.name));
}

function toggleGroup(groupName: string, event: Event) {
    const checked = (event.target as HTMLInputElement).checked;
    const groupPerms = groupedPermissions.value[groupName] || [];
    const groupPermNames = groupPerms.map(p => p.name);
    
    if (checked) {
        const newPerms = new Set([...form.permissions, ...groupPermNames]);
        form.permissions = Array.from(newPerms);
    } else {
        form.permissions = form.permissions.filter(p => !groupPermNames.includes(p));
    }
}
</script>
