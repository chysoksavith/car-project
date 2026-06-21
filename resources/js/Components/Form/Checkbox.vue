<template>
    <div class="form-control">
        <label class="label cursor-pointer justify-start gap-3 w-max">
            <input
                type="checkbox"
                class="checkbox checkbox-primary"
                :class="[{ 'checkbox-error': error }, checkboxClass]"
                v-model="proxyModel"
                :value="value"
                :required="required"
                v-bind="$attrs"
            />
            <span v-if="label || $slots.default" class="label-text">
                <slot>{{ label }}</slot>
                <span v-if="required" class="text-error ml-1" aria-hidden="true"
                    >*</span
                >
            </span>
        </label>
        <InputError :message="error" class="!pt-0" />
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import InputError from "./InputError.vue";

const props = defineProps<{
    modelValue: boolean | any[];
    value?: any;
    label?: string;
    error?: string;
    checkboxClass?: string;
    required?: boolean;
}>();

const emit = defineEmits(["update:modelValue"]);

const proxyModel = computed({
    get() {
        return props.modelValue;
    },
    set(val) {
        emit("update:modelValue", val);
    },
});
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
