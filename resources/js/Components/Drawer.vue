<template>
    <Teleport to="body">
        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 bg-base-300/50 backdrop-blur-sm z-[998]"
                @click="close"
            ></div>
        </transition>

        <transition
            enter-active-class="transform transition ease-in-out duration-300"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transform transition ease-in-out duration-300"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="modelValue"
                class="fixed inset-y-0 right-0 z-[999] flex max-w-full"
                :class="maxWidthClass"
            >
                <div class="w-screen h-full bg-base-100 shadow-xl flex flex-col border-l border-base-200">
                    <div class="px-6 py-4 border-b border-base-200 flex items-center justify-between bg-base-50/50">
                        <h2 class="text-lg font-semibold text-base-content">{{ title }}</h2>
                        <button
                            @click="close"
                            class="btn btn-sm btn-ghost btn-circle"
                        >
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6 bg-base-50/30">
                        <slot />
                    </div>
                    <div v-if="$slots.footer" class="px-6 py-4 border-t border-base-200 bg-base-50/50">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>

<script setup lang="ts">
import { watch, computed } from "vue";

const props = withDefaults(
    defineProps<{
        modelValue: boolean;
        title?: string;
        maxWidth?: "sm" | "md" | "lg" | "xl" | "2xl" | "3xl" | "4xl" | "full";
    }>(),
    {
        title: "Details",
        maxWidth: "md",
    }
);

const emit = defineEmits(["update:modelValue", "close"]);

const close = () => {
    emit("update:modelValue", false);
    emit("close");
};

const maxWidthClass = computed(() => {
    return {
        sm: "max-w-sm",
        md: "max-w-md",
        lg: "max-w-lg",
        xl: "max-w-xl",
        "2xl": "max-w-2xl",
        "3xl": "max-w-3xl",
        "4xl": "max-w-4xl",
        full: "max-w-full",
    }[props.maxWidth];
});

watch(
    () => props.modelValue,
    (value) => {
        if (value) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = "";
        }
    }
);
</script>
