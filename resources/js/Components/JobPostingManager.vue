<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import Modal from '@/Components/Modal.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    jobPostings: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['job-posting-created', 'job-posting-updated', 'job-posting-deleted'])

// Component state
const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingJobPosting = ref(null)
const isUploading = ref(false)
const uploadProgress = ref(0)

// Form for creating/editing job postings
const form = useForm({
    title: '',
    company: '',
    description: '',
    requirements: '',
    location: '',
    salary_range: '',
    employment_type: 'full-time',
    file: null
})

// File upload handling
const fileInput = ref(null)
const selectedFile = ref(null)

const handleFileSelect = (event) => {
    const file = event.target.files[0]
    if (file) {
        selectedFile.value = file
        form.file = file
        
        // Auto-parse if it's a supported file type
        if (file.type === 'application/pdf' || file.type.includes('text')) {
            parseJobPosting()
        }
    }
}

const parseJobPosting = async () => {
    if (!selectedFile.value) return
    
    isUploading.value = true
    uploadProgress.value = 0
    
    try {
        const formData = new FormData()
        formData.append('file', selectedFile.value)
        
        const response = await fetch('/api/job-postings/parse', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        
        if (response.ok) {
            const data = await response.json()
            
            // Auto-fill form with parsed data
            form.title = data.title || ''
            form.company = data.company || ''
            form.description = data.description || ''
            form.requirements = data.requirements || ''
            form.location = data.location || ''
            form.salary_range = data.salary_range || ''
            form.employment_type = data.employment_type || 'full-time'
        }
    } catch (error) {
        console.error('Error parsing job posting:', error)
        alert('Failed to parse job posting. Please fill in the details manually.')
    } finally {
        isUploading.value = false
        uploadProgress.value = 0
    }
}

// CRUD operations
const createJobPosting = () => {
    form.post('/api/job-postings', {
        onSuccess: () => {
            showCreateModal.value = false
            form.reset()
            selectedFile.value = null
            emit('job-posting-created')
        },
        onError: (errors) => {
            console.error('Validation errors:', errors)
        }
    })
}

const editJobPosting = (jobPosting) => {
    editingJobPosting.value = jobPosting
    form.title = jobPosting.title
    form.company = jobPosting.company
    form.description = jobPosting.description
    form.requirements = jobPosting.requirements
    form.location = jobPosting.location
    form.salary_range = jobPosting.salary_range
    form.employment_type = jobPosting.employment_type
    showEditModal.value = true
}

const updateJobPosting = () => {
    form.put(`/api/job-postings/${editingJobPosting.value.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            form.reset()
            editingJobPosting.value = null
            emit('job-posting-updated')
        }
    })
}

const deleteJobPosting = (jobPosting) => {
    if (confirm('Are you sure you want to delete this job posting?')) {
        router.delete(`/api/job-postings/${jobPosting.id}`, {
            onSuccess: () => {
                emit('job-posting-deleted')
            }
        })
    }
}

const downloadFile = (jobPosting) => {
    window.open(`/api/job-postings/${jobPosting.id}/download`, '_blank')
}

// Modal controls
const openCreateModal = () => {
    form.reset()
    selectedFile.value = null
    showCreateModal.value = true
}

const closeCreateModal = () => {
    showCreateModal.value = false
    form.reset()
    selectedFile.value = null
}

const closeEditModal = () => {
    showEditModal.value = false
    form.reset()
    editingJobPosting.value = null
}

// Computed properties
const employmentTypes = [
    { value: 'full-time', label: 'Full Time' },
    { value: 'part-time', label: 'Part Time' },
    { value: 'contract', label: 'Contract' },
    { value: 'internship', label: 'Internship' },
    { value: 'freelance', label: 'Freelance' }
]
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Job Postings</h2>
            <PrimaryButton @click="openCreateModal">
                Add Job Posting
            </PrimaryButton>
        </div>

        <!-- Job Postings List -->
        <div v-if="jobPostings.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div 
                v-for="jobPosting in jobPostings" 
                :key="jobPosting.id"
                class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow"
            >
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ jobPosting.title }}</h3>
                        <p class="text-gray-600 text-sm">{{ jobPosting.company }}</p>
                    </div>
                    <div class="flex gap-1 ml-4">
                        <button 
                            @click="editJobPosting(jobPosting)"
                            class="p-1 text-gray-400 hover:text-blue-600 transition-colors"
                            title="Edit"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button 
                            @click="deleteJobPosting(jobPosting)"
                            class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                            title="Delete"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600">
                    <div v-if="jobPosting.location" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ jobPosting.location }}
                    </div>
                    
                    <div v-if="jobPosting.salary_range" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        {{ jobPosting.salary_range }}
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        {{ jobPosting.employment_type.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                    </div>
                </div>
                
                <div v-if="jobPosting.description" class="mt-4">
                    <p class="text-gray-700 text-sm line-clamp-3">{{ jobPosting.description }}</p>
                </div>
                
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-xs text-gray-500">
                        Created {{ new Date(jobPosting.created_at).toLocaleDateString() }}
                    </span>
                    <button 
                        v-if="jobPosting.file_path"
                        @click="downloadFile(jobPosting)"
                        class="text-xs text-blue-600 hover:text-blue-800 transition-colors"
                    >
                        Download File
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Job Postings</h3>
            <p class="text-gray-600 mb-4">Get started by adding your first job posting.</p>
            <PrimaryButton @click="openCreateModal">
                Add Job Posting
            </PrimaryButton>
        </div>

        <!-- Create Modal -->
        <Modal :show="showCreateModal" @close="closeCreateModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Job Posting</h3>
                
                <!-- File Upload -->
                <div class="mb-6">
                    <InputLabel for="file" value="Upload Job Posting File (Optional)" />
                    <input 
                        ref="fileInput"
                        type="file" 
                        @change="handleFileSelect"
                        accept=".pdf,.doc,.docx,.txt"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <p class="mt-1 text-sm text-gray-500">PDF, DOC, DOCX, or TXT files. AI will auto-parse the content.</p>
                    
                    <div v-if="isUploading" class="mt-2">
                        <div class="bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="{ width: uploadProgress + '%' }"></div>
                        </div>
                        <p class="text-sm text-blue-600 mt-1">Parsing job posting...</p>
                    </div>
                </div>
                
                <form @submit.prevent="createJobPosting" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="title" value="Job Title" />
                            <TextInput 
                                id="title" 
                                v-model="form.title" 
                                type="text" 
                                class="mt-1 block w-full" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        
                        <div>
                            <InputLabel for="company" value="Company" />
                            <TextInput 
                                id="company" 
                                v-model="form.company" 
                                type="text" 
                                class="mt-1 block w-full" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors.company" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="location" value="Location" />
                            <TextInput 
                                id="location" 
                                v-model="form.location" 
                                type="text" 
                                class="mt-1 block w-full" 
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>
                        
                        <div>
                            <InputLabel for="employment_type" value="Employment Type" />
                            <select 
                                id="employment_type" 
                                v-model="form.employment_type" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="type in employmentTypes" :key="type.value" :value="type.value">
                                    {{ type.label }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.employment_type" />
                        </div>
                    </div>
                    
                    <div>
                        <InputLabel for="salary_range" value="Salary Range" />
                        <TextInput 
                            id="salary_range" 
                            v-model="form.salary_range" 
                            type="text" 
                            class="mt-1 block w-full" 
                            placeholder="e.g., $80,000 - $120,000"
                        />
                        <InputError class="mt-2" :message="form.errors.salary_range" />
                    </div>
                    
                    <div>
                        <InputLabel for="description" value="Job Description" />
                        <textarea 
                            id="description" 
                            v-model="form.description" 
                            rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>
                    
                    <div>
                        <InputLabel for="requirements" value="Requirements" />
                        <textarea 
                            id="requirements" 
                            v-model="form.requirements" 
                            rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.requirements" />
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <SecondaryButton @click="closeCreateModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Creating...' : 'Create Job Posting' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Modal -->
        <Modal :show="showEditModal" @close="closeEditModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Job Posting</h3>
                
                <form @submit.prevent="updateJobPosting" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="edit_title" value="Job Title" />
                            <TextInput 
                                id="edit_title" 
                                v-model="form.title" 
                                type="text" 
                                class="mt-1 block w-full" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        
                        <div>
                            <InputLabel for="edit_company" value="Company" />
                            <TextInput 
                                id="edit_company" 
                                v-model="form.company" 
                                type="text" 
                                class="mt-1 block w-full" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors.company" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="edit_location" value="Location" />
                            <TextInput 
                                id="edit_location" 
                                v-model="form.location" 
                                type="text" 
                                class="mt-1 block w-full" 
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>
                        
                        <div>
                            <InputLabel for="edit_employment_type" value="Employment Type" />
                            <select 
                                id="edit_employment_type" 
                                v-model="form.employment_type" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            >
                                <option v-for="type in employmentTypes" :key="type.value" :value="type.value">
                                    {{ type.label }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.employment_type" />
                        </div>
                    </div>
                    
                    <div>
                        <InputLabel for="edit_salary_range" value="Salary Range" />
                        <TextInput 
                            id="edit_salary_range" 
                            v-model="form.salary_range" 
                            type="text" 
                            class="mt-1 block w-full" 
                            placeholder="e.g., $80,000 - $120,000"
                        />
                        <InputError class="mt-2" :message="form.errors.salary_range" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_description" value="Job Description" />
                        <textarea 
                            id="edit_description" 
                            v-model="form.description" 
                            rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>
                    
                    <div>
                        <InputLabel for="edit_requirements" value="Requirements" />
                        <textarea 
                            id="edit_requirements" 
                            v-model="form.requirements" 
                            rows="4" 
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.requirements" />
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4">
                        <SecondaryButton @click="closeEditModal">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :disabled="form.processing">
                            {{ form.processing ? 'Updating...' : 'Update Job Posting' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>