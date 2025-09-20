<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import InterviewInterface from '@/Components/InterviewInterface.vue'
import JobPostingManager from '@/Components/JobPostingManager.vue'
import ResumeManager from '@/Components/ResumeManager.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    user: Object,
    stats: {
        type: Object,
        default: () => ({
            total_interviews: 0,
            completed_interviews: 0,
            total_job_postings: 0,
            total_resumes: 0,
            avg_score: 0
        })
    },
    recent_interviews: {
        type: Array,
        default: () => []
    },
    job_postings: {
        type: Array,
        default: () => []
    },
    resumes: {
        type: Array,
        default: () => []
    }
})

// Component state
const activeTab = ref('overview')
const showInterviewInterface = ref(false)
const selectedJobPosting = ref(null)
const selectedInterview = ref(null)

// Computed properties
const completionRate = computed(() => {
    if (props.stats.total_interviews === 0) return 0
    return Math.round((props.stats.completed_interviews / props.stats.total_interviews) * 100)
})

const primaryResume = computed(() => {
    return props.resumes.find(resume => resume.is_primary)
})

const recentJobPostings = computed(() => {
    return props.job_postings
        .filter(jobPosting => jobPosting && jobPosting.title)
        .slice(0, 3)
})

// Methods
const startInterview = async (jobPosting = null) => {
    try {
        // Create a new interview session
        const response = await router.post('/api/interviews', {
            job_posting_id: jobPosting?.id || null,
            resume_id: primaryResume.value?.id || null
        }, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                // Get the created interview from the response
                const interview = page.props.interview
                if (interview) {
                    selectedInterview.value = interview
                    selectedJobPosting.value = jobPosting
                    showInterviewInterface.value = true
                }
            },
            onError: (errors) => {
                console.error('Failed to create interview:', errors)
                alert('Failed to start interview. Please try again.')
            }
        })
    } catch (error) {
        console.error('Error starting interview:', error)
        alert('Failed to start interview. Please try again.')
    }
}

const closeInterview = () => {
    showInterviewInterface.value = false
    selectedJobPosting.value = null
    // Refresh data after interview
    router.reload({ only: ['stats', 'recent_interviews'] })
}

const refreshData = () => {
    router.reload({ only: ['stats', 'recent_interviews', 'job_postings', 'resumes'] })
}

const getScoreColor = (score) => {
    if (score >= 80) return 'text-green-600'
    if (score >= 60) return 'text-yellow-600'
    return 'text-red-600'
}

const getScoreBadgeColor = (score) => {
    if (score >= 80) return 'bg-green-100 text-green-800'
    if (score >= 60) return 'bg-yellow-100 text-yellow-800'
    return 'bg-red-100 text-red-800'
}

onMounted(() => {
    // Auto-refresh data every 5 minutes
    setInterval(refreshData, 5 * 60 * 1000)
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-orange-600 to-orange-700 bg-clip-text text-transparent">
                        Interview Prep Dashboard
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Welcome back, {{ user.name }}! Ready to ace your next interview?</p>
                </div>
                <div class="flex gap-3">
                    <SecondaryButton @click="refreshData" class="bg-white/70 dark:bg-gray-700/70 backdrop-blur-sm hover:bg-white/90 dark:hover:bg-gray-700/90 border-orange-200 dark:border-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </SecondaryButton>
                    <PrimaryButton @click="startInterview()" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Start Interview
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Interview Interface Modal -->
                <div v-if="showInterviewInterface" class="fixed inset-0 z-50 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                            <InterviewInterface 
                                :interview="selectedInterview"
                                @close="closeInterview"
                                @interview-completed="closeInterview"
                            />
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="mb-8">
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-xl p-2 shadow-lg border border-orange-200/30 dark:border-gray-700/30">
                        <nav class="flex space-x-2">
                            <button 
                                @click="activeTab = 'overview'"
                                :class="[
                                    'px-6 py-3 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2',
                                    activeTab === 'overview' 
                                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg transform scale-105' 
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700/50 hover:text-orange-600 dark:hover:text-orange-400'
                                ]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Overview
                            </button>
                            <button 
                                @click="activeTab = 'job-postings'"
                                :class="[
                                    'px-6 py-3 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2',
                                    activeTab === 'job-postings' 
                                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg transform scale-105' 
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700/50 hover:text-orange-600 dark:hover:text-orange-400'
                                ]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                </svg>
                                Job Postings
                            </button>
                            <button 
                                @click="activeTab = 'resumes'"
                                :class="[
                                    'px-6 py-3 rounded-lg font-medium text-sm transition-all duration-200 flex items-center gap-2',
                                    activeTab === 'resumes' 
                                        ? 'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg transform scale-105' 
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700/50 hover:text-orange-600 dark:hover:text-orange-400'
                                ]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Resumes
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Overview Tab -->
                <div v-if="activeTab === 'overview'" class="space-y-8">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 overflow-hidden shadow-lg rounded-xl border border-blue-200/50 dark:border-blue-700/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400 truncate">Total Interviews</dt>
                                            <dd class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ stats.total_interviews }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 overflow-hidden shadow-lg rounded-xl border border-green-200/50 dark:border-green-700/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-green-600 dark:text-green-400 truncate">Completion Rate</dt>
                                            <dd class="text-2xl font-bold text-green-900 dark:text-green-100">{{ completionRate }}%</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 overflow-hidden shadow-lg rounded-xl border border-purple-200/50 dark:border-purple-700/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-purple-600 dark:text-purple-400 truncate">Average Score</dt>
                                            <dd class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                                {{ stats.avg_score }}%
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 overflow-hidden shadow-lg rounded-xl border border-orange-200/50 dark:border-orange-700/50 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-orange-600 dark:text-orange-400 truncate">Job Postings</dt>
                                            <dd class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ stats.total_job_postings }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Recent Interviews -->
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm shadow-xl rounded-2xl border border-orange-200/30 dark:border-gray-700/30">
                            <div class="px-6 py-6 sm:p-8">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Interviews</h3>
                                </div>
                                <div v-if="recent_interviews.length > 0" class="space-y-4">
                                    <div 
                                        v-for="interview in recent_interviews" 
                                        :key="interview.id"
                                        class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50/50 to-orange-100/50 dark:from-gray-700/30 dark:to-gray-600/30 rounded-xl border border-orange-200/30 dark:border-gray-600/30 hover:shadow-md transition-all duration-200"
                                    >
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ interview.job_posting?.title || 'General Interview' }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ new Date(interview.created_at).toLocaleDateString() }}</p>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span 
                                                v-if="interview.overall_score"
                                                :class="['px-2 py-1 text-xs font-medium rounded-full', getScoreBadgeColor(interview.overall_score)]"
                                            >
                                                {{ interview.overall_score }}%
                                            </span>
                                            <span 
                                                :class="[
                                                    'px-2 py-1 text-xs font-medium rounded-full',
                                                    interview.status === 'completed' 
                                                        ? 'bg-green-100 text-green-800' 
                                                        : 'bg-yellow-100 text-yellow-800'
                                                ]"
                                            >
                                                {{ interview.status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500">No interviews yet</p>
                                    <PrimaryButton @click="startInterview()" class="mt-4">
                                        Start Your First Interview
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm shadow-xl rounded-2xl border border-orange-200/30 dark:border-gray-700/30">
                            <div class="px-6 py-6 sm:p-8">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                                </div>
                                <div class="space-y-4">
                                    <div v-if="recentJobPostings.length > 0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Practice with Recent Job Postings</h4>
                                        <div class="space-y-2">
                                            <button 
                                                v-for="jobPosting in recentJobPostings" 
                                                :key="jobPosting.id"
                                                @click="startInterview(jobPosting)"
                                                class="w-full text-left p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800/30 dark:hover:to-blue-700/30 rounded-xl border border-blue-200/50 dark:border-blue-700/50 transition-all duration-200 hover:shadow-md hover:scale-105"
                                            >
                                                <div class="font-semibold text-blue-900 dark:text-blue-100">{{ jobPosting?.title || 'Untitled Position' }}</div>
                                                <div class="text-sm text-blue-700 dark:text-blue-300">{{ jobPosting?.company || 'Unknown Company' }}</div>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t pt-4">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Manage Your Profile</h4>
                                        <div class="space-y-2">
                                            <button 
                                                @click="activeTab = 'resumes'"
                                                class="w-full flex items-center p-4 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-orange-50 hover:to-orange-100 dark:hover:from-gray-700/30 dark:hover:to-gray-600/30 rounded-xl border border-transparent hover:border-orange-200/50 dark:hover:border-gray-600/50 transition-all duration-200 hover:shadow-md"
                                            >
                                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-semibold">Manage Resumes ({{ stats.total_resumes }})</span>
                                            </button>
                                            <button 
                                                @click="activeTab = 'job-postings'"
                                                class="w-full flex items-center p-4 text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100 dark:hover:from-gray-700/30 dark:hover:to-gray-600/30 rounded-xl border border-transparent hover:border-green-200/50 dark:hover:border-gray-600/50 transition-all duration-200 hover:shadow-md"
                                            >
                                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center mr-3">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-semibold">Add Job Postings ({{ stats.total_job_postings }})</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Postings Tab -->
                <div v-if="activeTab === 'job-postings'">
                    <JobPostingManager 
                        :job-postings="job_postings"
                        @job-posting-created="refreshData"
                        @job-posting-updated="refreshData"
                        @job-posting-deleted="refreshData"
                        @start-interview="startInterview"
                    />
                </div>

                <!-- Resumes Tab -->
                <div v-if="activeTab === 'resumes'">
                    <ResumeManager 
                        :resumes="resumes"
                        @resume-created="refreshData"
                        @resume-updated="refreshData"
                        @resume-deleted="refreshData"
                        @primary-changed="refreshData"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
