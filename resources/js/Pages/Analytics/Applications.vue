<template>
    <Head title="Application Tracking - Interview Prep AI" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent leading-tight">
                        Application Tracking
                    </h2>
                    <p class="text-gray-600 mt-1">Manage and track your job applications</p>
                </div>
                <button 
                    @click="showAddModal = true"
                    class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Application
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Filter and Search -->
                <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-200/50">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search applications..."
                                class="w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white/50 backdrop-blur-sm"
                            >
                        </div>
                        <div class="flex gap-3">
                            <select 
                                v-model="statusFilter"
                                class="px-4 py-3 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white/50 backdrop-blur-sm"
                            >
                                <option value="">All Status</option>
                                <option value="applied">Applied</option>
                                <option value="screening">Screening</option>
                                <option value="interview">Interview</option>
                                <option value="offer">Offer</option>
                                <option value="rejected">Rejected</option>
                                <option value="withdrawn">Withdrawn</option>
                            </select>
                            <select 
                                v-model="priorityFilter"
                                class="px-4 py-3 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white/50 backdrop-blur-sm"
                            >
                                <option value="">All Priority</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Applications List -->
                <div class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200/50">
                    <div class="p-6">
                        <div class="space-y-6">
                            <div 
                                v-for="application in filteredApplications" 
                                :key="application.id"
                                class="bg-gradient-to-r from-white/90 to-gray-50/90 backdrop-blur-sm border border-gray-200/60 rounded-2xl p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ application.position_title }}</h3>
                                            <span 
                                                class="px-3 py-1 text-xs font-medium rounded-full shadow-sm"
                                                :class="getStatusColor(application.status)"
                                            >
                                                {{ application.status.charAt(0).toUpperCase() + application.status.slice(1) }}
                                            </span>
                                            <span 
                                                class="px-3 py-1 text-xs font-medium rounded-full shadow-sm"
                                                :class="getPriorityColor(application.priority)"
                                            >
                                                {{ application.priority.charAt(0).toUpperCase() + application.priority.slice(1) }}
                                            </span>
                                            <button 
                                                v-if="application.is_favorite"
                                                class="text-yellow-500 hover:text-yellow-600 transition-colors p-1 rounded-full hover:bg-yellow-50"
                                            >
                                                <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ application.company_name }}
                                        </p>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                            <div v-if="application.location" class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50/80 rounded-lg px-3 py-2">
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ application.location }}
                                            </div>
                                            <div v-if="application.work_type" class="flex items-center gap-2 text-sm text-gray-600 bg-blue-50/80 rounded-lg px-3 py-2">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6"></path>
                                                </svg>
                                                {{ application.work_type }}
                                            </div>
                                            <div v-if="application.salary_min && application.salary_max" class="flex items-center gap-2 text-sm text-gray-600 bg-green-50/80 rounded-lg px-3 py-2">
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                ${{ formatSalary(application.salary_min) }} - ${{ formatSalary(application.salary_max) }}
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-600 bg-purple-50/80 rounded-lg px-3 py-2">
                                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6 0h6"></path>
                                                </svg>
                                                Applied {{ formatDate(application.application_date) }}
                                            </div>
                                        </div>
                                        <p v-if="application.notes" class="text-sm text-gray-600 mb-2">{{ application.notes }}</p>
                                        <div v-if="application.interview_stages && application.interview_stages.length > 0" class="mb-4">
                                            <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Interview Stages:
                                            </p>
                                            <div class="flex flex-wrap gap-2">
                                                <span 
                                                    v-for="stage in application.interview_stages" 
                                                    :key="stage"
                                                    class="px-3 py-1 text-xs font-medium bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 rounded-full shadow-sm"
                                                >
                                                    {{ stage }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <button 
                                            @click="editApplication(application)"
                                            class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button 
                                            @click="deleteApplication(application.id)"
                                            class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div v-if="application.expected_response_date" class="mt-4 p-3 rounded-xl" :class="isOverdue(application.expected_response_date) ? 'bg-red-50/80 border border-red-200' : 'bg-blue-50/80 border border-blue-200'">
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg v-if="isOverdue(application.expected_response_date)" class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <svg v-else class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span 
                                            :class="isOverdue(application.expected_response_date) ? 'text-red-700 font-semibold' : 'text-blue-700 font-medium'"
                                        >
                                            Expected response: {{ formatDate(application.expected_response_date) }}
                                            <span v-if="isOverdue(application.expected_response_date)" class="font-bold">(Overdue)</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="filteredApplications.length === 0" class="text-center py-16">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 mx-auto max-w-md">
                                <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">No applications found</h3>
                                <p class="text-gray-600 mb-4">Start tracking your job applications to see them here.</p>
                                <button 
                                    @click="showAddModal = true"
                                    class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 mx-auto"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Your First Application
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Application Modal -->
        <div v-if="showAddModal || editingApplication" class="fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-6 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-2xl bg-white/95 backdrop-blur-sm border border-gray-200/50">
                <div class="mt-3">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-full w-12 h-12 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{ editingApplication ? 'Edit Application' : 'Add New Application' }}
                        </h3>
                    </div>
                    <form @submit.prevent="saveApplication" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Company Name *</label>
                                <input 
                                    v-model="applicationForm.company_name"
                                    type="text" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                    placeholder="Enter company name"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Position Title *</label>
                                <input 
                                    v-model="applicationForm.position_title"
                                    type="text" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                    placeholder="Enter position title"
                                >
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Status</label>
                                <select 
                                    v-model="applicationForm.status"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                >
                                    <option value="applied">Applied</option>
                                    <option value="screening">Screening</option>
                                    <option value="interview">Interview</option>
                                    <option value="offer">Offer</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="withdrawn">Withdrawn</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Priority</label>
                                <select 
                                    v-model="applicationForm.priority"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                >
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Work Type</label>
                                <select 
                                    v-model="applicationForm.work_type"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                >
                                    <option value="">Select...</option>
                                    <option value="remote">Remote</option>
                                    <option value="hybrid">Hybrid</option>
                                    <option value="onsite">On-site</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Location</label>
                                <input 
                                    v-model="applicationForm.location"
                                    type="text" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                    placeholder="Enter location"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Job URL</label>
                                <input 
                                    v-model="applicationForm.job_url"
                                    type="url" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                    placeholder="Enter job URL"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Min Salary</label>
                                <input 
                                    v-model="applicationForm.salary_min"
                                    type="number" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                    placeholder="Enter minimum salary"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Max Salary</label>
                                <input 
                                    v-model="applicationForm.salary_max"
                                    type="number" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                    placeholder="Enter maximum salary"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Application Date</label>
                                <input 
                                    v-model="applicationForm.application_date"
                                    type="date" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Expected Response Date</label>
                                <input 
                                    v-model="applicationForm.expected_response_date"
                                    type="date" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Notes</label>
                            <textarea 
                                v-model="applicationForm.notes"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50/50 backdrop-blur-sm transition-all duration-200 resize-none"
                                placeholder="Add any additional notes or comments"
                            ></textarea>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50/50 to-purple-50/50 rounded-xl border border-gray-200/50">
                            <input 
                                v-model="applicationForm.is_favorite" 
                                type="checkbox" 
                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-md transition-all duration-200"
                            >
                            <label class="block text-sm font-medium text-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                                Mark as favorite application
                            </label>
                        </div>

                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200/50">
                            <button 
                                type="button"
                                @click="closeModal"
                                class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100/80 hover:bg-gray-200/80 rounded-xl transition-all duration-200 backdrop-blur-sm"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ editingApplication ? 'Update Application' : 'Save Application' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    applications: {
        type: Array,
        default: () => []
    }
});

const showAddModal = ref(false);
const editingApplication = ref(null);
const searchQuery = ref('');
const statusFilter = ref('');
const priorityFilter = ref('');

const applicationForm = reactive({
    company_name: '',
    position_title: '',
    job_url: '',
    status: 'applied',
    priority: 'medium',
    application_date: new Date().toISOString().split('T')[0],
    expected_response_date: '',
    salary_min: '',
    salary_max: '',
    location: '',
    work_type: '',
    notes: '',
    is_favorite: false
});

const filteredApplications = computed(() => {
    return props.applications.filter(app => {
        const matchesSearch = !searchQuery.value || 
            app.company_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            app.position_title.toLowerCase().includes(searchQuery.value.toLowerCase());
        
        const matchesStatus = !statusFilter.value || app.status === statusFilter.value;
        const matchesPriority = !priorityFilter.value || app.priority === priorityFilter.value;
        
        return matchesSearch && matchesStatus && matchesPriority;
    });
});

const getStatusColor = (status) => {
    const colors = {
        'applied': 'bg-blue-100 text-blue-800',
        'screening': 'bg-yellow-100 text-yellow-800',
        'interview': 'bg-purple-100 text-purple-800',
        'offer': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
        'withdrawn': 'bg-gray-100 text-gray-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getPriorityColor = (priority) => {
    const colors = {
        'high': 'bg-red-100 text-red-800',
        'medium': 'bg-yellow-100 text-yellow-800',
        'low': 'bg-green-100 text-green-800'
    };
    return colors[priority] || 'bg-gray-100 text-gray-800';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
};

const formatSalary = (amount) => {
    return new Intl.NumberFormat().format(amount);
};

const isOverdue = (date) => {
    return new Date(date) < new Date();
};

const editApplication = (application) => {
    editingApplication.value = application;
    Object.assign(applicationForm, {
        ...application,
        application_date: application.application_date ? application.application_date.split('T')[0] : '',
        expected_response_date: application.expected_response_date ? application.expected_response_date.split('T')[0] : ''
    });
};

const closeModal = () => {
    showAddModal.value = false;
    editingApplication.value = null;
    resetForm();
};

const resetForm = () => {
    Object.assign(applicationForm, {
        company_name: '',
        position_title: '',
        job_url: '',
        status: 'applied',
        priority: 'medium',
        application_date: new Date().toISOString().split('T')[0],
        expected_response_date: '',
        salary_min: '',
        salary_max: '',
        location: '',
        work_type: '',
        notes: '',
        is_favorite: false
    });
};

const saveApplication = () => {
    const url = editingApplication.value 
        ? `/analytics/applications/${editingApplication.value.id}` 
        : '/analytics/applications';
    
    const method = editingApplication.value ? 'put' : 'post';
    
    router[method](url, applicationForm, {
        onSuccess: () => {
            closeModal();
        }
    });
};

const deleteApplication = (id) => {
    if (confirm('Are you sure you want to delete this application?')) {
        router.delete(`/analytics/applications/${id}`);
    }
};
</script>