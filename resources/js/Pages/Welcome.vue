<script setup>
import { Head, Link } from '@inertiajs/vue3';
import LandingPage from '@/Components/LandingPage.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Interview Prep AI - Master Your Next Interview" />
    
    <LandingPage 
        :can-login="canLogin" 
        :can-register="canRegister" 
        :laravel-version="laravelVersion" 
        :php-version="phpVersion" 
    />
</template>
