<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <TextInput
                label="Supplier Name"
                v-model="form.name"
                placeholder="e.g. ACME Corp"
                :error="form.errors.name"
                required
            />

            <TextInput
                label="Contact Person"
                v-model="form.contact_person"
                placeholder="e.g. John Doe"
                :error="form.errors.contact_person"
            />
            
            <TextInput
                label="Phone Number"
                v-model="form.phone"
                placeholder="e.g. +1 234 567 890"
                :error="form.errors.phone"
            />
            
            <TextInput
                label="Email Address"
                v-model="form.email"
                type="email"
                placeholder="e.g. john@example.com"
                :error="form.errors.email"
            />
        </div>

        <div class="form-control w-full">
            <label class="label">
                <span class="label-text font-medium text-base-content">Address</span>
            </label>
            <textarea
                v-model="form.address"
                class="textarea textarea-bordered w-full h-24"
                placeholder="Full address..."
                :class="{ 'textarea-error': form.errors.address }"
            ></textarea>
            <label class="label" v-if="form.errors.address">
                <span class="label-text-alt text-error">{{ form.errors.address }}</span>
            </label>
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
            <Link :href="route('admin.suppliers.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Create Supplier' }}
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
    supplier?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    name: props.supplier?.name || '',
    contact_person: props.supplier?.contact_person || '',
    phone: props.supplier?.phone || '',
    email: props.supplier?.email || '',
    address: props.supplier?.address || '',
    is_active: props.supplier ? Boolean(props.supplier.is_active) : true,
});

const submit = () => {
    if (props.isEdit && props.supplier) {
        form.put(route('admin.suppliers.update', props.supplier.id));
    } else {
        form.post(route('admin.suppliers.store'));
    }
};
</script>
