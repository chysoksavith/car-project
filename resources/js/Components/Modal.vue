<template>
    <dialog ref="dialogRef" class="modal">
        <div class="modal-box" :class="maxWidthClass">
            <h3 v-if="title" class="font-bold text-lg mb-4">{{ title }}</h3>

            <slot />

            <div class="modal-action" v-if="$slots.actions">
                <slot name="actions" />
            </div>
        </div>
        <form v-if="closeable" method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";

const props = defineProps({
    title: { type: String, default: "" },
    maxWidth: { type: String, default: "md" },
    closeable: { type: Boolean, default: true },
});

const dialogRef = ref<HTMLDialogElement | null>(null);

const maxWidthClass = computed(() => {
    return (
        {
            sm: "max-w-sm",
            md: "max-w-md",
            lg: "max-w-lg",
            xl: "max-w-xl",
            "2xl": "max-w-2xl",
            "3xl": "max-w-3xl",
            "4xl": "max-w-4xl",
            "5xl": "max-w-5xl",
            "6xl": "max-w-6xl",
            "7xl": "max-w-7xl",
            full: "max-w-full",
        }[props.maxWidth] || "max-w-md"
    );
});

defineExpose({
    showModal: () => dialogRef.value?.showModal(),
    close: () => dialogRef.value?.close(),
});
</script>
