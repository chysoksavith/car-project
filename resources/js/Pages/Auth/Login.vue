<template>
    <div class="min-h-screen flex bg-base-100">
        <!-- Left Side: Brand/Image Area (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primary items-center justify-center overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary/80 z-10"></div>

            <!-- Abstract Shapes -->
            <div class="absolute w-[800px] h-[800px] rounded-full bg-white/10 -top-40 -left-40 blur-3xl z-10"></div>
            <div class="absolute w-[600px] h-[600px] rounded-full bg-black/10 bottom-0 right-0 blur-3xl z-10"></div>
            <div class="absolute inset-0 z-10 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 32px 32px;"></div>

            <!-- Brand Content -->
            <div class="relative z-20 flex flex-col items-center text-primary-content text-center px-12">
                <div class="w-24 h-24 bg-base-100 rounded-3xl flex items-center justify-center text-primary text-5xl font-black mb-10 shadow-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                    S
                </div>
                <h1 class="text-5xl font-bold tracking-tight mb-6">Top Auto </h1>
                <p class="text-lg opacity-90 max-w-md leading-relaxed">
                    Top Auto
                </p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 md:p-20 bg-base-100 relative overflow-hidden">

            <!-- Subtle background blob for right side -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-3xl -z-10 translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>

            <div class="w-full max-w-md space-y-8 relative z-10">
                <!-- Mobile Logo (Only visible on small screens) -->
                <div class="flex lg:hidden flex-col items-center gap-3 mb-10">
                    <div class="w-16 h-16 rounded-2xl bg-primary flex items-center justify-center text-primary-content font-bold text-3xl shadow-lg shadow-primary/30">
                        S
                    </div>
                    <h2 class="text-2xl font-bold mt-2">SaaS App</h2>
                </div>

                <!-- Header -->
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-base-content mb-2">Welcome back</h2>
                    <p class="text-base-content/60">Please enter your credentials to access your account.</p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6 mt-8">
                    <TextInput
                        v-model="form.email"
                        type="email"
                        label="Email Address"
                        placeholder="name@company.com"
                        :error="form.errors.email"
                        required
                    />

                    <!-- Custom Password Input layout to allow "Forgot Password" on the same line as the label -->
                    <div class="form-control w-full">
                        <div class="flex items-center justify-between pb-1">
                            <label class="label p-0">
                                <span class="label-text font-medium text-base-content/80">
                                    Password <span class="text-error ml-1" aria-hidden="true">*</span>
                                </span>
                            </label>
                            <a href="#" class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                                Forgot password?
                            </a>
                        </div>
                        <input
                            type="password"
                            v-model="form.password"
                            placeholder="••••••••"
                            required
                            class="input w-full transition-all bg-base-200/70 border-transparent focus:bg-base-100 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl px-4 font-medium"
                            :class="{ 'input-error border-error/50 focus:border-error focus:ring-error/10': form.errors.password }"
                        />
                        <div v-show="form.errors.password" class="label pt-1 pb-0">
                            <span class="label-text-alt text-error font-medium">{{ form.errors.password }}</span>
                        </div>
                    </div>

                    <div class="flex items-center pt-2">
                        <Checkbox v-model="form.remember" label="Remember me for 30 days" />
                    </div>

                    <div class="pt-4">
                        <Button type="submit" class="w-full h-12 text-lg font-medium shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all" :loading="form.processing">
                            Sign In
                        </Button>
                    </div>

                    <p class="text-center text-sm text-base-content/60 mt-8 font-medium">
                        Don't have an account?
                        <a href="#" class="font-semibold text-primary hover:text-primary/80 transition-colors ml-1">Contact Admin</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/Form/TextInput.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import Button from '@/Components/Button.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>
