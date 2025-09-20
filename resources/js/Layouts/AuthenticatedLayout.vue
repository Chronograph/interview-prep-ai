<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <!-- Background Pattern -->
        <div class="fixed inset-0 bg-gradient-to-br from-orange-50 via-white to-orange-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="absolute inset-0 opacity-30">
                <div class="w-full h-full" style="background-image: radial-gradient(circle at 1px 1px, rgba(249, 115, 22, 0.15) 1px, transparent 0); background-size: 20px 20px;"></div>
            </div>
        </div>
        
        <div class="relative min-h-screen">
            <nav
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-orange-200/50 dark:border-gray-700/50 shadow-lg"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')" class="group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 transform group-hover:scale-105">
                                            <ApplicationLogo
                                                class="block h-6 w-auto fill-current text-white"
                                            />
                                        </div>
                                        <span class="text-xl font-bold bg-gradient-to-r from-orange-600 to-orange-700 bg-clip-text text-transparent hidden sm:block">
                                            Interview Prep AI
                                        </span>
                                    </div>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                    class="px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-orange-50 dark:hover:bg-gray-700/50"
                                    :class="route().current('dashboard') ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' : 'text-gray-600 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400'"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    :href="route('analytics.dashboard')"
                                    :active="route().current('analytics.*')"
                                    class="px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-orange-50 dark:hover:bg-gray-700/50"
                                    :class="route().current('analytics.*') ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' : 'text-gray-600 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400'"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Analytics
                                </NavLink>
                                <NavLink
                                    :href="route('analytics.applications.index')"
                                    :active="route().current('analytics.applications.*')"
                                    class="px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:bg-orange-50 dark:hover:bg-gray-700/50"
                                    :class="route().current('analytics.applications.*') ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' : 'text-gray-600 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400'"
                                >
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Applications
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-lg border border-transparent bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm px-4 py-2 text-sm font-medium leading-4 text-gray-700 dark:text-gray-200 transition duration-150 ease-in-out hover:bg-white/70 dark:hover:bg-gray-700/70 hover:text-orange-600 dark:hover:text-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-sm"
                                            >
                                                <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                                    </span>
                                                </div>
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('analytics.dashboard')"
                            :active="route().current('analytics.*')"
                        >
                            Analytics
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('analytics.applications.index')"
                            :active="route().current('analytics.applications.*')"
                        >
                            Applications
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm shadow-sm border-b border-orange-200/30 dark:border-gray-700/30"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="relative">
                <slot />
            </main>
        </div>
    </div>
</template>
