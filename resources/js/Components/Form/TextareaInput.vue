<template>
    <div class="form-control w-full">
        <InputLabel v-if="label" :value="label" :required="required" />
        <textarea
            ref="input"
            class="textarea textarea-bordered w-full"
            :class="[
                error
                    ? 'textarea-error focus:textarea-error border-error/50 focus:border-error'
                    : '',
                inputClass,
            ]"
            :value="modelValue"
            :required="required"
            @input="
                $emit(
                    'update:modelValue',
                    ($event.target as HTMLTextAreaElement).value,
                )
            "
            v-bind="$attrs"
        ></textarea>
        <InputError v-if="error" :message="error" />
        <div v-if="hint && !error" class="label pb-0">
            <span class="label-text-alt text-base-content/50">{{ hint }}</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import InputLabel from "./InputLabel.vue";
import InputError from "./InputError.vue";

defineProps<{
    modelValue: string | null;
    label?: string;
    error?: string;
    hint?: string;
    inputClass?: string;
    required?: boolean;
}>();

defineEmits(["update:modelValue"]);

// Expose focus method to parent
const input = ref<HTMLTextAreaElement | null>(null);
defineExpose({ focus: () => input.value?.focus() });
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
