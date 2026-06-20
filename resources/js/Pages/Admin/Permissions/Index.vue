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

            <!-- Search -->
            <div class="relative w-full max-w-xs">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-base-content/40"
                >
                    <i class="fa-solid fa-search"></i>
                </div>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Filter permissions..."
                    class="input input-sm input-bordered w-full pl-9 rounded-full"
                />
            </div>

            <!-- Permissions Grouped Grid -->
            <div
                v-if="Object.keys(grouped).length === 0"
                class="text-center py-16 text-base-content/50 text-sm"
            >
                No permissions found.
            </div>

            <div v-else class="flex flex-col gap-8">
                <div
                    v-for="(perms, groupName) in grouped"
                    :key="groupName"
                    class="flex flex-col gap-3"
                >
                    <!-- Group Header -->
                    <div
                        class="flex items-center gap-3 border-b border-base-200 pb-2"
                    >
                        <div
                            class="w-8 h-8 rounded-lg bg-base-200 flex items-center justify-center text-base-content/70"
                        >
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h2
                            class="text-lg font-bold capitalize tracking-wide text-base-content/80"
                        >
                            {{ groupName }}
                        </h2>
                        <span class="badge badge-sm badge-ghost">{{
                            perms.length
                        }}</span>
                    </div>

                    <!-- Group Grid -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3"
                    >
                        <div
                            v-for="perm in perms"
                            :key="perm.id"
                            class="card bg-base-100 border border-base-200 shadow-sm hover:border-primary/30 hover:shadow-md transition-all group"
                        >
                            <div
                                class="card-body p-3 flex-row items-center justify-between gap-2"
                            >
                                <div class="flex items-center gap-2 min-w-0">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-primary/40 group-hover:bg-primary transition-colors shrink-0"
                                    ></span>
                                    <span
                                        class="text-sm font-mono font-medium text-base-content truncate"
                                        >{{
                                            perm.name.replace(
                                                groupName + ".",
                                                "",
                                            )
                                        }}</span
                                    >
                                    <span
                                        class="text-xs text-base-content/40 hidden md:inline-block ml-1"
                                        v-if="perm.name.includes('.')"
                                        >(.{{ perm.name.split(".")[1] }})</span
                                    >
                                </div>
                                <div
                                    class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity shrink-0"
                                >
                                    <TableActionButtons
                                        @edit="openEdit(perm)"
                                        @delete="confirmDelete(perm)"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
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
    permissions: Permission[];
}>();

// ── Local state ───────────────────────────────────────────────────────────
const search = ref("");
const modalRef = ref<HTMLDialogElement | null>(null);
const deleteModalRef = ref<HTMLDialogElement | null>(null);
const editingPerm = ref<Permission | null>(null);
const deletingPerm = ref<Permission | null>(null);

const filtered = computed(() =>
    props.permissions.filter((p) =>
        p.name.toLowerCase().includes(search.value.toLowerCase()),
    ),
);

const grouped = computed(() => {
    const groups: Record<string, Permission[]> = {};
    filtered.value.forEach((p) => {
        const parts = p.name.split(".");
        const group = parts.length > 1 ? parts[0] : "general";
        if (!groups[group]) groups[group] = [];
        groups[group].push(p);
    });
    return groups;
});

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
