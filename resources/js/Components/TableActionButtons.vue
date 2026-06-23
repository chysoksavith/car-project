<template>
    <div class="flex items-center justify-center gap-4">
        <button
            v-if="hasEdit"
            @click.stop="$emit('edit')"
            class="flex items-center gap-1.5 text-info hover:opacity-70 transition-opacity text-sm font-medium"
            title="Edit"
        >
            <i class="fa-solid fa-pen-to-square"></i>
            <span v-if="withLabels">Edit</span>
        </button>

        <button
            v-if="hasDelete"
            @click.stop="$emit('delete')"
            class="flex items-center gap-1.5 text-error hover:opacity-70 transition-opacity text-sm font-medium"
            title="Delete"
        >
            <i class="fa-solid fa-trash-can"></i>
            <span v-if="withLabels">Delete</span>
        </button>
    </div>
</template>

<script setup lang="ts">
defineProps({
    hasEdit: { type: Boolean, default: true },
    hasDelete: { type: Boolean, default: true },
    withLabels: { type: Boolean, default: true },
});

defineEmits(["edit", "delete"]);
</script>
