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

            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text">Company Logo</span>
                </label>
                <div class="flex items-center gap-4">
                    <div v-if="props.company?.logo_url" class="avatar">
                        <div class="w-16 rounded border border-base-300">
                            <img :src="props.company.logo_url" alt="Logo preview" />
                        </div>
                    </div>
                    <input
                        type="file"
                        class="file-input file-input-bordered w-full max-w-xs"
                        @input="form.logo = $event.target.files[0]"
                        accept="image/*"
                    />
                </div>
                <div v-if="form.errors.logo" class="text-error text-sm mt-1">
                    {{ form.errors.logo }}
                </div>
            </div>

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
    logo: null as File | null,
});

// # Submit
const submit = () => {
    if (props.isEdit && props.company) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route("admin.companies.update", props.company.id), {
            forceFormData: true,
        });
    } else {
        form.post(route("admin.companies.store"));
    }
};
</script>
