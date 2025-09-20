<template>
    <Head title="Analytics - Interview Prep AI" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Overall Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Overall Mastery</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ Math.round(overallStats.overall_mastery) }}%</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Topics Practiced</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ overallStats.topics_practiced }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Practice Time</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ formatTime(overallStats.total_practice_time) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Accuracy Rate</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ Math.round(overallStats.accuracy_rate) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mastery Scores Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Topic Mastery Scores</h3>
                        <div class="space-y-4">
                            <div v-for="score in masteryScores" :key="score.topic" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ score.topic }}</h4>
                                    <p class="text-sm text-gray-500">{{ score.skill_count }} skills tracked</p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                        <div 
                                            class="h-2 rounded-full transition-all duration-300"
                                            :class="getScoreColor(score.average_score)"
                                            :style="{ width: score.average_score + '%' }"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 w-12 text-right">
                                        {{ Math.round(score.average_score) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-if="masteryScores.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No mastery data yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Start practicing to see your progress here.</p>
                        </div>
                    </div>
                </div>

                <!-- Topic Progress Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Topic Progress</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="progress in topicProgress" :key="progress.id" class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-gray-900">{{ progress.topic_name }}</h4>
                                    <span class="text-xs px-2 py-1 rounded-full" :class="getCategoryColor(progress.category)">
                                        {{ progress.category }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ Math.round(progress.completion_percentage) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div 
                                            class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                                            :style="{ width: progress.completion_percentage + '%' }"
                                        ></div>
                                    </div>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>{{ progress.questions_attempted }} questions</span>
                                    <span>{{ Math.round((progress.questions_correct / progress.questions_attempted) * 100) || 0 }}% accuracy</span>
                                </div>
                            </div>
                        </div>
                        <div v-if="topicProgress.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No progress data yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Complete some practice sessions to track your progress.</p>
                        </div>
                    </div>
                </div>

                <!-- Application Stats Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Application Tracking</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ applicationStats.total }}</div>
                                <div class="text-sm text-gray-500">Total Applications</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ applicationStats.active }}</div>
                                <div class="text-sm text-gray-500">Active</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ applicationStats.interviews }}</div>
                                <div class="text-sm text-gray-500">Interviews</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ applicationStats.offers }}</div>
                                <div class="text-sm text-gray-500">Offers</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ applicationStats.recent }}</div>
                                <div class="text-sm text-gray-500">This Month</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    masteryScores: {
        type: Array,
        default: () => []
    },
    topicProgress: {
        type: Array,
        default: () => []
    },
    applicationStats: {
        type: Object,
        default: () => ({})
    },
    overallStats: {
        type: Object,
        default: () => ({})
    }
});

const getScoreColor = (score) => {
    if (score >= 80) return 'bg-green-500';
    if (score >= 60) return 'bg-yellow-500';
    return 'bg-red-500';
};

const getCategoryColor = (category) => {
    const colors = {
        'Technical': 'bg-blue-100 text-blue-800',
        'Behavioral': 'bg-green-100 text-green-800',
        'Case Study': 'bg-purple-100 text-purple-800',
    };
    return colors[category] || 'bg-gray-100 text-gray-800';
};

const formatTime = (minutes) => {
    if (minutes < 60) return `${minutes}m`;
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    return remainingMinutes > 0 ? `${hours}h ${remainingMinutes}m` : `${hours}h`;
};
</script>