<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <TextInput
                id="name"
                v-model="form.name"
                label="Name"
                :error="form.errors.name"
                required
            />
            <TextInput
                id="code"
                v-model="form.code"
                label="Code"
                :error="form.errors.code"
                required
            />
        </div>


        <div class="flex items-center gap-3 mt-2">
            <span class="font-semibold text-base-content text-sm">Status:</span>
            <Toggle
                id="is_active"
                v-model="form.is_active"
                toggleClass="toggle-sm"
                :label="form.is_active ? 'Active' : 'Inactive'"
            />
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200">
            <Link :href="route('admin.departments.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Save Department' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import Button from "@/Components/Button.vue";

const props = defineProps<{
    department?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    name: props.department?.name || "",
    code: props.department?.code || "",
    is_active: props.department?.is_active ?? true,
});

// # Submit
const submit = () => {
    if (props.isEdit && props.department) {
        form.put(route("admin.departments.update", props.department.id));
    } else {
        form.post(route("admin.departments.store"));
    }
};
</script>
