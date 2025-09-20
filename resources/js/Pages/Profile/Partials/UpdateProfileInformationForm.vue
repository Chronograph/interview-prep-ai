<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="flex items-center gap-4 mb-8">
            <div class="bg-gradient-to-br from-green-100 to-emerald-100 rounded-full w-10 h-10 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Profile Information
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Update your account's profile information and email address.
                </p>
            </div>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-6"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Enter your full name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="Enter your email address"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
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
                        class="flex items-center gap-2 text-sm text-green-600 bg-green-50/50 px-3 py-2 rounded-lg backdrop-blur-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Profile updated successfully!
                    </div>
                </Transition>
                
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </div>
        </form>
    </section>
</template>
