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
                id="email"
                type="email"
                v-model="form.email"
                label="Email"
                :error="form.errors.email"
            />

            <TextInput
                id="phone"
                v-model="form.phone"
                label="Phone"
                :error="form.errors.phone"
            />

            <div class="md:col-span-2">
                <TextareaInput
                    id="address"
                    v-model="form.address"
                    label="Address"
                    placeholder="Enter address..."
                    :error="form.errors.address"
                />
            </div>
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
            <Link :href="route('admin.companies.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Save Company' }}
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
    company?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    name: props.company?.name || "",
    email: props.company?.email || "",
    phone: props.company?.phone || "",
    address: props.company?.address || "",
    is_active: props.company?.is_active ?? true,
});

const submit = () => {
    if (props.isEdit && props.company) {
        form.put(route("admin.companies.update", props.company.id));
    } else {
        form.post(route("admin.companies.store"));
    }
};
</script>
