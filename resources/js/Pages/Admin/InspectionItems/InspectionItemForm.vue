<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Parent Category (Nullable) -->
            <SelectInput
                v-model="form.parent_id"
                label="Parent Category"
                :error="form.errors.parent_id"
                :options="categoryOptions"
                placeholder="-- Make this a Main Category --"
            />

            <div class="hidden md:block"></div>

            <TextInput
                v-model="form.name_kh"
                label="Name (Khmer)"
                :error="form.errors.name_kh"
                required
            />

            <TextInput
                v-model="form.name_en"
                label="Name (English)"
                :error="form.errors.name_en"
            />

            <TextInput
                v-model="form.default_price"
                label="Default Price (USD)"
                type="number"
                step="0.01"
                min="0"
                :error="form.errors.default_price"
            />

            <div class="flex items-center mt-8">
                <Toggle
                    v-model="form.is_active"
                    label="Is Active?"
                />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t border-base-200">
            <Button
                :href="route('admin.inspection-items.index')"
                variant="ghost"
                type="button"
            >
                Cancel
            </Button>
            <Button
                type="submit"
                variant="primary"
                :loading="form.processing"
                icon="fa-solid fa-save"
            >
                {{ isEdit ? "Update Item" : "Create Item" }}
            </Button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import TextInput from "@/Components/Form/TextInput.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";
import Toggle from "@/Components/Form/Toggle.vue";
import Button from "@/Components/Button.vue";

const props = defineProps<{
    inspectionItem?: any;
    categories: any[];
}>();

const isEdit = computed(() => !!props.inspectionItem);

const item = computed(() => props.inspectionItem?.data || props.inspectionItem || {});

const form = useForm({
    parent_id: item.value.parent_id ?? "",
    name_kh: item.value.name_kh ?? "",
    name_en: item.value.name_en ?? "",
    default_price: item.value.default_price ?? 0,
    is_active: item.value.is_active ?? true,
});

const categoryOptions = computed(() => {
    const list = Array.isArray(props.categories) ? props.categories : (props.categories?.data || []);
    return list.map((c: any) => ({
        value: c.id,
        label: c.name_kh + (c.name_en ? ` (${c.name_en})` : ""),
    }));
});

const submit = () => {
    // Convert empty string to null for parent_id
    if (form.parent_id === "") {
        form.parent_id = null;
    }

    if (isEdit.value) {
        form.put(route("admin.inspection-items.update", item.value.id));
    } else {
        form.post(route("admin.inspection-items.store"));
    }
};
</script>
