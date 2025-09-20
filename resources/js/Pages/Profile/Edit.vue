<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-full w-12 h-12 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent leading-tight">
                        Profile Settings
                    </h2>
                    <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
                <div class="bg-white/80 backdrop-blur-sm p-6 shadow-xl sm:rounded-2xl sm:p-8 border border-gray-200/50">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-2xl"
                    />
                </div>

                <div class="bg-white/80 backdrop-blur-sm p-6 shadow-xl sm:rounded-2xl sm:p-8 border border-gray-200/50">
                    <UpdatePasswordForm class="max-w-2xl" />
                </div>

                <div class="bg-white/80 backdrop-blur-sm p-6 shadow-xl sm:rounded-2xl sm:p-8 border border-gray-200/50">
                    <DeleteUserForm class="max-w-2xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
