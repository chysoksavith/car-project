<template>
    <div class="form-control w-full">
        <InputLabel :value="label" :required="required" />
        <input
            ref="input"
            class="input w-full transition-all bg-base-200/70 border-transparent focus:bg-base-100 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl px-4 font-medium"
            :class="[
                error ? 'input-error focus:input-error border-error/50 focus:border-error' : '',
                inputClass
            ]"
            :value="modelValue"
            :required="required"
            @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
            v-bind="$attrs"
        />
        <InputError :message="error" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import InputLabel from './InputLabel.vue';
import InputError from './InputError.vue';

defineProps<{
    modelValue: string | number | null;
    label?: string;
    error?: string;
    inputClass?: string;
    required?: boolean;
}>();

defineEmits(['update:modelValue']);

// Expose focus method to parent
const input = ref<HTMLInputElement | null>(null);
defineExpose({ focus: () => input.value?.focus() });
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
