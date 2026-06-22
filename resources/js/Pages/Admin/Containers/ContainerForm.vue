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
                v-model="form.ship_id"
                placeholder="e.g. SHIP001"
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
                label="Total Shipping Cost"
                type="number"
                step="0.01"
                v-model="form.total_shipping_cost"
                placeholder="0.00"
                :error="form.errors.total_shipping_cost"
            />
        </div>

        <TextareaInput
            label="Note"
            v-model="form.note"
            placeholder="Additional notes..."
            :error="form.errors.note"
        />

        <div class="divider text-xl font-bold">Cars in Container</div>
        
        <div class="flex flex-col gap-4">
            <div v-for="(car, index) in form.cars" :key="index" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-box shadow-sm">
                <!-- Checkbox to control collapse state -->
                <input type="checkbox" :checked="true" /> 
                
                <div class="collapse-title text-lg font-medium bg-base-200/50 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="badge badge-primary">Car #{{ index + 1 }}</span> 
                        {{ car.name || 'New Car' }}
                    </span>
                </div>
                
                <div class="collapse-content pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput
                            label="Name"
                            v-model="car.name"
                            required
                            :error="form.errors[`cars.${index}.name`]"
                        />
                        
                        <SelectInput
                            label="Make"
                            v-model="car.maker_id"
                            :options="makerOptions"
                            :error="form.errors[`cars.${index}.maker_id`]"
                        />

                        <SelectInput
                            label="Model"
                            v-model="car.car_model_id"
                            :options="getModelOptions(car.maker_id)"
                            :error="form.errors[`cars.${index}.car_model_id`]"
                        />
                        
                        <SelectInput
                            label="Fuel"
                            v-model="car.fuel_id"
                            :options="fuelOptions"
                            :error="form.errors[`cars.${index}.fuel_id`]"
                        />

                        <TextInput
                            label="Year"
                            type="number"
                            v-model="car.year"
                            :error="form.errors[`cars.${index}.year`]"
                        />
                        
                        <SelectInput
                            label="Color"
                            v-model="car.color_id"
                            :options="colorOptions"
                            :error="form.errors[`cars.${index}.color_id`]"
                        />
                        
                        <TextInput
                            label="Body Number"
                            v-model="car.body_number"
                            :error="form.errors[`cars.${index}.body_number`]"
                        />
                        
                        <TextInput
                            label="Engine Capacity (CC)"
                            type="number"
                            v-model="car.engine_capacity_cc"
                            :error="form.errors[`cars.${index}.engine_capacity_cc`]"
                        />
                        
                        <SelectInput
                            label="Registration Type"
                            v-model="car.registration_type"
                            :options="[
                                { value: 'TAX_PAPER', label: 'Tax Paper' },
                                { value: 'PLATE_NUMBER', label: 'Plate Number' },
                            ]"
                            :error="form.errors[`cars.${index}.registration_type`]"
                        />
                        
                        <TextInput
                            label="CIF Price"
                            type="number"
                            step="0.01"
                            v-model="car.cif_price"
                            :error="form.errors[`cars.${index}.cif_price`]"
                        />
                        
                        <TextInput
                            label="Transport Cost"
                            type="number"
                            step="0.01"
                            v-model="car.transport_cost"
                            :error="form.errors[`cars.${index}.transport_cost`]"
                        />
                        
                        <TextInput
                            label="Expected Profit"
                            type="number"
                            step="0.01"
                            v-model="car.expected_profit"
                            :error="form.errors[`cars.${index}.expected_profit`]"
                        />
                    </div>
                    
                    <div class="mt-4">
                        <TextareaInput
                            label="Description"
                            v-model="car.description"
                            :error="form.errors[`cars.${index}.description`]"
                        />
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div class="w-full md:w-1/2">
                            <label class="label"><span class="label-text">Images</span></label>
                            <input 
                                type="file" 
                                multiple 
                                @change="(e) => handleImageUpload(index, e)" 
                                class="file-input file-input-bordered w-full" 
                                accept="image/*"
                            />
                            <div v-if="form.errors[`cars.${index}.images`]" class="text-error text-sm mt-1">
                                {{ form.errors[`cars.${index}.images`] }}
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="removeCar(index)" 
                            class="btn btn-error btn-outline btn-sm self-end"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Remove Car
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <Button type="button" variant="secondary" @click="addCar" class="w-full md:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Add Another Car
            </Button>
        </div>

        <div class="flex items-center justify-end gap-3 pt-6 mt-4 border-t border-base-200">
            <Link :href="route('admin.containers.index')" class="btn btn-ghost">Cancel</Link>
            <Button :loading="form.processing" variant="primary" type="submit">
                {{ isEdit ? 'Save Changes' : 'Create Container' }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import TextareaInput from "@/Components/Form/TextareaInput.vue";
import Button from "@/Components/Button.vue";

const props = withDefaults(defineProps<{
    container?: any;
    suppliers?: any[];
    makers?: any[];
    carModels?: any[];
    fuels?: any[];
    colors?: any[];
    isEdit?: boolean;
}>(), {
    suppliers: () => [],
    makers: () => [],
    carModels: () => [],
    fuels: () => [],
    colors: () => [],
});

const supplierOptions = computed(() => props.suppliers.map(s => ({
    value: s.id,
    label: s.name
})));

const makerOptions = computed(() => [
    { value: "", label: "-- Select Make --" },
    ...props.makers.map((m) => ({ value: m.id, label: m.name })),
]);

const getModelOptions = (makerId: string | number) => {
    const filtered = props.carModels.filter(m => m.maker_id == makerId);
    return [
        { value: "", label: "-- Select Model --" },
        ...filtered.map((m: any) => ({ value: m.id, label: m.name }))
    ];
};

const fuelOptions = computed(() => [
    { value: "", label: "-- Select Fuel --" },
    ...props.fuels.map((f) => ({ value: f.id, label: f.name })),
]);

const colorOptions = computed(() => [
    { value: "", label: "-- Select Color --" },
    ...props.colors.map((c) => ({ value: c.id, label: c.name })),
]);

const statusOptions = [
    { value: 'on_the_way', label: 'On The Way' },
    { value: 'in_stock', label: 'In Stock' },
    { value: 'delivered', label: 'Delivered' },
];

const today = new Date().toISOString().split('T')[0];

const defaultCar = {
    name: '',
    maker_id: '',
    car_model_id: '',
    fuel_id: '',
    description: '',
    year: '',
    color_id: '',
    body_number: '',
    engine_capacity_cc: '',
    registration_type: 'TAX_PAPER',
    cif_price: '',
    transport_cost: '',
    expected_profit: '',
    images: [] as File[],
};

const form = useForm({
    supplier_id: props.container?.supplier_id || '',
    ship_id: props.container?.ship_id || '',
    bl_number: props.container?.bl_number || '',
    container_number: props.container?.container_number || '',
    container_type: props.container?.container_type || '',
    status: props.container?.status || 'on_the_way',
    departure_date: props.container?.departure_date || today,
    expected_date: props.container?.expected_date || today,
    note: props.container?.note || '',
    total_shipping_cost: props.container?.total_shipping_cost || '',
    cars: (props.container?.cars && props.container.cars.length > 0) 
        ? props.container.cars 
        : [{ ...defaultCar }],
});

const addCar = () => {
    form.cars.push({ ...defaultCar });
};

const removeCar = (index: number) => {
    form.cars.splice(index, 1);
};

const handleImageUpload = (index: number, event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        form.cars[index].images = Array.from(target.files);
    }
};

// # Submit
const submit = () => {
    if (props.isEdit && props.container) {
        form.transform((data) => ({
            ...data,
            _method: "put",
        })).post(route('admin.containers.update', props.container.id));
    } else {
        form.post(route('admin.containers.store'));
    }
};
</script>
