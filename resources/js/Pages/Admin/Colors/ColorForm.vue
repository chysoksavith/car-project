<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <TextInput
                label="Color Name"
                v-model="form.name"
                placeholder="e.g. Pearl White"
                :error="form.errors.name"
                required
            />

            <!-- Hex code is just a text input but we can show a small color preview -->
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text font-medium">Hex Code (Optional)</span>
                </label>
                <div class="flex gap-3">
                    <input
                        type="color"
                        v-model="form.hex_code"
                        class="w-12 h-12 rounded cursor-pointer border border-base-300"
                    />
                    <input
                        type="text"
                        v-model="form.hex_code"
                        placeholder="#FFFFFF"
                        class="input input-bordered w-full font-mono uppercase"
                    />
                </div>
                <label class="label" v-if="form.errors.hex_code">
                    <span class="label-text-alt text-error">{{ form.errors.hex_code }}</span>
                </label>
            </div>
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
            <Link :href="route('admin.colors.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Create Color' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import Button from "@/Components/Button.vue";
import { watch } from "vue";

const props = defineProps<{
    color?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    name: props.color?.name || '',
    hex_code: props.color?.hex_code || '#ffffff',
    is_active: props.color ? Boolean(props.color.is_active) : true,
});

// Ensure hex_code starts with # for the color picker
watch(() => form.hex_code, (newVal) => {
    if (newVal && !newVal.startsWith('#')) {
        form.hex_code = '#' + newVal;
    }
});

const submit = () => {
    if (props.isEdit && props.color) {
        form.put(route('admin.colors.update', props.color.id));
    } else {
        form.post(route('admin.colors.store'));
    }
};
</script>
