import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import type { Role } from "@/Types/role";

export function useRole() {
    const modalRef = ref<HTMLDialogElement | null>(null);
    const deleteModalRef = ref<HTMLDialogElement | null>(null);
    const editingRole = ref<Role | null>(null);
    const deletingRole = ref<Role | null>(null);

    const form = useForm({ name: "", permissions: [] as string[] });
    const deleteForm = useForm({});

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

    return {
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
    };
}
