import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import type { Permission } from "@/Types/permission";

export function usePermission() {
    const modalRef = ref<HTMLDialogElement | null>(null);
    const deleteModalRef = ref<HTMLDialogElement | null>(null);
    const editingPerm = ref<Permission | null>(null);
    const deletingPerm = ref<Permission | null>(null);

    const form = useForm({ name: "" });
    const deleteForm = useForm({});

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

    return {
        modalRef,
        deleteModalRef,
        editingPerm,
        deletingPerm,
        form,
        deleteForm,
        openCreate,
        openEdit,
        confirmDelete,
        save,
        deletePerm,
    };
}
