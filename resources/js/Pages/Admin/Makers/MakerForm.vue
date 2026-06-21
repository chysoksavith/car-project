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
            <Link :href="route('admin.makers.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Save Maker' }}
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
    maker?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    name: props.maker?.name || "",
    is_active: props.maker?.is_active ?? true,
});

const submit = () => {
    if (props.isEdit && props.maker) {
        form.put(route("admin.makers.update", props.maker.id));
    } else {
        form.post(route("admin.makers.store"));
    }
};
</script>
