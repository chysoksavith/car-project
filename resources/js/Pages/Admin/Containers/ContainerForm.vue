<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <SelectInput
                label="Supplier"
                v-model="form.supplier_id"
                :options="supplierOptions"
                placeholder="Select a supplier..."
                :error="form.errors.supplier_id"
                required
            />

            <TextInput
                label="Ship ID"
                type="number"
                v-model="form.ship_id"
                placeholder="e.g. 1"
                :error="form.errors.ship_id"
            />

            <TextInput
                label="B/L Number"
                v-model="form.bl_number"
                placeholder="e.g. BL123456789"
                :error="form.errors.bl_number"
                required
            />

            <TextInput
                label="Container Number"
                v-model="form.container_number"
                placeholder="e.g. ABCD1234567"
                :error="form.errors.container_number"
                required
            />
            
            <TextInput
                label="Container Type"
                v-model="form.container_type"
                placeholder="e.g. 40ft HC"
                :error="form.errors.container_type"
            />
            
            <SelectInput
                label="Status"
                v-model="form.status"
                :options="statusOptions"
                :error="form.errors.status"
                required
            />

            <TextInput
                label="Departure Date"
                type="date"
                v-model="form.departure_date"
                :error="form.errors.departure_date"
            />

            <TextInput
                label="Expected Date"
                type="date"
                v-model="form.expected_date"
                :error="form.errors.expected_date"
            />

            <TextInput
                label="Video Review Arrival"
                v-model="form.video_review_arrival"
                placeholder="Link to video..."
                :error="form.errors.video_review_arrival"
            />

            <TextInput
                label="Total Shipping Cost"
                type="number"
                step="0.01"
                v-model="form.total_shipping_cost"
                placeholder="0.00"
                :error="form.errors.total_shipping_cost"
            />

            <SelectInput
                label="Cost Allocation Method"
                v-model="form.cost_allocation_method"
                :options="allocationOptions"
                :error="form.errors.cost_allocation_method"
            />
        </div>

        <TextareaInput
            label="Note"
            v-model="form.note"
            placeholder="Additional notes..."
            :error="form.errors.note"
        />

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200">
            <Link :href="route('admin.containers.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Create Container' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import TextareaInput from "@/Components/Form/TextareaInput.vue";
import Button from "@/Components/Button.vue";

const props = withDefaults(defineProps<{
    container?: any;
    suppliers?: any[];
    isEdit?: boolean;
}>(), {
    suppliers: () => []
});

import { computed } from "vue";

const supplierOptions = computed(() => props.suppliers.map(s => ({
    value: s.id,
    label: s.name
})));

const statusOptions = [
    { value: 'on_the_way', label: 'On The Way' },
    { value: 'in_stock', label: 'In Stock' },
    { value: 'delivered', label: 'Delivered' },
];

const allocationOptions = [
    { value: 'equal_split', label: 'Equal Split' },
    { value: 'by_volume', label: 'By Volume' },
    { value: 'by_weight', label: 'By Weight' },
];

const today = new Date().toISOString().split('T')[0];

const form = useForm({
    supplier_id: props.container?.supplier_id || '',
    ship_id: props.container?.ship_id || '',
    bl_number: props.container?.bl_number || '',
    container_number: props.container?.container_number || '',
    container_type: props.container?.container_type || '',
    status: props.container?.status || 'on_the_way',
    departure_date: props.container?.departure_date || today,
    expected_date: props.container?.expected_date || today,
    video_review_arrival: props.container?.video_review_arrival || '',
    note: props.container?.note || '',
    total_shipping_cost: props.container?.total_shipping_cost || '',
    cost_allocation_method: props.container?.cost_allocation_method || 'equal_split',
});

// # Submit
const submit = () => {
    if (props.isEdit && props.container) {
        form.put(route('admin.containers.update', props.container.id));
    } else {
        form.post(route('admin.containers.store'));
    }
};
</script>
