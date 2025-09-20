<script setup>
import { ref, onMounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

// Component state
const stats = ref({
    total_interviews: 0,
    completed_interviews: 0,
    pending_interviews: 0,
    average_score: 0,
    total_questions_answered: 0,
    improvement_rate: 0
})

const recentInterviews = ref([])
const progressData = ref([])
const isLoading = ref(true)
const error = ref(null)

// Computed properties
const completionRate = computed(() => {
    if (stats.value.total_interviews === 0) return 0
    return Math.round((stats.value.completed_interviews / stats.value.total_interviews) * 100)
})

const performanceGrade = computed(() => {
    const score = stats.value.average_score
    if (score >= 90) return { grade: 'A+', color: 'text-green-600', bg: 'bg-green-100' }
    if (score >= 80) return { grade: 'A', color: 'text-green-600', bg: 'bg-green-100' }
    if (score >= 70) return { grade: 'B', color: 'text-blue-600', bg: 'bg-blue-100' }
    if (score >= 60) return { grade: 'C', color: 'text-yellow-600', bg: 'bg-yellow-100' }
    return { grade: 'D', color: 'text-red-600', bg: 'bg-red-100' }
})

const improvementTrend = computed(() => {
    const rate = stats.value.improvement_rate
    if (rate > 0) return { text: `+${rate}%`, color: 'text-green-600', icon: '↗' }
    if (rate < 0) return { text: `${rate}%`, color: 'text-red-600', icon: '↘' }
    return { text: '0%', color: 'text-gray-600', icon: '→' }
})

// Methods
const loadDashboardData = async () => {
    try {
        isLoading.value = true
        error.value = null
        
        const response = await fetch('/api/dashboard/stats', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        
        if (!response.ok) {
            throw new Error('Failed to load dashboard data')
        }
        
        const data = await response.json()
        stats.value = data.stats
        recentInterviews.value = data.recent_interviews
        progressData.value = data.progress_data
    } catch (err) {
        error.value = err.message
        console.error('Dashboard loading error:', err)
    } finally {
        isLoading.value = false
    }
}

const startNewInterview = () => {
    router.visit('/interviews/create')
}

const viewInterview = (interviewId) => {
    router.visit(`/interviews/${interviewId}`)
}

const viewAllInterviews = () => {
    router.visit('/interviews')
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}

const getStatusColor = (status) => {
    const colors = {
        'completed': 'bg-green-100 text-green-800',
        'in_progress': 'bg-blue-100 text-blue-800',
        'pending': 'bg-yellow-100 text-yellow-800',
        'cancelled': 'bg-red-100 text-red-800'
    }
    return colors[status] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(() => {
    loadDashboardData()
})
</script>

<template>
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ user.name }}!</h1>
                    <p class="text-gray-600 mt-1">Track your interview progress and improve your skills</p>
                </div>
                <PrimaryButton @click="startNewInterview" class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Start New Interview
                </PrimaryButton>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-red-800">Error Loading Dashboard</h3>
                    <p class="text-red-600 mt-1">{{ error }}</p>
                </div>
            </div>
            <SecondaryButton @click="loadDashboardData" class="mt-4">
                Try Again
            </SecondaryButton>
        </div>

        <!-- Dashboard Content -->
        <div v-else>
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Interviews -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Interviews</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.total_interviews }}</p>
                        </div>
                    </div>
                </div>

                <!-- Completion Rate -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                            <p class="text-2xl font-bold text-gray-900">{{ completionRate }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Average Score -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg" :class="performanceGrade.bg">
                            <svg class="w-6 h-6" :class="performanceGrade.color" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Performance Grade</p>
                            <p class="text-2xl font-bold" :class="performanceGrade.color">{{ performanceGrade.grade }}</p>
                        </div>
                    </div>
                </div>

                <!-- Improvement Trend -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Improvement</p>
                            <p class="text-2xl font-bold" :class="improvementTrend.color">
                                {{ improvementTrend.icon }} {{ improvementTrend.text }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Interviews -->
            <div class="bg-white rounded-lg shadow-sm border mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Interviews</h2>
                        <SecondaryButton @click="viewAllInterviews" class="text-sm">
                            View All
                        </SecondaryButton>
                    </div>
                </div>
                
                <div v-if="recentInterviews.length === 0" class="p-8 text-center">
                    <div class="text-gray-500 mb-4">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Interviews Yet</h3>
                    <p class="text-gray-600 mb-4">Start your first interview to begin tracking your progress</p>
                    <PrimaryButton @click="startNewInterview">
                        Start Your First Interview
                    </PrimaryButton>
                </div>
                
                <div v-else class="divide-y divide-gray-200">
                    <div 
                        v-for="interview in recentInterviews" 
                        :key="interview.id"
                        class="p-6 hover:bg-gray-50 cursor-pointer transition-colors"
                        @click="viewInterview(interview.id)"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">{{ interview.title }}</h3>
                                    <span 
                                        class="px-2 py-1 text-xs font-medium rounded-full"
                                        :class="getStatusColor(interview.status)"
                                    >
                                        {{ interview.status.replace('_', ' ').toUpperCase() }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">{{ interview.job_posting?.title || 'General Interview' }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>{{ formatDate(interview.created_at) }}</span>
                                    <span v-if="interview.score">Score: {{ interview.score }}%</span>
                                    <span v-if="interview.questions_count">{{ interview.questions_count }} questions</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Practice Interview -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-blue-200 rounded-lg">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-900 ml-3">Practice Interview</h3>
                    </div>
                    <p class="text-blue-700 mb-4">Start a practice session with AI-generated questions</p>
                    <PrimaryButton @click="startNewInterview" class="w-full">
                        Start Practice
                    </PrimaryButton>
                </div>

                <!-- Upload Resume -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-green-200 rounded-lg">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-green-900 ml-3">Upload Resume</h3>
                    </div>
                    <p class="text-green-700 mb-4">Upload your resume for personalized interview questions</p>
                    <SecondaryButton @click="router.visit('/resumes')" class="w-full">
                        Manage Resumes
                    </SecondaryButton>
                </div>

                <!-- View Analytics -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-purple-200 rounded-lg">
                            <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-purple-900 ml-3">View Analytics</h3>
                    </div>
                    <p class="text-purple-700 mb-4">Detailed insights into your interview performance</p>
                    <SecondaryButton @click="router.visit('/analytics')" class="w-full">
                        View Analytics
                    </SecondaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>