<template>
    <div class="form-control w-full">
        <InputLabel :value="label" :required="required" />
        <select
            class="select select-bordered w-full"
            :class="[
                error
                    ? 'select-error focus:select-error border-error/50 focus:border-error'
                    : '',
                selectClass,
            ]"
            :value="modelValue"
            :required="required"
            @change="
                $emit(
                    'update:modelValue',
                    ($event.target as HTMLSelectElement).value,
                )
            "
            v-bind="$attrs"
        >
            <option v-if="placeholder" value="" disabled selected>
                {{ placeholder }}
            </option>
            <option
                v-for="(opt, index) in options"
                :key="index"
                :value="opt.value ?? opt"
            >
                {{ opt.label ?? opt }}
            </option>
        </select>
        <InputError :message="error" />
    </div>
</template>

<script setup lang="ts">
import InputLabel from "./InputLabel.vue";
import InputError from "./InputError.vue";

defineProps<{
    modelValue: string | number | null;
    options: any[];
    label?: string;
    error?: string;
    placeholder?: string;
    selectClass?: string;
    required?: boolean;
}>();

defineEmits(["update:modelValue"]);
</script>

<script lang="ts">
export default { inheritAttrs: false };
</script>
