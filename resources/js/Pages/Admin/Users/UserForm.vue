<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <TextInput
                label="First Name"
                v-model="form.first_name"
                placeholder="e.g. John"
                :error="form.errors.first_name"
                required
            />

            <TextInput
                label="Last Name"
                v-model="form.last_name"
                placeholder="e.g. Doe"
                :error="form.errors.last_name"
                required
            />

            <TextInput
                label="Email"
                type="email"
                v-model="form.email"
                placeholder="john@example.com"
                :error="form.errors.email"
                required
            />

            <TextInput
                label="Phone Number"
                v-model="form.phone_number"
                placeholder="+855 12 345 678"
                :error="form.errors.phone_number"
            />

            <TextInput
                label="Birth Date"
                type="date"
                v-model="form.birth_date"
                :error="form.errors.birth_date"
            />

            <SelectInput
                label="User Type"
                v-model="form.user_type"
                :options="[{value: 'backend', label: 'Backend'}, {value: 'frontend', label: 'Frontend'}]"
                :error="form.errors.user_type"
                required
            />

            <TextInput
                label="Password"
                type="password"
                v-model="form.password"
                :placeholder="isEdit ? 'Leave blank to keep current password' : 'Enter password'"
                :error="form.errors.password"
                :required="!isEdit"
            />

            <TextInput
                label="Confirm Password"
                type="password"
                v-model="form.password_confirmation"
                placeholder="Confirm password"
                :error="form.errors.password_confirmation"
                :required="!isEdit"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company -->
            <SelectInput
                label="Assign Company"
                v-model="form.company_id"
                :options="companies.map(c => ({ value: c.id, label: c.name }))"
                :error="form.errors.company_id"
                placeholder="Select a company..."
            />

            <!-- Department -->
            <SelectInput
                label="Assign Department"
                v-model="form.department_id"
                :options="departments?.map(d => ({ value: d.id, label: d.name })) || []"
                :error="form.errors.department_id"
                placeholder="Select a department..."
            />

            <!-- Role (Backend Only) -->
            <SelectInput
                v-if="form.user_type === 'backend'"
                label="Assign Role"
                v-model="form.role"
                :options="roles.map(r => ({ value: r.name, label: r.name }))"
                :error="form.errors.roles"
                placeholder="Select a role..."
            />
        </div>

        <!-- Status -->
        <div class="flex items-center gap-3 mt-2">
            <span class="font-semibold text-base-content text-sm">Status:</span>
            <Toggle
                v-model="form.is_active"
                toggleClass="toggle-sm"
                :label="form.is_active ? 'Active' : 'Inactive'"
            />
        </div>

        <!-- Address Section -->
        <div class="flex flex-col gap-6 border-t border-base-200 pt-6 mt-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-base-content">Address Information</h3>
                    <p class="text-sm text-base-content/60">Provide user's location.</p>
                </div>
                <div class="flex items-center bg-base-200/50 p-1 rounded-xl">
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-lg transition-colors" :class="{'bg-base-100 shadow-sm text-primary font-medium': form.address_type === 'select'}">
                        <input type="radio" value="select" v-model="form.address_type" class="hidden" />
                        <span class="text-sm">Select Location</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-lg transition-colors" :class="{'bg-base-100 shadow-sm text-primary font-medium': form.address_type === 'handwrite'}">
                        <input type="radio" value="handwrite" v-model="form.address_type" class="hidden" />
                        <span class="text-sm">Hand Write</span>
                    </label>
                </div>
            </div>

            <!-- Hand Write -->
            <div v-if="form.address_type === 'handwrite'" class="grid grid-cols-1 gap-6">
                <TextInput
                    label="Full Address Details"
                    v-model="form.address.address"
                    placeholder="Enter manual complete address..."
                    :error="form.errors['address.address']"
                />
            </div>

            <!-- Select -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <SelectInput
                    label="Province"
                    v-model="form.address.province"
                    :options="provinces.map(p => ({ value: p.code, label: p.name_en + ' - ' + p.name_km }))"
                    :error="form.errors['address.province']"
                    placeholder="Select Province"
                />
                <SelectInput
                    label="District"
                    v-model="form.address.district"
                    :options="districts.map(d => ({ value: d.code, label: d.name_en + ' - ' + d.name_km }))"
                    :error="form.errors['address.district']"
                    placeholder="Select District"
                    :disabled="!form.address.province"
                />
                <SelectInput
                    label="Commune"
                    v-model="form.address.commune"
                    :options="communes.map(c => ({ value: c.code, label: c.name_en + ' - ' + c.name_km }))"
                    :error="form.errors['address.commune']"
                    placeholder="Select Commune"
                    :disabled="!form.address.district"
                />
                <SelectInput
                    label="Village"
                    v-model="form.address.village"
                    :options="villages.map(v => ({ value: v.code, label: v.name_en + ' - ' + v.name_km }))"
                    :error="form.errors['address.village']"
                    placeholder="Select Village"
                    :disabled="!form.address.commune"
                />
                <TextInput
                    label="House No."
                    v-model="form.address.house_no"
                    placeholder="e.g. 12A"
                    :error="form.errors['address.house_no']"
                />
                <TextInput
                    label="Street / Road No."
                    v-model="form.address.road_no"
                    placeholder="e.g. St. 123"
                    :error="form.errors['address.road_no']"
                />
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200">
            <Link :href="route('admin.users.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Create User' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { onMounted } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import Button from "@/Components/Button.vue";
import { useLocations } from "@/Composables/useLocations";

const props = defineProps<{
    user?: any;
    roles: any[];
    companies: any[];
    departments?: any[];
    isEdit?: boolean;
}>();

const addressData = props.user?.addresses?.length ? props.user.addresses[0] : null;

const form = useForm({
    first_name: props.user?.first_name || '',
    last_name: props.user?.last_name || '',
    email: props.user?.email || '',
    phone_number: props.user?.phone_number || '',
    birth_date: props.user?.birth_date ? new Date(props.user.birth_date).toISOString().split('T')[0] : '',
    password: '',
    password_confirmation: '',
    user_type: props.user?.user_type || 'backend',
    is_active: props.user ? Boolean(props.user.is_active) : true,
    company_id: props.user?.company_id || '',
    department_id: props.user?.department_id || '',
    role: props.user?.roles?.length ? props.user.roles[0].name : '',
    address_type: addressData && !addressData.province ? 'handwrite' : 'select',
    address: {
        province: addressData?.province || '',
        district: addressData?.district || '',
        commune: addressData?.commune || '',
        village: addressData?.village || '',
        house_no: addressData?.house_no || '',
        road_no: addressData?.road_no || '',
        address: addressData?.address || '',
    }
});

// # Composable handles all refs, watchers, and caching
const { provinces, districts, communes, villages, loading, init } = useLocations(form.address);

onMounted(init);

// # Submit
const submit = () => {
    form.transform((data) => {
        const addressPayload = { ...data.address };
        if (data.address_type === 'handwrite') {
            addressPayload.province = null;
            addressPayload.district = null;
            addressPayload.commune  = null;
            addressPayload.village  = null;
            addressPayload.house_no = null;
            addressPayload.road_no  = null;
        } else {
            addressPayload.address = null;
        }
        return {
            ...data,
            roles: data.role ? [data.role] : [],
            address: addressPayload,
        };
    });

    if (props.isEdit && props.user) {
        form.put(route('admin.users.update', props.user.id));
    } else {
        form.post(route('admin.users.store'));
    }
};
</script>

