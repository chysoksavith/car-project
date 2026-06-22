<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <SelectInput
                label="Maker"
                v-model="form.maker_id"
                :options="makers.map(m => ({ value: m.id, label: m.name }))"
                :error="form.errors.maker_id"
                required
            />

            <TextInput
                label="Model Name"
                v-model="form.name"
                placeholder="e.g. Camry"
                :error="form.errors.name"
                required
            />
        </div>

        <div class="flex items-center gap-3 mt-2">
            <span class="font-semibold text-base-content text-sm">Status:</span>
            <Toggle
                v-model="form.is_active"
                toggleClass="toggle-sm"
                :label="form.is_active ? 'Active' : 'Inactive'"
            />
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200">
            <Link :href="route('admin.car-models.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Create Car Model' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import Button from "@/Components/Button.vue";

const props = defineProps<{
    carModel?: any;
    makers: any[];
    isEdit?: boolean;
}>();

const form = useForm({
    maker_id: props.carModel?.maker_id || '',
    name: props.carModel?.name || '',
    is_active: props.carModel ? Boolean(props.carModel.is_active) : true,
});

// # Submit
const submit = () => {
    if (props.isEdit && props.carModel) {
        form.put(route('admin.car-models.update', props.carModel.id));
    } else {
        form.post(route('admin.car-models.store'));
    }
};
</script>
