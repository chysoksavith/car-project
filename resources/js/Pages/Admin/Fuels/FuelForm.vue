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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <TextInput
                id="sort_order"
                type="number"
                v-model="form.sort_order"
                label="Sort Order"
                :error="form.errors.sort_order"
            />
        </div>

        <div class="mt-6">
            <TextareaInput
                id="description"
                v-model="form.description"
                label="Description"
                :error="form.errors.description"
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
            <Link :href="route('admin.fuels.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Save Fuel' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import TextareaInput from "@/Components/Form/TextareaInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import Button from "@/Components/Button.vue";

const props = defineProps<{
    fuel?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    name: props.fuel?.name || "",
    code: props.fuel?.code || "",
    description: props.fuel?.description || "",
    sort_order: props.fuel?.sort_order ?? 0,
    is_active: props.fuel?.is_active ?? true,
});

// # Submit
const submit = () => {
    if (props.isEdit && props.fuel) {
        form.put(route("admin.fuels.update", props.fuel.id));
    } else {
        form.post(route("admin.fuels.store"));
    }
};
</script>
