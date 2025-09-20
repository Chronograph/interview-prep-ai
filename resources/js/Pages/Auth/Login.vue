<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign In - Interview Prep AI" />

        <div class="text-center mb-8">
            <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Welcome Back</h2>
            <p class="mt-2 text-sm text-gray-600">Sign in to continue your interview preparation journey</p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-green-50/50 border border-green-200/50 rounded-xl backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="bg-gradient-to-br from-green-100 to-emerald-100 rounded-full w-8 h-8 flex items-center justify-center">
                    <svg class="h-4 w-4 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-green-800">{{ status }}</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email address"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Password</label>
                <input
                    id="password"
                    type="password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                    <span class="text-sm text-gray-700 font-medium">Remember me</span>
                </label>
                
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-all duration-200 hover:underline"
                >
                    Forgot password?
                </Link>
            </div>

            <div>
                <button
                    type="submit"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg v-if="form.processing" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    {{ form.processing ? 'Signing in...' : 'Sign In' }}
                </button>
            </div>
        </form>
        
        <div class="mt-8 text-center">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">New to Interview Prep AI?</span>
                </div>
            </div>
            <div class="mt-6">
                <Link 
                    :href="route('register')" 
                    class="w-full bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 text-gray-700 px-6 py-3 rounded-xl text-sm font-medium border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Create Free Account
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
