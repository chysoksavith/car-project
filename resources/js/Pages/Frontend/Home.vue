<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-base-200">
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body items-center text-center">
                <h2 class="card-title text-2xl mb-4">Welcome!</h2>
                <p class="text-base-content/80 mb-6">
                    You are logged in as a <strong>Frontend User</strong>.
                </p>
                <div class="card-actions">
                    <Button @click="logout" variant="primary">Logout</Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import Button from "@/Components/Button.vue";

const form = useForm({});

const logout = () => {
    form.post(route('logout'));
};
</script>
