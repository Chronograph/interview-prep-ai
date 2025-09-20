<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header class="flex items-center gap-4 mb-8">
            <div class="bg-gradient-to-br from-red-100 to-rose-100 rounded-full w-10 h-10 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">
                    Delete Account
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                </p>
            </div>
        </header>

        <button
            @click="confirmUserDeletion"
            class="bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete Account
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/50 p-8 max-w-md mx-auto">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-gradient-to-br from-red-100 to-rose-100 rounded-full w-12 h-12 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold bg-gradient-to-r from-red-600 to-rose-600 bg-clip-text text-transparent">
                            Delete Account?
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            This action cannot be undone
                        </p>
                    </div>
                </div>

                <div class="bg-red-50/50 border border-red-200/50 rounded-xl p-4 mb-6">
                    <p class="text-sm text-gray-700">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Confirm Password</label>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                        placeholder="Enter your password to confirm"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        @click="closeModal"
                        class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-medium transition-all duration-200"
                    >
                        Cancel
                    </button>

                    <button
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        {{ form.processing ? 'Deleting...' : 'Delete Account' }}
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
