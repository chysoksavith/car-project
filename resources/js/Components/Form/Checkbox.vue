<template>
    <div class="form-control">
        <label class="label cursor-pointer justify-start gap-3 w-max">
            <input 
                type="checkbox" 
                class="checkbox checkbox-primary" 
                :class="[{ 'checkbox-error': error }, checkboxClass]"
                :checked="modelValue" 
                :required="required"
                @change="$emit('update:modelValue', ($event.target as HTMLInputElement).checked)"
                v-bind="$attrs" 
            />
            <span v-if="label || $slots.default" class="label-text">
                <slot>{{ label }}</slot>
                <span v-if="required" class="text-error ml-1" aria-hidden="true">*</span>
            </span>
        </label>
        <InputError :message="error" class="!pt-0" />
    </div>
</template>

<script setup lang="ts">
import InputError from './InputError.vue';

defineProps<{
    modelValue: boolean;
    label?: string;
    error?: string;
    checkboxClass?: string;
    required?: boolean;
}>();

defineEmits(['update:modelValue']);
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
