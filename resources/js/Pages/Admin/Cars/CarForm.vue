<template>
    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <ul class="steps w-full mb-8">
            <li
                v-for="(step, index) in stepsList"
                :key="step.id"
                class="step cursor-pointer"
                :class="{ 'step-primary': index <= activeStepIndex }"
                @click="activeTab = step.id"
            >
                {{ step.label }}
            </li>
        </ul>

        <div v-show="activeTab === 'basic'">
            <h3
                class="font-bold text-lg mb-4 text-base-content/80 border-l-4 border-primary pl-3"
            >
                Basic Info
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <TextInput
                    id="name"
                    v-model="form.name"
                    label="Name"
                    :error="form.errors.name"
                    required
                />
                <SelectInput
                    id="maker_id"
                    v-model="form.maker_id"
                    label="Make"
                    :options="makerOptions"
                    :error="form.errors.maker_id"
                />
                <SelectInput
                    id="car_model_id"
                    v-model="form.car_model_id"
                    label="Model"
                    :options="modelOptions"
                    :error="form.errors.car_model_id"
                />
                <SelectInput
                    id="container_id"
                    v-model="form.container_id"
                    label="Container"
                    :options="containerOptions"
                    :error="form.errors.container_id"
                />
                <SelectInput
                    id="fuel_id"
                    v-model="form.fuel_id"
                    label="Fuel"
                    :options="fuelOptions"
                    :error="form.errors.fuel_id"
                />
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                <TextInput
                    id="description"
                    v-model="form.description"
                    label="Description"
                    :error="form.errors.description"
                />
                <TextInput
                    id="options"
                    v-model="form.options"
                    label="Options"
                    :error="form.errors.options"
                />
            </div>

            <div class="mt-6">
                <h4 class="font-bold text-sm mb-2 text-base-content/80">
                    Car Images
                </h4>
                <ImageUploader
                    v-model="form.images"
                    v-model:deleted-images="form.deleted_images"
                    v-model:existing-image-order="form.existing_image_order"
                    :existing-images="props.car?.images"
                    :error="carImageError"
                    :label="undefined"
                />
            </div>

            <div class="divider my-6"></div>

            <h3
                class="font-bold text-lg mb-4 text-base-content/80 border-l-4 border-primary pl-3"
            >
                Technical Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <TextInput
                    id="year"
                    type="number"
                    v-model="form.year"
                    label="Year"
                    :error="form.errors.year"
                />
                <SelectInput
                    id="color_id"
                    v-model="form.color_id"
                    label="Color"
                    :options="colorOptions"
                    :error="form.errors.color_id"
                />
                <SelectInput
                    id="condition"
                    v-model="form.condition"
                    label="Condition"
                    :options="[
                        { value: 'NEW', label: 'NEW' },
                        { value: 'USED', label: 'USED' },
                    ]"
                    :error="form.errors.condition"
                    required
                />
                <TextInput
                    id="transmission"
                    v-model="form.transmission"
                    label="Transmission"
                    :error="form.errors.transmission"
                />
                <TextInput
                    id="body_number"
                    v-model="form.body_number"
                    label="Body Number"
                    :error="form.errors.body_number"
                    required
                />
                <TextInput
                    id="engine_number"
                    v-model="form.engine_number"
                    label="Engine Number"
                    :error="form.errors.engine_number"
                />
                <TextInput
                    id="engine_capacity_cc"
                    type="number"
                    v-model="form.engine_capacity_cc"
                    label="Engine Capacity (CC)"
                    :error="form.errors.engine_capacity_cc"
                />

                <SelectInput
                    id="registration_type"
                    v-model="form.registration_type"
                    label="Registration Type"
                    :options="[
                        { value: 'TAX_PAPER', label: 'Tax Paper' },
                        { value: 'PLATE_NUMBER', label: 'Plate Number' },
                    ]"
                    :error="form.errors.registration_type"
                    required
                />
                <TextInput
                    id="plate_number"
                    v-model="form.plate_number"
                    label="Plate Number"
                    :error="form.errors.plate_number"
                />
                <TextInput
                    id="certificate_number"
                    v-model="form.certificate_number"
                    label="Certificate Number"
                    :error="form.errors.certificate_number"
                />
            </div>
        </div>

        <div v-show="activeTab === 'pricing'">
            <h3
                class="font-bold text-lg mb-4 text-base-content/80 border-l-4 border-primary pl-3"
            >
                Pricing
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <TextInput
                    id="purchase_price"
                    type="number"
                    step="0.01"
                    v-model="form.purchase_price"
                    label="Purchase Price"
                    :error="form.errors.purchase_price"
                />
                <TextInput
                    id="cif_price"
                    type="number"
                    step="0.01"
                    v-model="form.cif_price"
                    label="CIF Price"
                    :error="form.errors.cif_price"
                />
                <TextInput
                    id="transport_cost"
                    type="number"
                    step="0.01"
                    v-model="form.transport_cost"
                    label="Transport Cost"
                    :error="form.errors.transport_cost"
                />
                <TextInput
                    id="expected_profit"
                    type="number"
                    step="0.01"
                    v-model="form.expected_profit"
                    label="Expected Profit"
                    :error="form.errors.expected_profit"
                />
                <TextInput
                    id="selling_price"
                    type="number"
                    step="0.01"
                    v-model="form.selling_price"
                    label="Selling Price"
                    :error="form.errors.selling_price"
                />
                <TextInput
                    id="discounted_price"
                    type="number"
                    step="0.01"
                    v-model="form.discounted_price"
                    label="Discounted Price"
                    :error="form.errors.discounted_price"
                />
            </div>

            <div class="divider my-6"></div>

            <h3
                class="font-bold text-lg mb-4 text-base-content/80 border-l-4 border-primary pl-3"
            >
                Inventory & Status
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <TextInput
                    id="quantity"
                    type="number"
                    v-model="form.quantity"
                    label="Quantity"
                    :error="form.errors.quantity"
                />
                <SelectInput
                    id="inventory_status"
                    v-model="form.inventory_status"
                    label="Inventory Status"
                    :options="[
                        { value: 'on_the_way', label: 'On The Way' },
                        { value: 'in_stock', label: 'In Stock' },
                    ]"
                    :error="form.errors.inventory_status"
                    required
                />
                <SelectInput
                    id="sales_status"
                    v-model="form.sales_status"
                    label="Sales Status"
                    :options="[
                        { value: 'AVAILABLE', label: 'AVAILABLE' },
                        { value: 'BOOKED', label: 'BOOKED' },
                        { value: 'RESERVED', label: 'RESERVED' },
                        { value: 'SOLD', label: 'SOLD' },
                    ]"
                    :error="form.errors.sales_status"
                    required
                />
            </div>

            <div class="flex items-center gap-6 mt-6">
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-base-content text-sm"
                        >Active:</span
                    >
                    <Toggle
                        id="is_active"
                        v-model="form.is_active"
                        toggleClass="toggle-sm"
                        :label="form.is_active ? 'Yes' : 'No'"
                    />
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-base-content text-sm"
                        >Published:</span
                    >
                    <Toggle
                        id="is_published"
                        v-model="form.is_published"
                        toggleClass="toggle-sm"
                        :label="form.is_published ? 'Yes' : 'No'"
                    />
                </div>
            </div>
        </div>

        <div
            class="flex items-center justify-between pt-4 border-t border-base-200 mt-8"
        >
            <div>
                <Button
                    v-if="activeStepIndex > 0"
                    @click="prevStep"
                    variant="ghost"
                    type="button"
                    >Previous</Button
                >
            </div>
            <div class="flex items-center gap-3">
                <Link :href="route('admin.cars.index')" class="btn btn-ghost"
                    >Cancel</Link
                >
                <Button
                    v-if="activeStepIndex < stepsList.length - 1"
                    @click="nextStep"
                    variant="secondary"
                    type="button"
                    >Next Step</Button
                >
                <Button
                    v-else
                    :loading="form.processing"
                    variant="primary"
                    type="submit"
                >
                    {{ isEdit ? "Save Changes" : "Save Car" }}
                </Button>
            </div>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import ImageUploader from "@/Components/Form/ImageUploader.vue";
import Button from "@/Components/Button.vue";
import { useDependentDropdown } from "@/Composables/useDependentDropdown";

const props = defineProps<{
    car?: any;
    makers: any[];
    carModels: any[];
    fuels: any[];
    colors: any[];
    containers: any[];
    isEdit?: boolean;
}>();

const activeTab = ref("basic");

const stepsList = [
    { id: "basic", label: "Vehicle Information" },
    { id: "pricing", label: "Pricing & Status" },
];

const activeStepIndex = computed(() =>
    stepsList.findIndex((s) => s.id === activeTab.value),
);

const nextStep = () => {
    if (activeStepIndex.value < stepsList.length - 1) {
        activeTab.value = stepsList[activeStepIndex.value + 1].id;
    }
};

const prevStep = () => {
    if (activeStepIndex.value > 0) {
        activeTab.value = stepsList[activeStepIndex.value - 1].id;
    }
};

const makerOptions = computed(() => [
    { value: "", label: "-- Select Make --" },
    ...props.makers.map((m) => ({ value: m.id, label: m.name })),
]);

// Wait to define useForm first to pass parentValue, so let's define form first and move this logic down


const fuelOptions = computed(() => [
    { value: "", label: "-- Select Fuel --" },
    ...props.fuels.map((f) => ({ value: f.id, label: f.name })),
]);

const colorOptions = computed(() => [
    { value: "", label: "-- Select Color --" },
    ...props.colors.map((c) => ({ value: c.id, label: c.name })),
]);

const containerOptions = computed(() => [
    { value: "", label: "-- Select Container --" },
    ...props.containers.map((c) => ({
        value: c.id,
        label: c.container_number,
    })),
]);

const form = useForm({
    name: props.car?.name || "",
    description: props.car?.description || "",
    options: props.car?.options || "",
    maker_id: props.car?.maker_id || "",
    car_model_id: props.car?.car_model_id || "",
    container_id: props.car?.container_id || "",
    fuel_id: props.car?.fuel_id || "",
    year: props.car?.year || "",
    color_id: props.car?.color_id || "",
    condition: props.car?.condition || "NEW",
    transmission: props.car?.transmission || "",
    body_number: props.car?.body_number || "",
    engine_number: props.car?.engine_number || "",
    engine_capacity_cc: props.car?.engine_capacity_cc || "",
    registration_type: props.car?.registration_type || "TAX_PAPER",
    plate_number: props.car?.plate_number || "",
    certificate_number: props.car?.certificate_number || "",
    quantity: props.car?.quantity ?? 1,
    inventory_status: props.car?.inventory_status || "on_the_way",
    sales_status: props.car?.sales_status || "AVAILABLE",
    purchase_price: props.car?.purchase_price || 0,
    cif_price: props.car?.cif_price || 0,
    transport_cost: props.car?.transport_cost || 0,
    expected_profit: props.car?.expected_profit || 0,
    selling_price: props.car?.selling_price || 0,
    discounted_price: props.car?.discounted_price || 0,
    is_active: props.car?.is_active ?? true,
    images: [] as File[],
    deleted_images: [] as any[],
    existing_image_order: [] as number[],
});

const makerIdRef = computed(() => form.maker_id);
const carModelIdRef = computed({
    get: () => form.car_model_id,
    set: (val) => { form.car_model_id = val; }
});

const { filteredList: filteredCarModels } = useDependentDropdown(
    props.carModels,
    'maker_id',
    makerIdRef,
    carModelIdRef
);

const modelOptions = computed(() => [
    { value: "", label: "-- Select Model --" },
    ...filteredCarModels.value.map((m: any) => ({ value: m.id, label: m.name })),
]);

const carImageError = computed(() => {
    if (form.errors.images) return form.errors.images;
    const specificErrorKey = Object.keys(form.errors).find(k => k.startsWith('images.'));
    return specificErrorKey ? form.errors[specificErrorKey as keyof typeof form.errors] : undefined;
});

// # Submit
const submit = () => {
    const submitOptions = {
        preserveScroll: true,
        onError: (errors: any) => {
            const basicFields = [
                "name", "maker_id", "car_model_id", "container_id", "fuel_id",
                "description", "options", "year", "color_id", "condition", "transmission",
                "body_number", "engine_number", "engine_capacity_cc", "registration_type",
                "plate_number", "certificate_number"
            ];

            const hasBasicError = Object.keys(errors).some(key => basicFields.includes(key) || key.startsWith('images'));

            if (hasBasicError) {
                activeTab.value = 'basic';
            }
        }
    };

    if (props.isEdit && props.car) {
        // Inertia needs _method = PUT for form data with files
        form.transform((data) => ({
            ...data,
            _method: "put",
        })).post(route("admin.cars.update", props.car.id), submitOptions);
    } else {
        form.post(route("admin.cars.store"), submitOptions);
    }
};
</script>
