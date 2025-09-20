<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="flex items-center gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full w-10 h-10 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Update Password
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
        </header>

        <form
            @submit.prevent="updatePassword"
            class="space-y-6"
        >
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">Current Password</label>
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                    autocomplete="current-password"
                    placeholder="Enter your current password"
                />
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">New Password</label>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                        autocomplete="new-password"
                        placeholder="Enter new password"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    />
                    <InputError :message="form.errors.password_confirmation" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200/50">
                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0 transform translate-x-2"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0 transform translate-x-2"
                >
                    <div
                        v-if="form.recentlySuccessful"
                        class="flex items-center gap-2 text-sm text-blue-600 bg-blue-50/50 px-3 py-2 rounded-lg backdrop-blur-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Password updated successfully!
                    </div>
                </Transition>
                
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    {{ form.processing ? 'Updating...' : 'Update Password' }}
                </button>
            </div>
        </form>
    </section>
</template>
